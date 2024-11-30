@extends('admin.adminpanel')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<form action="{{ route('actor.update', $actor->id) }}" method="POST" enctype="multipart/form-data" style="width: 40%; position: relative; left: 30%; border: 1px solid black; border-radius: 10px; padding: 10px;">
    @csrf
    <img src="{{ asset('images/'.$actor->photo) }}" alt="" style="width:40%; height:150px; position:relative; border-radius:20px; left:30%; margin-bottom:10px;">

    <div class="relative mb-6">
        <div class="relative text-gray-500 focus-within:text-gray-900 mb-6">
            <input type="text" id="default-search" class="block w-full h-11 pr-5 pl-12 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none" placeholder="Enter Name" name="name" value="{{ $actor->name }}">
        </div>
    </div>

    <div class="relative mb-6">
        <div class="relative text-gray-500 focus-within:text-gray-900 mb-6">
            <input type="text" id="default-search" class="block w-full h-11 pr-5 pl-12 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none" placeholder="Enter country" name="country" value="{{ $actor->country }}">
        </div>
    </div>

    <div class="relative mb-6">
        <label for="birth_date">Birth Date </label>
        <input type="text" id="birth_date" name="birth_date" class="block w-full h-11 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('birth_date')is-invalid  @enderror" value="{{$actor->birth_date}}" style="margin:10px;">
        @error('birth_date')
          <div class="text-danger invalid-feedback">{{ $message }}</div>
          @enderror
    </div>
    <div class="relative mb-6">
        <label for="birth_date">Death Date </label>
        <input type="text" id="birth_date" name="death_date" class="block w-full h-11 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none" value="{{$actor->death_date}}" style="margin:10px;">
    </div>

    <div class="relative mb-6">
        <div class="relative text-gray-500 focus-within:text-gray-900 mb-6">
          <textarea id="actor-bio" class="block w-full h-20 pr-5 pl-4 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-400 focus:outline-nonex    " placeholder="Enter Biography" name="bio">{{$actor->biography}}</textarea>
        </div>
        </div>

    <div class="custom-file-upload" style="margin: 10px;">
        <span id="file-name">Choose an image</span>
        <button type="button" id="upload-button" class="btn btn-primary">Upload</button>
        <input type="file" id="actor-image" name="image" class="form-control" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name;">
    </div>

    <div class="flex items-center justify-center">
        <button type="submit" class="w-52 h-12 shadow-sm rounded-full bg-indigo-600 hover:bg-indigo-800 transition-all duration-700 text-white text-base font-semibold leading-7">Update Actor</button>
    </div>
</form>

<script>
    document.getElementById('upload-button').addEventListener('click', function () {
        document.getElementById('actor-image').click();
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
            flatpickr("#birth_date", {
            dateFormat: "Y-m-d", // تنسيق التاريخ
            maxDate: "today",   // تحديد اليوم الحالي كحد أقصى
            yearRange: [1900, new Date().getFullYear()] // نطاق السنوات
        });
    </script>
</script>
@endsection
