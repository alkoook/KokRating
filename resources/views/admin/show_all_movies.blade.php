@extends('admin.adminpanel')
@section('content')



<div class="main-container">

    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search for a movie..." oninput="filterMovies()">

    <!-- حقل البحث للسنة -->
    <div class="mb-3">
        <input type="number" id="year-search" class="form-control" style="position: absolute;top:-5px;left:-40px;width:100px;height:50px;
    padding: 10px;
    font-size: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;" placeholder=" Year Search" oninput="filterMovies()">
    </div>

    <!-- قائمة الأفلام -->
    <div class="movies-container" id="movies-container">
        @foreach ($movies as $movie)
    <div class="movie-card" data-name="{{ strtolower($movie->name) }}" data-year="{{ $movie->year }}" onclick="showDetails(this, 'details-{{ $movie->id }}')">
        <img src="{{ asset('images/'.$movie->image)}}" alt="{{ $movie->name }}">
        <h4>{{ $movie->name}} <span>{{$movie->year}}</span></h4>
        <a href="javascript:void(0)"></a>
    </div>
@endforeach
    </div>
</div>

    <!-- تفاصيل الأفلام -->
    @foreach ($movies as $movie)
        <div id="details-{{ $movie->id }}" class="movie-details">
            <button class="close-btn" onclick="closeDetails('details-{{ $movie->id }}')">X</button>

            <img src="{{ asset('images/'.$movie->image)}}" alt="{{ $movie->name }}">

            <h3>{{ $movie->name }} <span> {{$movie->year}}</span></h3>
            <span>({{ $movie->duration }} mins)</span>
            <p>{{$movie->country}}</p>

            <div class="par">
                <div class="child"><p class="first_line">Description</p>
                    <p>{{ $movie->description }}</p>
                </div>
            <div class="child">
            <ul>
                <li value="" class="first_line">Actors</li>
              @foreach ($movie->actors as $actor )
                <li disabled><a href="{{route('actor.edit',['id'=>$actor->id])}}">{{$actor->name}}</a>
                </li>
              @endforeach
            </ul>
        </div>
        <div class="child">
            <ul>
                <li value="" class="first_line">categories</li>
              @foreach ($movie->categories as $category )
                <li disabled>{{$category->name}}
                </li>
              @endforeach
            </ul>
        </div>
        </div>
        <a href="{{route('movies.edit',$movie->id)}}">  <button class="edit-btn text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"> Edit</button></a>
            <form method="POST" action="{{ route('movies.destroy', $movie->id) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Delete</button>
            </form>
    </div>
    @endforeach


     <!-- فورم إضافة أو تعديل فيلم -->
     <div class="add-movie-form">
        <h2 id="form-title">Add New Movie</h2>
        <form method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data" id="movie-form">
            @csrf

            <div class="mb-3">
                <input type="text" id="movie-name" name="name" class="form-control" placeholder="Enter movie name" required>
            </div>

            <div class="mb-3">
                <input type="text" id="movie-country" name="country" class="form-control" placeholder="Enter movie Country" required>
            </div>

            <div class="mb-3">
                <input type="number" id="movie-duration" name="duration" class="form-control" placeholder="Duration in minutes" required>
            </div>

            <div class="mb-3">
                <select id="year-select" name="year" class="form-select" required>
                    <!-- السنوات ستُضاف ديناميكيًا عبر JavaScript -->
                </select>
            </div>

            <div class="mb-3">
                <select id="movie-category" name="categories[]" class="form-select" multiple required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <select id="movie-actors" name="actor[]" class="form-select" multiple required>
                    @foreach ($actors as $actor)
                        <option value="{{ $actor->id }}">{{ $actor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <textarea id="movie-description" name="description" class="form-control" placeholder="Enter movie description" rows="4" required style="overflow: scroll;scrollbar-width: none"></textarea>
            </div>

            <div class="mb-3">
                <input type="file" id="movie-image" name="image" class="form-control" required>
            </div>

            <button type="submit" id="submit-button" class="text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" style="width:100%" >Add new Movie</button>
        </form>
    </div>
</div>

<div class="d-flex justify-content-center" style="top:-70px; left:-250px;position:relative">
    {{ $movies->links('pagination::bootstrap-5') }}
</div>

<script>
// عرض التفاصيل
function showDetails(card, detailsId) {
    // إخفاء جميع تفاصيل الأفلام
    const allDetails = document.querySelectorAll('.movie-details');
    allDetails.forEach(detail => {
        detail.classList.remove('active');  // إخفاء التفاصيل
    });

    // عرض التفاصيل الخاصة بالفيلم الذي تم الضغط عليه
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

    // إذا كان الكليك خارج تفاصيل الفيلم أو الفيلم نفسه
    allDetails.forEach(detail => {
        if (!detail.contains(event.target) && !movieContainer.contains(event.target)) {
            detail.classList.remove('active');
        }
    });
});





// فلترة الأفلام حسب الاسم والسنة
function filterMovies() {
    const searchInput = document.getElementById('search-input').value.toLowerCase(); // النص المدخل للبحث عن الفيلم
    const searchYear = document.getElementById('year-search').value.trim(); // السنة المدخلة للبحث
    const movieCards = document.querySelectorAll('.movie-card'); // جلب كل الأفلام

    movieCards.forEach(card => {
        const movieName = card.getAttribute('data-name').toLowerCase(); // اسم الفيلم (بالحروف الصغيرة)
        const movieYear = card.getAttribute('data-year').toLowerCase(); // سنة الفيلم (بالحروف الصغيرة)

        // التحقق من فلترة الاسم والسنة معًا
        const matchesName = movieName.includes(searchInput); // تطابق الاسم مع النص المدخل
        const matchesYear = movieYear.includes(searchYear); // تطابق السنة مع السنة المدخلة

        // إذا كان الاسم والسنة متطابقين مع البحث، أو أحدهما فقط، نعرض الفيلم
        if ((matchesName && matchesYear) || (matchesName && searchYear === '') || (matchesYear && searchInput === '')) {
            card.style.display = 'block'; // إظهار الفيلم
        } else {
            card.style.display = 'none'; // إخفاء الفيلم
        }
    });
}









</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('movie-actors');
        const choices = new Choices(element, {
            searchEnabled: true,   // تفعيل البحث
            removeItemButton: true, // زر إزالة الخيار
            placeholderValue: 'Select actors', // نص افتراضي
            itemSelectable: 'my-choices-item-selectable',

        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('movie-category');
        const choices = new Choices(element, {
            searchEnabled: true,   // تفعيل البحث
            removeItemButton: true, // زر إزالة الخيار
            placeholderValue: 'Select categories', // نص افتراضي
        });
    });
</script>

@endsection

