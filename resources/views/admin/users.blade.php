@extends('admin.adminpanel')

@section('content')

<link rel="stylesheet" href="{{ asset('css_for_admin/users.css') }}">

<div class="container">



    <div class="users-section-container">
        <!-- البحث داخل قسم المستخدمين -->
        <div class="search-bar">
            <input type="text" id="user-search-input" class="search-input" placeholder="Search for a user email..." oninput="filterUsers()">
        </div>

        @foreach($users as $user)
        <div class="user-card" data-name="{{ strtolower($user->email) }}" onclick="showDetails('details-user-{{ $user->id }}')">
            <img src="{{ asset('images/'.$user->image) }}" alt="{{ $user->name }}" onclick="showDetails('details-user-{{ $user->id }}',event)">
            <div class="card-header">
                <p  onclick="showDetails('details-user-{{ $user->id }}',event)">{{ $user->email }}</p>
            </div>
        </div>

            <!-- تفاصيل المستخدم -->
            <div id="details-user-{{ $user->id }}" class="details-popup">
                <button class="close-btn" onclick="closeDetails('details-user-{{ $user->id }}')">X</button>
                <img src="{{asset('images/'.$user->image)}}" alt="{{$user->name}}">
                <h3>{{ $user->name }}</h3>
                <p>Email: {{ $user->email }}</p>
                <p>phone: {{ $user->phone }}</p>
                <a href="{{route('admin.user.edit',$user->id)}}">  <button class="edit-btn text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"> Edit</button></a>
                <form method="POST" action="{{ route('admin.user.delete', $user->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Delete</button>
                </form>
            </div>
        @endforeach
    </div>


     <!-- قسم الفورم: لإضافة مستخدم جديد -->
     <form action="{{route('admin.users.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="relative mb-6">
        <label class="flex  items-center mb-2 text-gray-600 text-sm font-medium">Name <svg width="7" height="7" class="ml-1" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3.11222 6.04545L3.20668 3.94744L1.43679 5.08594L0.894886 4.14134L2.77415 3.18182L0.894886 2.2223L1.43679 1.2777L3.20668 2.41619L3.11222 0.318182H4.19105L4.09659 2.41619L5.86648 1.2777L6.40838 2.2223L4.52912 3.18182L6.40838 4.14134L5.86648 5.08594L4.09659 3.94744L4.19105 6.04545H3.11222Z" fill="#EF4444" />
          </svg>
        </label>
        <div class="relative  text-gray-500 focus-within:text-gray-900 mb-6">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
            <svg class="stroke-current ml-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 21V20.1429C20 19.0805 20 18.5493 19.8997 18.1099C19.5578 16.6119 18.3881 15.4422 16.8901 15.1003C16.4507 15 15.9195 15 14.8571 15H10C8.13623 15 7.20435 15 6.46927 15.3045C5.48915 15.7105 4.71046 16.4892 4.30448 17.4693C4 18.2044 4 19.1362 4 21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="" stroke-width="1.6" stroke-linecap="round" />
            </svg>
          </div>
          <input type="text" id="default-search" class="block w-full h-11 pr-5 pl-12 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('name')is-invalid  @enderror" placeholder="Enter Name" name="name">
          @error('name')
          <div class="text-danger invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        </div>
        <div class="relative mb-6">
        <label class="flex  items-center mb-2 text-gray-600 text-sm font-medium">Email <svg width="7" height="7" class="ml-1" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3.11222 6.04545L3.20668 3.94744L1.43679 5.08594L0.894886 4.14134L2.77415 3.18182L0.894886 2.2223L1.43679 1.2777L3.20668 2.41619L3.11222 0.318182H4.19105L4.09659 2.41619L5.86648 1.2777L6.40838 2.2223L4.52912 3.18182L6.40838 4.14134L5.86648 5.08594L4.09659 3.94744L4.19105 6.04545H3.11222Z" fill="#EF4444" />
          </svg>
        </label>
        <div class="relative  text-gray-500 focus-within:text-gray-900 mb-6">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
            <svg class="stroke-current ml-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3.54887 6.73325L7.76737 9.36216C9.82591 10.645 10.8552 11.2864 11.9999 11.2863C13.1446 11.2861 14.1737 10.6443 16.2318 9.36081L20.4611 6.72333M11 20H13C16.7712 20 18.6569 20 19.8284 18.8284C21 17.6569 21 15.7712 21 12C21 8.22876 21 6.34315 19.8284 5.17157C18.6569 4 16.7712 4 13 4H11C7.22876 4 5.34315 4 4.17157 5.17157C3 6.34315 3 8.22876 3 12C3 15.7712 3 17.6569 4.17157 18.8284C5.34315 20 7.22876 20 11 20Z" stroke="" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <input type="text" id="default-search" class="block w-full h-11 pr-5 pl-12 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('email')is-invalid  @enderror" placeholder="Enter Email" name='email'>
          @error('email')
          <div class="text-danger invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        </div>

        <div class="relative mb-2">
            <label class="flex  items-center mb-2 text-gray-600 text-sm font-medium">Password <svg width="7" height="7" class="ml-1" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.11222 6.04545L3.20668 3.94744L1.43679 5.08594L0.894886 4.14134L2.77415 3.18182L0.894886 2.2223L1.43679 1.2777L3.20668 2.41619L3.11222 0.318182H4.19105L4.09659 2.41619L5.86648 1.2777L6.40838 2.2223L4.52912 3.18182L6.40838 4.14134L5.86648 5.08594L4.09659 3.94744L4.19105 6.04545H3.11222Z" fill="#EF4444" />
              </svg>
            </label>
            <div class="relative  text-gray-500 focus-within:text-gray-900 mb-6">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
                <svg class="stroke-current ml-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M17 10H8M17 10V10C17.93 10 18.395 10 18.7765 10.1022C19.8117 10.3796 20.6204 11.1883 20.8978 12.2235C21 12.605 21 13.07 21 14C21 14.6667 21 15.3333 21 16C21 18.8284 21 20.2426 20.1213 21.1213C19.2426 22 17.8284 22 15 22C13.3333 22 11.6667 22 10 22C7.17157 22 5.75736 22 4.87868 21.1213C4 20.2426 4 18.8284 4 16C4 15.3333 4 14.6667 4 14C4 13.07 4 12.605 4.10222 12.2235C4.37962 11.1883 5.18827 10.3796 6.22354 10.1022C6.60504 10 7.07003 10 8 10V10M17 10V6.5C17 4.01472 14.9853 2 12.5 2C10.0147 2 8 4.01472 8 6.5V10M15 15.5C15 16.8807 13.8807 18 12.5 18C11.1193 18 10 16.8807 10 15.5C10 14.1193 11.1193 13 12.5 13C13.8807 13 15 14.1193 15 15.5Z" stroke="" stroke-width="1.5" />
                </svg>
              </div>
              <input type="password" id="default-search" class="block w-full h-11 pr-5 pl-12 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('password')is-invalid  @enderror" placeholder="Enter Password" name="password">
              @error('password')
          <div class="text-danger invalid-feedback">{{ $message }}</div>
          @enderror
            </div>
            </div>

        <div class="relative mb-6">
        <label class="flex  items-center mb-2 text-gray-600 text-sm font-medium">Phone Number <svg width="7" height="7" class="ml-1" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3.11222 6.04545L3.20668 3.94744L1.43679 5.08594L0.894886 4.14134L2.77415 3.18182L0.894886 2.2223L1.43679 1.2777L3.20668 2.41619L3.11222 0.318182H4.19105L4.09659 2.41619L5.86648 1.2777L6.40838 2.2223L4.52912 3.18182L6.40838 4.14134L5.86648 5.08594L4.09659 3.94744L4.19105 6.04545H3.11222Z" fill="#EF4444" />
          </svg>
        </label>
        <div class="relative  text-gray-500 focus-within:text-gray-900 mb-6">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
            <svg class="stroke-current ml-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.02623 10.2611L12.7387 17.9736C14.4091 19.6439 17.1173 19.6439 18.7877 17.9736C19.4559 17.3054 19.4559 16.2221 18.7877 15.554L16.6454 13.4116C16.1582 12.9244 15.3683 12.9244 14.8811 13.4116C14.3939 13.8988 13.604 13.8988 13.1168 13.4116L9.23534 9.53015C8.74814 9.04295 8.74814 8.25305 9.23534 7.76585C9.72253 7.27865 9.72253 6.48875 9.23534 6.00155L7.44584 4.21205C6.77768 3.5439 5.69439 3.5439 5.02623 4.21205C3.35584 5.88244 3.35584 8.59067 5.02623 10.2611Z" stroke="" stroke-width="1.6" />
            </svg>
          </div>
          <input type="text" id="default-search" class="block w-full h-11 pr-5 pl-12 py-2.5 text-base font-normal shadow-xs text-gray-900 bg-transparent border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none @error('phone')is-invalid  @enderror" placeholder="Enter Phone No" name="phone">
          @error('phone')
          <div class="text-danger invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        </div>
        <div class="custom-file-upload" style="margin: 10px;">
            <span id="file-name">Choose an image</span>
            <button type="button" id="upload-button" class="btn btn-primary">Upload</button>
            <input type="file" id="user-image" name="image" class="form-control @error('image')is-invalid @enderror" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name;">
            @error('image')
                <div class="text-danger invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center justify-center">
        <button type="submit" class="w-52 h-12 shadow-sm rounded-full bg-indigo-600 hover:bg-indigo-800 transition-all duration-700 text-white text-base font-semibold leading-7">Create Account</button>
        </div>
    </form>

</div>

<script>

document.getElementById('upload-button').addEventListener('click', function () {
    document.getElementById('user-image').click();
});

// عرض اسم الملف عند اختياره
document.getElementById('user-image').addEventListener('change', function () {
    const fileName = this.files[0] ? this.files[0].name : 'Choose an image';
    document.getElementById('file-name').textContent = fileName;
});
        // دالة الفلترة
function filterUsers() {
    const searchInput = document.getElementById("user-search-input").value.toLowerCase();
    const userCards = document.querySelectorAll(".user-card");

    userCards.forEach(card => {
        const userEmail = card.getAttribute("data-name");
        if (userEmail.includes(searchInput)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}

// عرض التفاصيل عند الضغط على الكارد
function showDetails(userId, event) {
    event.stopPropagation();  // منع التفاعل المزدوج عند الضغط على الصورة
    var detailsElement = document.getElementById(userId);
    if (detailsElement) {
        if (detailsElement.style.display === 'none' || detailsElement.style.display === '') {
            detailsElement.style.display = 'block';
        } else {
            detailsElement.style.display = 'none';
        }
    }
}



// إخفاء التفاصيل عند الضغط على زر الإغلاق
function closeDetails(detailId) {
    const detail = document.getElementById(detailId);
    detail.style.display = "none";
}

// إخفاء التفاصيل عند الضغط في مكان آخر
window.onclick = function(event) {
    const details = document.querySelectorAll('.details-popup');
    details.forEach(function(detail) {
        if (!detail.contains(event.target) && !event.target.matches('.user-card')) {
            detail.style.display = "none";
        }
    });
}

</script>



@endsection
