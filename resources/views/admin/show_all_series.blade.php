@extends('admin.adminpanel')
@section('content')

<div class="main-container">

    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search for a series..." oninput="filterSeries()">

        <!-- حقل البحث للسنة -->
        <div class="mb-3">
            <input type="number" id="year-search" class="form-control" style="position: absolute;top:-5px;left:-40px;width:100px;height:50px;
        padding: 10px;
        font-size: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;" placeholder="Year Search" oninput="filterSeries()">
        </div>

    <!-- قائمة المسلسلات -->
    <div class="series-container" id="series-container">
        @foreach ($series as $serie)
        <div class="series-card" data-name="{{ strtolower($serie->name) }}" data-year="{{ $serie->year }}" onclick="showDetails(this, 'details-{{ $serie->id }}')">
            <img src="{{ asset('images/'.$serie->image)}}" alt="{{ $serie->name }}">
            <h4>{{ $serie->name }} <span>{{$serie->year}}</span></h4>
            <a href="javascript:void(0)"></a>
        </div>
        @endforeach
    </div>
</div>

    <!-- تفاصيل المسلسلات -->
    @foreach ($series as $serie)
        <div id="details-{{ $serie->id }}" class="series-details">
            <button class="close-btn" onclick="closeDetails('details-{{ $serie->id }}')">X</button>

            <img src="{{ asset('images/'.$serie->image)}}" alt="{{ $serie->name }}">

            <h3>{{ $serie->name }} <span> {{$serie->year}}</span></h3>
            <span>({{ $serie->episode }} Episode)</span>
            <p>{{ $serie->country }}</p>

            <div class="par">
                <div class="child"><p class="first_line">Description</p>
                    <p>{{ $serie->description }}</p>
                </div>
            <div class="child">
            <ul>
                <li value="" class="first_line">Actors</li>
              @foreach ($serie->actors as $actor )
                <li><a href="{{route('actor.edit',['id'=>$actor->id])}}">{{$actor->name}}</a>
                </li>
              @endforeach
            </ul>
        </div>
        <div class="child">
            <ul>
                <li value="" class="first_line">categories</li>
              @foreach ($serie->categories as $category )
                <li disabled>{{$category->name}}
                </li>
              @endforeach
            </ul>
        </div>
    </div>
    <a href="{{route('series.edit',$serie->id)}}"><button class="edit-btn text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Edit</button></a>
            <form method="POST" action="{{ route('series.destroy', $serie->id) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Delete</button>
            </form>
        </div>
    @endforeach

    <!-- فورم إضافة أو تعديل مسلسل -->
    <div class="add-series-form">
        <h2 id="form-title">Add New Series</h2>
        <form method="POST" action="{{ route('series.store') }}" enctype="multipart/form-data" id="series-form">
            @csrf

            <div class="mb-3">
                <input type="text" id="series-name" name="name" class="form-control" placeholder="Enter series name" required>
            </div>

            <div class="mb-3">
                <input type="text" id="series-country" name="country" class="form-control" placeholder="Enter Series Country" required>
            </div>

            <div class="mb-3">
                <input type="number" id="series-episode" name="episode" class="form-control" placeholder="Episodes" required>
            </div>

            <div class="mb-3">
                <select id="year-select" name="year" class="form-select" required>
                    <!-- السنوات ستُضاف ديناميكيًا عبر JavaScript -->
                </select>
            </div>

            <div class="mb-3">
                <select id="series-category" name="categories[]" class="form-select" multiple required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <select id="series-actors" name="actor[]" class="form-select" multiple required>
                    @foreach ($actors as $actor)
                        <option value="{{ $actor->id }}">{{ $actor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <textarea id="series-description" name="description" class="form-control" placeholder="Enter series description" rows="4" required style="overflow: scroll;scrollbar-width: none"></textarea>
            </div>

            <div class="mb-3">
                <input type="file" id="series-image" name="image" class="form-control" required>
            </div>

            <button type="submit" id="submit-button" class="text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" style="width:100%" >Add new Series</button>
        </form>
    </div>
</div>

<div class="d-flex justify-content-center" style="top:-70px; left:-250px;position:relative">
    {{ $series->links('pagination::bootstrap-5') }}
</div>

<script>


// عرض التفاصيل
function showDetails(card, detailsId) {
    const allDetails = document.querySelectorAll('.series-details');
    allDetails.forEach(detail => {
        detail.classList.remove('active');
    });

      // عرض التفاصيل الخاصة بالمسلسل الذي تم الضغط عليه
    const detailsElement = document.getElementById(detailsId);
    detailsElement.classList.add('active');  // إظهار التفاصيل

    detailsElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// إغلاق التفاصيل
function closeDetails(detailsId) {
    document.getElementById(detailsId).classList.remove('active');
}

// إخفاء التفاصيل عند الضغط في أي مكان خارج التفاصيل
document.addEventListener('click', function(event) {
    const allDetails = document.querySelectorAll('.series-details');
    const movieContainer = document.getElementById('series-container');

    // إذا كان الكليك خارج تفاصيل
    allDetails.forEach(detail => {
        if (!detail.contains(event.target) && !movieContainer.contains(event.target)) {
            detail.classList.remove('active');
        }
    });
});

// فلترة المسلسلات حسب الاسم والسنة
function filterSeries() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const searchYear = document.getElementById('year-search').value.trim();
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('series-actors');
        const choices = new Choices(element, {
            searchEnabled: true,   // تفعيل البحث
            removeItemButton: true, // زر إزالة الخيار
            placeholderValue: 'Select actors', // نص افتراضي
            itemSelectable: 'my-choices-item-selectable',

        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('series-category');
        const choices = new Choices(element, {
            searchEnabled: true,   // تفعيل البحث
            removeItemButton: true, // زر إزالة الخيار
            placeholderValue: 'Select categories', // نص افتراضي
        });
    });
</script>
@endsection
