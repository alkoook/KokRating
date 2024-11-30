@extends('admin.adminpanel')

@section('content')
<link rel="stylesheet" href='{{ URL('css_for_admin/home.css') }}'>

<div class="container">
    <div class="section movies-section">
        <div class="search-bar">
            <input type="text" id="movie-search-input" class="search-input" placeholder="Search for a movie..." oninput="filterMovies()">
            <input type="number" id="movie-year-search" class="search-input" placeholder="Year" oninput="filterMovies()">
        </div>
        @foreach ($movies as $movie)
        <div class="movie-card" data-name="{{ strtolower($movie->name) }}" data-year="{{ $movie->year }}" onclick="showDetails(this, 'details-movie-{{ $movie->id }}')">
            <img src="{{ asset('images/'.$movie->image) }}" alt="{{ $movie->name }}">
            <h4>{{ $movie->name }} <span>{{ $movie->year }}</span></h4>
        </div>
        <!-- تفاصيل الفيلم -->
        <div id="details-movie-{{ $movie->id }}" class="details-popup">
            <button class="close-btn" onclick="closeDetails('details-movie-{{ $movie->id }}')">X</button>
            <img src="{{ asset('images/'.$movie->image) }}" alt="{{ $movie->name }}">
            <h3>{{ $movie->name }} <span>{{ $movie->year }}</span></h3>
            <span>({{$movie->duration}}) Minute</span>
            <div class="par">
                <div class="child"><p class="first_line">Description</p>
                    <p>{{ $movie->description }}</p>
                </div>
                <div class="child">
                    <ul>
                        <li class="first_line">Actors</li>
                        @foreach ($movie->actors as $actor)
                            <li disabled><a href="{{route('actor.edit',['id'=>$actor->id])}}">{{ $actor->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="child">
                    <ul>
                        <li class="first_line">Categories</li>
                        @foreach ($movie->categories as $category)
                            <li disabled>{{ $category->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <!-- قسم المسلسلات -->
    <div class="section series-section">
        <div class="search-bar">
            <input type="text" id="series-search-input" class="search-input" placeholder="Search for a series..." oninput="filterSeries()">
            <input type="number" id="series-year-search" class="search-input" placeholder="Year" oninput="filterSeries()">
        </div>

            @foreach ($series as $serie)
            <div class="series-card" data-name="{{ strtolower($serie->name) }}" data-year="{{ $serie->year }}" onclick="showDetails(this, 'details-series-{{ $serie->id }}')">
                <img src="{{ asset('images/'.$serie->image) }}" alt="{{ $serie->name }}">
                <h4>{{ $serie->name }} <span>{{ $serie->year }}</span></h4>
            </div>

            <!-- تفاصيل المسلسل -->
            <div id="details-series-{{ $serie->id }}" class="details-popup">
                <button class="close-btn" onclick="closeDetails('details-series-{{ $serie->id }}')">X</button>
                <img src="{{ asset('images/'.$serie->image) }}" alt="{{ $serie->name }}">
                <h3>{{ $serie->name }} <span>{{ $serie->year }}</span></h3>
                <span>{{$serie->episode}} Episode</span>
                <div class="par">
                    <div class="child"><h1>Description</h1>
                        <p>{{ $serie->description }}</p>
                    </div>
                    <div class="child">
                        <ul>
                            <li class="first_line">Actors</li>
                            @foreach ($serie->actors as $actor)
                                <li disabled><a href="#">{{ $actor->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="child">
                        <ul>
                            <li class="first_line">Categories</li>
                            @foreach ($serie->categories as $category)
                                <li disabled><a href="#">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
    </div>

<script>
// عرض التفاصيل
function showDetails(card, detailsId) {
    const allDetails = document.querySelectorAll('.details-popup');
    allDetails.forEach(detail => {
        detail.classList.remove('active');
    });

    const detailsElement = document.getElementById(detailsId);
    detailsElement.classList.add('active');
    detailsElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// إغلاق التفاصيل
function closeDetails(detailsId) {
    const detailsElement = document.getElementById(detailsId);
    detailsElement.classList.remove('active');
}

// إغلاق التفاصيل عند النقر خارجها
document.addEventListener('click', function(event) {
    const allDetails = document.querySelectorAll('.details-popup');
    const movieCards = document.querySelectorAll('.movie-card');
    const seriesCards = document.querySelectorAll('.series-card');

    const clickedOutside = ![...allDetails].some(detail => detail.contains(event.target)) &&
                          ![...movieCards, ...seriesCards].some(card => card.contains(event.target));

    if (clickedOutside) {
        allDetails.forEach(detail => {
            detail.classList.remove('active');
        });
    }
});

// فلترة الأفلام
function filterMovies() {
    const searchInput = document.getElementById('movie-search-input').value.toLowerCase();
    const searchYear = document.getElementById('movie-year-search').value.trim();
    const movieCards = document.querySelectorAll('.movie-card');

    movieCards.forEach(card => {
        const movieName = card.getAttribute('data-name').toLowerCase();
        const movieYear = card.getAttribute('data-year').toLowerCase();

        const matchesName = movieName.includes(searchInput);
        const matchesYear = movieYear.includes(searchYear);

        if ((matchesName && matchesYear) || (matchesName && searchYear === '') || (matchesYear && searchInput === '')) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// فلترة المسلسلات
function filterSeries() {
    const searchInput = document.getElementById('series-search-input').value.toLowerCase();
    const searchYear = document.getElementById('series-year-search').value.trim();
    const seriesCards = document.querySelectorAll('.series-card');

    seriesCards.forEach(card => {
        const seriesName = card.getAttribute('data-name').toLowerCase();
        const seriesYear = card.getAttribute('data-year').toLowerCase();

        const matchesName = seriesName.includes(searchInput);
        const matchesYear = seriesYear.includes(searchYear);

        if ((matchesName && matchesYear) || (matchesName && searchYear === '') || (matchesYear && searchInput === '')) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

@endsection
