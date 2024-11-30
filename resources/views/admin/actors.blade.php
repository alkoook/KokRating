@extends('admin.adminpanel')

@section('content')

<link rel="stylesheet" href="{{ asset('css_for_admin/actor.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="container">

    <div class="actors-section-container">
        <!-- البحث داخل قسم الممثلين -->
        <div class="search-bar">
            <input type="text" id="actor-search-input" class="search-input" placeholder="Search for an actor name..." oninput="filterActors()">
        </div>

        @foreach($actors as $actor)

        <div class="actor-card" data-name="{{ strtolower($actor->name) }}" onclick="showDetails('details-actor-{{ $actor->id }}')">
            <img src="{{ asset('images/'.$actor->photo) }}" alt="{{ $actor->name }}" onclick="showDetails('details-actor-{{ $actor->id }}',event)" >
            <div class="card-header">
                <p  onclick="showDetails('details-actor-{{ $actor->id }}',event)">{{ $actor->name }}</p>
            </div>
        </div>

            <!-- تفاصيل الممثل -->
            <div id="details-actor-{{ $actor->id }}" class="details-popup">
                <button class="close-btn" onclick="closeDetails('details-actor-{{ $actor->id }}')">X</button>
                <img src="{{asset('images/'.$actor->photo)}}" alt="{{$actor->name}}">
                <h3>{{ $actor->name }}</h3>
                <p>Born in {{$actor->birth_date}}</p>
                @if($actor->death_date)
                <p>Death in {{$actor->death_date}}</p>
                <p>Age at Death :{{$actor->age}}</p>
                @else
                <p>Age now :{{$actor->age}}</p>
                @endif
                <div class="par">
                    <p class="child">{{$actor->biography}}</p>
                    <ul class="child">
                        <li class="child">المسلسلات</li>
                        @foreach ($actor->Series as $series)
                      <a href="{{route('series.edit',$series->id)}}"><li> {{$series->name}}</li></a>
                        @endforeach
                    </ul>
                    <ul class="child">
                        <li class="child">الأفلام</li>
                        @foreach ($actor->Movies as $movie)
                      <a href="{{route('movies.edit',$movie->id)}}"><li> {{$movie->name}}</li></a>
                        @endforeach
                    </ul>

                </div>
                <a href="{{route('actor.edit',$actor->id)}}">
                    <button class="edit-btn text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Edit</button>
                </a>
                <form method="POST" action="{{ route('actor.delete', $actor->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Delete</button>
                </form>
            </div>
        @endforeach
    </div>

     <!-- قسم الفورم: لإضافة ممثل جديد -->
     <div class="form-container">
     <form action="{{route('actors.store')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        <div class="relative mb-6">
        <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Name</label>
        <div class="relative text-gray-500 focus-within:text-gray-900 mb-6">
          <input type="text" id="actor-name" class="block w-full h-11 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('name')is-invalid  @enderror" placeholder="Enter Name" name="name">
          @error('name')
          <div class="text-danger invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        </div>

        <div class="relative mb-6">
            <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Country</label>
            <div class="relative text-gray-500 focus-within:text-gray-900 mb-6">
              <input type="text" id="actor-name" class="block w-full h-11 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('country')is-invalid  @enderror" placeholder="Enter Country" name="country">
              @error('country')
              <div class="text-danger invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            </div>

            <div class="relative mb-6">
            <label for="birth_date">Birth Date </label>
            <input type="text" id="birth_date" name="birth_date" class="block w-full h-11 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('birth_date')is-invalid  @enderror" style="margin:10px;">
            @error('birth_date')
              <div class="text-danger invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="relative mb-6">
                <label for="birth_date">Death Date </label>
                <input type="text" id="birth_date" name="death_date" class="block w-full h-11 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none" style="margin:10px;">
                </div>


        <div class="relative mb-6">
        <div class="relative text-gray-500 focus-within:text-gray-900 mb-6">
          <textarea id="actor-bio" class="block w-full h-20 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-400 focus:outline-nonex    " placeholder="Enter Biography" name="bio" style="overflow: scroll;scrollbar-width:none;"></textarea>
        </div>
        </div>

        <div class="custom-file-upload" style="margin: 10px;">
            <span id="file-name">Choose an image</span>
            <button type="button" id="upload-button" class="btn btn-primary">Upload</button>
            <input type="file" id="actor-image" name="image" class="form-control @error('image')is-invalid @enderror" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name;">
            @error('image')
                <div class="text-danger invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center justify-center">
        <button type="submit" class="w-52 h-12 shadow-sm rounded-full bg-indigo-600 hover:bg-indigo-800 transition-all duration-700 text-white text-base font-semibold leading-7">Add Actor</button>
        </div>
    </form>
     </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
            flatpickr("#birth_date", {
            dateFormat: "Y-m-d", // تنسيق التاريخ
            maxDate: "today",   // تحديد اليوم الحالي كحد أقصى
            yearRange: [1900, new Date().getFullYear()] // نطاق السنوات
        });
    </script>
</script>
<script>

document.getElementById('upload-button').addEventListener('click', function () {
    document.getElementById('actor-image').click();
});

// عرض اسم الملف عند اختياره
document.getElementById('actor-image').addEventListener('change', function () {
    const fileName = this.files[0] ? this.files[0].name : 'Choose an image';
    document.getElementById('file-name').textContent = fileName;
});

// دالة الفلترة
function filterActors() {
    const searchInput = document.getElementById("actor-search-input").value.toLowerCase();
    const actorCards = document.querySelectorAll(".actor-card");

    actorCards.forEach(card => {
        const actorName = card.getAttribute("data-name");
        if (actorName.includes(searchInput)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}

// عرض التفاصيل عند الضغط على الكارد
function showDetails(actorId, event) {
    event.stopPropagation(); // منع انتقال الحدث إلى مستمعات أعلى

    // إغلاق كل النوافذ المفتوحة أولاً
    const openDetails = document.querySelectorAll(".details-popup");
    openDetails.forEach(detail => {
        detail.style.display = "none"; // إغلاق النوافذ المفتوحة
    });

    // عرض النافذة الخاصة بالممثل المحدد
    var detailsElement = document.getElementById(actorId);
    if (detailsElement) {
        detailsElement.style.display = 'block'; // فتح النافذة
    }
}

// إغلاق نافذة التفاصيل عند الضغط على زر الإغلاق
function closeDetails(detailId) {
    const detail = document.getElementById(detailId);
    if (detail) {
        detail.style.display = "none";
    }
}

// إغلاق جميع النوافذ عند الضغط على أي مكان خارج التفاصيل
document.addEventListener("click", function () {
    const openDetails = document.querySelectorAll(".details-popup");
    openDetails.forEach(detail => {
        detail.style.display = "none";
    });
});

// منع إغلاق التفاصيل عند الضغط داخل النافذة نفسها
document.querySelectorAll(".details-popup").forEach(detail => {
    detail.addEventListener("click", function (event) {
        event.stopPropagation();
    });
});



</script>




@endsection
