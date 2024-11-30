{{-- @extends('admin.adminpanel')
@section('content')

<style>
    .filter {
        margin-bottom: 20px;
        text-align: center;
    }

    .filter select {
        padding: 5px;
        font-size: 16px;
    }

    .user-reviews {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .user-card {
        width: 300px;
        background-color: #2c2c2c;
        border-radius: 10px;
        padding: 0px 15px;
        height: 300px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        overflow: scroll;
        scrollbar-width: none;

    }

    .user-info {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        position: sticky;
        top:0px;
        background: #2c2c2c;
        border-radius: 10px;
    }

    .user-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: fill;
    }

    .user-card h3 {
        font-size: 20px;
        color: #FFD700;
    }

    .reviews {
        margin-top: 15px;
    }

    .review-card {
        background-color: #1e1e1e;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);

    }

    .review-card h4 {
        color: #FFD700;
        font-size: 18px;
    }

    .review-card p {
        font-size: 14px;
        color: #ccc;
    }

    .likes {
        margin-top: 10px;
        font-size: 12px;
        color: #FFD700;
    }
</style>

<!-- فلترة حسب اسم المستخدم -->
<div class="filter">
    <form method="GET" action="{{ route('admin.reviews') }}">
        <label for="user_filter">فلتر حسب اسم المستخدم:</label>
        <select id="user_filter" name="user_id">
            <option value="">-- اختر مستخدم --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit">فلتر</button>
    </form>
</div>

<!-- عرض المراجعات لكل مستخدم -->
<div class="user-reviews">
    @foreach ($users as $user)
        <div class="user-card">
            <div class="user-info">
                <img src="{{ asset('images/'.$user->image) }}" alt="{{ $user->name }}" class="user-img">
                <h3>{{ $user->name }}</h3>
            </div>

            <!-- عرض المراجعات الخاصة بكل مستخدم -->
            <div class="reviews">
                @foreach ($user->reviews as $review)
                    <div class="review-card">
                        <h4>
                            @if ($review->reviewable_type=='App\Models\Movie')
                                <span>فيلم:</span> {{ $review->reviewable->name }}
                            @elseif ($review->series)
                                <span>مسلسل:</span> {{ $review->reviewable->name }}
                            @endif
                        </h4>
                        <p>{{ $review->content }}</p>
                        <div class="likes">
                            <span class="like-count">{{ $review->likes->count() }} إعجاب</span>
                        </div>
                       <a href="{{route('admin.delete.review',['id'=>$review->id])}}"> <button class="btn btn-danger">delete</button></a>
                    </div>
                @endforeach



            </div>
        </div>
    @endforeach
</div>



@endsection
 --}}
 @extends('admin.adminpanel')
 @section('content')

 <style>
     .filter {
         margin-bottom: 20px;
         text-align: center;
     }

     .filter input {
         padding: 5px;
         font-size: 16px;
         width: 300px;
         border: 1px solid #ccc;
         border-radius: 5px;
     }

     .user-reviews {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
         justify-content: center;
     }

     .user-card {
         width: 300px;
         background-color: #2c2c2c;
         border-radius: 10px;
         padding: 0px 15px;
         height: 300px;
         box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
         overflow: scroll;
         scrollbar-width: none;
     }

     .user-info {
         display: flex;
         justify-content: center;
         align-items: center;
         gap: 10px;
         margin-bottom: 20px;
         position: sticky;
         top: 10px;
         background: #2c2c2c;
         border-radius: 10px;
     }

     .user-img {
         width: 100px;
         height: 100px;
         border-radius: 50%;
         object-fit: fill;
     }

     .user-card h3 {
         font-size: 20px;
         color: #FFD700;
     }

     .reviews {
         margin-top: 15px;
     }

     .review-card {
         background-color: #1e1e1e;
         padding: 15px;
         margin-bottom: 15px;
         border-radius: 8px;
         box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
     }

     .review-card h4 {
         color: #FFD700;
         font-size: 18px;
     }

     .review-card p {
         font-size: 14px;
         color: #ccc;
     }

     .likes {
         margin-top: 10px;
         font-size: 12px;
         color: #FFD700;
     }
 </style>

 <!-- فلترة حسب اسم المستخدم -->
 <div class="filter">
     <input type="text" id="user_filter" placeholder="اكتب اسم المستخدم للفلترة...">
 </div>

 <!-- عرض المراجعات لكل مستخدم -->
 <div class="user-reviews" id="user_reviews">
     @foreach ($users as $user)
         <div class="user-card" data-username="{{ $user->name }}">
             <div class="user-info">
                 <img src="{{ asset('images/'.$user->image) }}" alt="{{ $user->name }}" class="user-img">
                 <h3>{{ $user->name }}</h3>
             </div>

             <!-- عرض المراجعات الخاصة بكل مستخدم -->
             <div class="reviews">
                 @foreach ($user->reviews as $review)
                     <div class="review-card">
                         <h4>
                             @if ($review->reviewable_type=='App\Models\Movie')
                                 <span>فيلم:</span> {{ $review->reviewable->name }}
                             @elseif ($review->reviewable_type='\App\Models\Series')
                                 <span>مسلسل:</span> {{ $review->reviewable->name }}
                             @endif
                         </h4>
                         <p>{{ $review->content }}</p>
                         <div class="likes">
                             <span class="like-count">{{ $review->likes->count() }} إعجاب</span>
                         </div>
                         <a href="{{route('admin.delete.review',['id'=>$review->id])}}">
                             <button class="btn btn-danger">حذف</button>
                         </a>
                     </div>
                 @endforeach
             </div>
         </div>
     @endforeach
 </div>

 <script>
     document.getElementById('user_filter').addEventListener('input', function () {
         const filterValue = this.value.toLowerCase();
         const userCards = document.querySelectorAll('.user-card');

         userCards.forEach(card => {
             const username = card.getAttribute('data-username').toLowerCase();
             if (username.includes(filterValue)) {
                 card.style.display = 'block';
             } else {
                 card.style.display = 'none';
             }
         });
     });
 </script>

 @endsection
