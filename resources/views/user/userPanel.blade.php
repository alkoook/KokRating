
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <!-- Bootstrap CSS -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">


<style>
        /* تفعيل الانتقال */
        .d-flex{
            background:linear-gradient(to bottom,rgb(23 23 23),rgb(128 125 123),rgb(23 23 23));
            height: 100%;
        }
        #sidebar {
           position: relative;
            width: 60px; /* العرض الابتدائي */
            height: 100%;
            background:transparent;
            transition: width 0.5s ease, background-color 0.5s ease; /* الانتقال عند تغيير العرض واللون */
            border-radius: 0px 10px 10px 0px;
        }

        #sidebar:hover {
            width: 180px; /* العرض عند التمرير */
            background-image:url('background.jpg');
            background-size: cover;
            background-position: center;

        }

        #sidebar ul {
            list-style: none;
            padding: 10px;
            margin: 0;

        }

        #sidebar ul li {
            padding: 5px;
            display: flex;
            align-items: center;
            white-space:nowrap;
            margin: 20px 0;
            overflow-x: scroll;
            scrollbar-width: none;
            width: 100%;
    }
    #sidebar ul li .rev{
        transition: 10s;
    }
    #sidebar ul li:hover .rev {
        animation: moveText 5s linear infinite; /* تعيين الحركة */

    }


    /* تعيين الحركة باستخدام keyframes */
    @keyframes moveText {
        0% {
            transform: translateX(-100%); /* يبدأ من خارج الشاشة من الجهة اليسرى */
        }
        50% {
            transform: translateX(0%); /* ينتهي عند الخروج من الجهة اليمنى */
        }
        100% {
            transform: translateX(100%); /* ينتهي عند الخروج من الجهة اليمنى */
        }
    }

        #sidebar ul li:hover {
            background: linear-gradient(to bottom,rgb(223 223 223),rgb(22 22 22),rgb(230 230 230));
            border-radius: 10px;
            width:100%;
            left:0px;

        }
        #sidebar ul li .icon {
            min-width: 40px;
            text-align: center;
        }

        #sidebar ul li a {
            color: rgb(15, 1, 1);
            display: flex;
            align-items: center;
            justify-content: center;
        }


        #sidebar ul li a span {
            opacity: 0; /* إخفاء النصوص في الوضع العادي */
            transition: opacity 0.5s ease; /* الانتقال عند تغيير الشفافية */

        }

        #sidebar ul li form button span {
            opacity: 0; /* إخفاء النصوص في الوضع العادي */
            transition: opacity 0.5s ease; /* الانتقال عند تغيير الشفافية */
        }

        #sidebar:hover ul li form button span {
            opacity: 1; /* إظهار النصوص عند التمرير */
        }
        #sidebar:hover ul li a span {
            opacity: 1; /* إظهار النصوص عند التمرير */
        }


        .flex-grow-1 {

            margin-right: 0px; /* المسافة الابتدائية للمحتوى */
            transition: margin-left 0.5s ease; /* الانتقال عند تغيير المسافة */
        }

        #sidebar:hover ~ .flex-grow-1 {
            margin-left: 0px; /* المسافة عند التمرير */
        }


</style>


</head>
<body>
    <div class="d-flex">


        <!-- Sidebar -->
        <div class=" text-white" id="sidebar">
            <h5 class="text-center py-3">User Panel</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{route('user.home')}}" class="nav-link text-white px-3">
                        <i class="fas fa-home">
                        <span> Home</span>
                        </i>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('user.showMovie')}}" class="nav-link text-white px-3">
                        <i class="fas fa-film"><span> Movies</span></i>

                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('user.showSeries')}}" class="nav-link text-white px-3">
                        <i class="fas fa-tv"> <span> Series</span></i>

                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('user.show.movie.review')}}" class="nav-link text-white px-3 rev">
                        <i class="far fa-address-card"> <span> Reviews for Movie</span></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('user.show.series.review')}}" class="nav-link text-white px-3 rev">
                        <i class="far fa-address-card"> <span> Reviews for Series</span></i>
                    </a>
                </li>

                    <li class="nav-item">
                       <a href="{{route('user.show.actors')}}" class="nav-link text-white px-3">
                           <i class='fas fa-user-alt'> <span> Actors</span></i>
                       </a>
                   </li>

                   <li class="nav-item">
                    <form action="{{ route('profile.edit') }}" method="get" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link text-white px-3" style="">
                            <i class="fa-regular fa-circle-user"> <span>Profile</span></i>
                        </button>
                    </form>
                </li>

                   <li class="nav-item">
                       <form action="{{ route('user.logout') }}" method="POST" style="display: inline;">
                           @csrf
                           <button type="submit" class="nav-link text-white px-3" style="background: none; border: none;">
                               <i class="fa-solid fa-right-from-bracket"> <span>Log Out</span></i>
                           </button>
                       </form>
                   </li>

            </ul>
        </div>
   <!-- Main Content -->
   <div class="flex-grow-1 p-4">
    @yield('content')
 </div>

    </div>







    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

<script>           // إنشاء السنوات ديناميكيًا
   const currentYear = new Date().getFullYear(); // السنة الحالية
   const startYear = 1900; // بداية السنوات
   const yearSelect = document.getElementById('year-select');

   // إنشاء الخيارات
   for (let year = currentYear; year >= startYear; year--) {
       const option = document.createElement('option');
       option.value = year;
       option.textContent = year;
       yearSelect.appendChild(option);
   }

   </script>
</body>
</html>
