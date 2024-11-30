@extends('user.userPanel')
@section('content')
<link rel="stylesheet" href="{{ asset('css_for_users/mov_ser.css') }}">

<div class="main-container">
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search for a movie..." oninput="filterMovies()">

        <!-- حقل البحث للسنة -->
        <div class="mb-3">
            <input type="number" id="year-search" class="form-control" style="position: absolute;top:0px;left:0px;width:100px;height:50px;padding: 10px;font-size: 10px;border: 1px solid #ccc;border-radius: 5px;" placeholder="Year Search" oninput="filterMovies()">
        </div>
        @php
// الحصول على الأفلام الفريدة
$uniqueMovies = $reviews->map(function($review) {
    return $review->reviewable;
})->unique('id');
@endphp



<div class="parent">
    @foreach ($uniqueMovies as $movie)
    <div class="movies-container" id="movies-container">
        <div class="left">
            @php
// ترتيب المراجعات حسب عدد الإعجابات تنازلياً
$sortedReviews = $movie->reviews->sortByDesc(fn($review) => $review->likes->count());

// حساب متوسط التقييمات
$averageRating = $movie->reviews->avg('rating');
@endphp
            <img src="{{ asset('images/'.$movie->image) }}" alt="{{ $movie->name }}">
            <div class="movie-card" data-name="{{ strtolower($movie->name) }}" data-year="{{ $movie->year }}">

                <h4 style="font-family:fantasy;">{{ $movie->name }} <span>{{ $movie->year }}</span> <p style="position: sticky">⭐{{ number_format($averageRating, 1) }}</p></h4>

                <h4>({{ $movie->duration }} Minute)</h4>
            </div>
        </div>


        <div class="right">


            @foreach ($sortedReviews as $movieReview)
                <div class="re">
                    <span style="background: transparent;border-radius:5px;padding:5px">
                        {{ $movieReview->user->name }}
                    </span>
                    <p>{{ $movieReview->content }}</p>
                    <button class="like-button" data-review-id="{{ $movieReview->id }}">
                        ❤️ إعجاب (<span class="like-count"  style="color:rgb(240, 240, 25)">{{ $movieReview->likes->count() }}</span>)
                    </button>
                </div>
            @endforeach
        </div>


    </div>
    @endforeach
</div>


</div>
</div>
{{-- الاعجابات --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const likeButtons = document.querySelectorAll('.like-button');

        likeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reviewId = this.getAttribute('data-review-id');
                const likeCountElement = this.querySelector('.like-count');

                // إرسال AJAX لتحديث الإعجابات
                fetch('/like-review', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // تأكد من أن الـ CSRF Token مضاف بشكل صحيح
                    },
                    body: JSON.stringify({ review_id: reviewId }) // إرسال الـ review_id
                })
                .then(response => {
                    // تحقق من أن الرد هو JSON
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        likeCountElement.textContent = data.new_like_count; // تحديث عدد الإعجابات
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
            });
        });
    });

</script>



<script>
function filterMovies() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const searchYear = document.getElementById('year-search').value.trim();
    const movieContainers = document.querySelectorAll('.movies-container');

    let matchedMovies = []; // لحفظ العناصر المطابقة
    let unmatchedMovies = []; // لحفظ العناصر غير المطابقة

    movieContainers.forEach(container => {
        const movieCard = container.querySelector('.movie-card');
        const movieName = movieCard.getAttribute('data-name').toLowerCase();
        const movieYear = movieCard.getAttribute('data-year');

        const matchesName = movieName.includes(searchInput);
        const matchesYear = movieYear.includes(searchYear);

        if ((matchesName && matchesYear) || (matchesName && !searchYear) || (matchesYear && !searchInput)) {
            // إذا تم العثور على الفيلم
            container.style.visibility = 'visible';
            container.style.opacity = '1';
            matchedMovies.push(container); // أضفها إلى قائمة المطابقات
        } else {
            // إذا لم يتم العثور على الفيلم
            container.style.visibility = 'hidden';
            container.style.opacity = '0';
            unmatchedMovies.push(container); // أضفها إلى قائمة غير المطابقات
        }
    });

    // ترتيب العناصر المطابقة لتظهر في الأعلى
    matchedMovies.forEach(movie => {
        movie.style.order = -1; // تحديد أن العنصر يظهر أولاً
    });

    // إعادة ترتيب العناصر غير المطابقة إلى وضعها الطبيعي
    unmatchedMovies.forEach(movie => {
        movie.style.order = ''; // إعادة تحديد ترتيب العنصر إذا لم يكن هناك مطابقة
    });
}




</script>
@endsection
