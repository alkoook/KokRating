@extends('user.userPanel')
@section('content')
<link rel="stylesheet" href="{{asset('css_for_users/movie.css')}}">
<div class="main-container">
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search for a series..." oninput="filterMovies()">

        <!-- حقل البحث للسنة -->
        <div class="mb-3">
            <input type="number" id="year-search" class="form-control" style="position: absolute;top:0px;left:0px;width:100px;height:50px;
            padding: 10px;
            font-size: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;" placeholder=" Year Search" oninput="filterMovies()">
        </div>

        <!-- قائمة المسلسلات -->
        <div class="movies-container" id="movies-container">
            @foreach ($series as $serie)
            <div class="movie-card" data-name="{{ strtolower($serie->name) }}" data-year="{{ $serie->year }}" onclick="showDetails(this, 'details-{{ $serie->id }}')">
                <img src="{{ asset('images/'.$serie->image)}}" alt="{{ $serie->name }}">
                <h4>{{ $serie->name}} <span>{{$serie->year}}</span></h4>
                <a href="javascript:void(0)"></a>
            </div>
            @endforeach
        </div>
    </div>

    @foreach ($series as $serie)
    <div id="details-{{ $serie->id }}" class="movie-details">
        <button class="close-btn" onclick="closeDetails('details-{{ $serie->id }}')">X</button>
        <div class="parent">
            <div class="left">
                <h3>{{ $serie->name }} <span> {{$serie->year}}</span></h3>
                <span>({{ $serie->episode }} Episode)</span>
                <p>{{$serie->country}} :البلد</p>
                <img src="{{ asset('images/'.$serie->image)}}" alt="{{ $serie->name }}">
                <div class="par">
                    <div class="child">
                        <p class="first_line">Description</p>
                        <p>{{ $serie->description }}</p>
                    </div>
                </div>
            </div>
            <div class="right">
                <p>المسلسل من بطولة</p>
                <div class="act_cat">
                    <ul>
                        @foreach ($serie->actors as $actor)
                            <li disabled><a href="{{route('user.actorInformation',['id'=>$actor->id])}}">{{$actor->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
                <p>تصنيف المسلسل</p>
                <div class="act_cat">
                    <ul>
                        @foreach ($serie->categories as $category)
                            <li disabled>{{$category->name}}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- زر لإضافة مراجعة -->
                <button class="edit-btn text-white bg-gradient-to-r from-gray-400 via-black-500 to-gray-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-black-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" onclick="showReviewForm({{ $serie->id }})">إضافة مراجعة</button>

                <div id="review-form-{{ $serie->id }}" class="review-form" style="display: none; margin-top: 0px;">
                    <form action="{{route('user.series.review',['series_id'=>$serie->id])}}" method="POST">
                        @csrf
                    <textarea placeholder="اكتب مراجعتك هنا..." class="form-control" name="review" rows="4" style="background: transparent;color:white;font-family:'Times New Roman', Times, serif"></textarea>

                    <div class="movie-rating">
                        <h4>Rate this Movie</h4>
                        <input type="range" id="rating-slider-{{ $serie->id }}" name="rating" min="1" max="10" value="5"/>
                        <span id="rating-value-{{ $serie->id }}">5</span>
                    </div>
                    <button type="submit" class="edit-btn text-white bg-gradient-to-r from-gray-400 via-black-500 to-gray-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-black-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" onclick="submitReview({{ $serie->id }})">إرسال المراجعة</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- دالة لإظهار المراجعة --}}
<script>
    function showReviewForm(serieId) {
        const reviewForm = document.getElementById(`review-form-${serieId}`);
        const reviewButton = document.querySelector(`#details-${serieId} .edit-btn`);

        reviewForm.style.display = 'block'; // إظهار القسم
        reviewButton.style.display = 'none'; // إخفاء زر إضافة المراجعة
    }
</script>

{{-- التقييم --}}
<script>
 function showReviewForm(seriesId) {
    const reviewForm = document.getElementById(`review-form-${seriesId}`);
    const reviewButton = document.querySelector(`#details-${seriesId} .edit-btn`);

    reviewForm.style.display = 'block'; // إظهار القسم
    reviewButton.style.display = 'none'; // إخفاء زر إضافة المراجعة

    // تحديث التقييم الخاص بالفيلم عند تحريك المؤشر
    const slider = document.getElementById(`rating-slider-${seriesId}`);
    const ratingValue = document.getElementById(`rating-value-${seriesId}`);

    slider.addEventListener('input', () => {
        ratingValue.textContent = slider.value; // تحديث القيمة المعروضة
    });
 }
</script>

{{-- عرض التفاصيل --}}
<script>
    function showDetails(card, detailsId) {
        // إخفاء جميع تفاصيل المسلسلات
        const allDetails = document.querySelectorAll('.movie-details');
        allDetails.forEach(detail => {
            detail.classList.remove('active');  // إخفاء التفاصيل
        });

        // عرض التفاصيل الخاصة بالمسلسل الذي تم الضغط عليه
        const detailsElement = document.getElementById(detailsId);
        detailsElement.classList.add('active');  // إظهار التفاصيل

        // تمرير الصفحة لعرض التفاصيل بشكل جيد
        detailsElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // إغلاق التفاصيل
    function closeDetails(detailsId) {
        document.getElementById(detailsId).classList.remove('active');
    }

    // إخفاء التفاصيل عند الضغط في أي مكان خارج التفاصيل
    document.addEventListener('click', function(event) {
        const allDetails = document.querySelectorAll('.movie-details');
        const movieContainer = document.getElementById('movies-container');

        // إذا كان الكليك خارج تفاصيل المسلسل أو المسلسل نفسه
        allDetails.forEach(detail => {
            if (!detail.contains(event.target) && !movieContainer.contains(event.target)) {
                detail.classList.remove('active');
            }
        });
    });

    // فلترة المسلسلات حسب الاسم والسنة
    function filterMovies() {
        const searchInput = document.getElementById('search-input').value.toLowerCase(); // النص المدخل للبحث عن المسلسل
        const searchYear = document.getElementById('year-search').value.trim(); // السنة المدخلة للبحث
        const movieCards = document.querySelectorAll('.movie-card'); // جلب كل المسلسلات

        movieCards.forEach(card => {
            const movieName = card.getAttribute('data-name').toLowerCase(); // اسم المسلسل (بالحروف الصغيرة)
            const movieYear = card.getAttribute('data-year').toLowerCase(); // سنة المسلسل (بالحروف الصغيرة)

            // التحقق من فلترة الاسم والسنة معًا
            const matchesName = movieName.includes(searchInput); // تطابق الاسم مع النص المدخل
            const matchesYear = movieYear.includes(searchYear); // تطابق السنة مع السنة المدخلة

            // إذا كان الاسم والسنة متطابقين مع البحث، أو أحدهما فقط، نعرض المسلسل
            if ((matchesName && matchesYear) || (matchesName && searchYear === '') || (matchesYear && searchInput === '')) {
                card.style.display = 'block'; // إظهار المسلسل
            } else {
                card.style.display = 'none'; // إخفاء المسلسل
            }
        });
    }
</script>

@endsection
