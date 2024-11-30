@extends('user.userPanel')
@section('content')
<link rel="stylesheet" href="{{ asset('css_for_users/mov_ser.css') }}">

<div class="main-container">
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search for a series..." oninput="filterSeries()">

        <!-- حقل البحث للسنة -->
        <div class="mb-3">
            <input type="number" id="year-search" class="form-control" style="position: absolute;top:0px;left:0px;width:100px;height:50px;padding: 10px;font-size: 10px;border: 1px solid #ccc;border-radius: 5px;" placeholder="Year Search" oninput="filterSeries()">
        </div>
        @php
        // الحصول على المسلسلات الفريدة
        $uniqueSeries = $reviews->map(function($review) {
            return $review->reviewable;
        })->unique('id');
        @endphp

        <div class="parent">
            @foreach ($uniqueSeries as $series)
            <div class="movies-container" id="movies-container">
                <div class="left">
                    @php
                    // ترتيب المراجعات حسب عدد الإعجابات تنازلياً
                    $sortedReviews = $series->reviews->sortByDesc(fn($review) => $review->likes->count());

                    // حساب متوسط التقييمات
                    $averageRating = $series->reviews->avg('rating');
                    @endphp
                    <img src="{{ asset('images/'.$series->image) }}" alt="{{ $series->name }}">
                    <div class="movie-card" data-name="{{ strtolower($series->name) }}" data-year="{{ $series->year }}">

                        <h4 style="font-family:fantasy;">{{ $series->name }} <span>{{ $series->year }}</span> <p style="position: sticky">⭐{{ number_format($averageRating, 1) }}</p></h4>

                        <h4>({{ $series->episode }} Episode)</h4>
                    </div>
                </div>

                <div class="right">
                    @foreach ($sortedReviews as $seriesReview)
                    <div class="re">
                        <span style="background: transparent;border-radius:5px;padding:5px">
                            {{ $seriesReview->user->name }}
                        </span>
                        <p>{{ $seriesReview->content }}</p>
                        <button class="like-button" data-review-id="{{ $seriesReview->id }}">
                            ❤️ إعجاب (<span class="like-count" style="color:rgb(240, 240, 25)">{{ $seriesReview->likes->count() }}</span>)
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
function filterSeries() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const searchYear = document.getElementById('year-search').value.trim();
    const seriesContainers = document.querySelectorAll('.movies-container');

    let matchedSeries = []; // لحفظ العناصر المطابقة
    let unmatchedSeries = []; // لحفظ العناصر غير المطابقة

    seriesContainers.forEach(container => {
        const seriesCard = container.querySelector('.movie-card');
        const seriesName = seriesCard.getAttribute('data-name').toLowerCase();
        const seriesYear = seriesCard.getAttribute('data-year');

        const matchesName = seriesName.includes(searchInput);
        const matchesYear = seriesYear.includes(searchYear);

        if ((matchesName && matchesYear) || (matchesName && !searchYear) || (matchesYear && !searchInput)) {
            // إذا تم العثور على المسلسل
            container.style.visibility = 'visible';
            container.style.opacity = '1';
            matchedSeries.push(container); // أضفها إلى قائمة المطابقات
        } else {
            // إذا لم يتم العثور على المسلسل
            container.style.visibility = 'hidden';
            container.style.opacity = '0';
            unmatchedSeries.push(container); // أضفها إلى قائمة غير المطابقات
        }
    });

    // ترتيب العناصر المطابقة لتظهر في الأعلى
    matchedSeries.forEach(series => {
        series.style.order = -1; // تحديد أن العنصر يظهر أولاً
    });

    // إعادة ترتيب العناصر غير المطابقة إلى وضعها الطبيعي
    unmatchedSeries.forEach(series => {
        series.style.order = ''; // إعادة تحديد ترتيب العنصر إذا لم يكن هناك مطابقة
    });
}
</script>

@endsection
