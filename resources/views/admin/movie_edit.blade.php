@extends('admin.adminpanel')
@section('content')
<div class="d-flex justify-content-center align-items-center" >
    <div class="w-50 p-4 bg-light rounded shadow">
        <form action="{{route('movies.update',$movie->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            <img src="{{asset('images/'.$movie->image)}}" alt="{{$movie->name}}" style="width:100%;height:200;border-radius:20px">

            <div class="grid md:grid-cols-2 grid-cols-1 gap-x-8">
                <div class="relative mb-6">
                 <input type="text" name="name" id="default-search" class="block w-full h-11 px-5 py-2.5 bg-white leading-7 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none " placeholder="Enter Name" value="{{$movie->name}}">
                </div>
                <div class="relative mb-6">
                 <input type="text" name="country" id="default-search" class="block w-full h-11 px-5 py-2.5 bg-white leading-7 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none "placeholder="Enter Country" value="{{$movie->country}}">
                </div>
            </div>

                <div class="grid md:grid-cols-2 grid-cols-1 gap-x-8">
                    <div class="relative mb-6">
                     <input name="duration" type="number" id="default-search" class="block w-full h-11 px-5 py-2.5 bg-white leading-7 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none " placeholder="Enter Movie Duration" value="{{$movie->duration}}">
                    </div>
                    <div class="relative mb-6">
                        <select id="year-select" name="year" class="form-select">
                            <option value="{{$movie->year}}">{{$movie->year}}</option>
                            <!-- السنوات ستُضاف ديناميكيًا عبر JavaScript -->
                        </select>
                     </div>
                </div>

                <div class="custom-file-upload" style="margin: 10px;">
                    <span id="file-name">Choose an image if you want to Replace the Old One</span>
                    <button type="button" id="upload-button" class="btn btn-primary">Upload</button>
                    <input type="file" id="movie-image" name="image" class="form-control" style="display: none;">
                </div>

                <div class="relative mb-6">
                    <textarea name="description" type="text" id="default-search" class="block w-full h-40 px-5 py-2.5 bg-white leading-7 resize-none text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-2xl placeholder-gray-400 focus:outline-none " placeholder="Enter Description">{{$movie->description}}</textarea>
                    </div>



            <div class="mb-3">
                <select id="movie-category" name="categories[]" class="form-select" multiple >
                    @foreach ($movie->categories as $category )
                    <option value="{{$category->id}}" selected>{{$category->name}}</option>
                    @endforeach
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <select id="movie-actors" name="actors[]" class="form-select" multiple >
                    @foreach ($movie->Actors as $actor )
                    <option value="{{$actor->id}}" selected>{{$actor->name}}</option>
                    @endforeach
                    @foreach ($actors as $actor)
                        <option value="{{ $actor->id }}">{{ $actor->name }}</option>
                    @endforeach
                </select>
            </div>



            <div class="flex items-center justify-center">
            <button type="submit" class="w-52 h-12 shadow-sm rounded-full bg-indigo-600 hover:bg-indigo-800 transition-all duration-700 text-white text-base font-semibold leading-7">update Data</button>
            </div>
    </form>
    </div>
</div>






<script>

// استدعاء نافذة اختيار الملف عند الضغط على الزر
document.getElementById('upload-button').addEventListener('click', function () {
    document.getElementById('movie-image').click();
});

// عرض اسم الملف عند اختياره
document.getElementById('movie-image').addEventListener('change', function () {
    const fileName = this.files[0] ? this.files[0].name : 'Choose an image';
    document.getElementById('file-name').textContent = fileName;
});





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
