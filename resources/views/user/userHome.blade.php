@extends('user.userPanel')
@section('content')

<style>
    /* تصميم الحاوية الرئيسية */
    .container {
        height: 100%;
        display: flex;
        justify-content: space-between;
        padding: 20px;
        color: white;
        gap: 20px;
        flex-wrap: wrap; /* ليظهر المحتوى بشكل جيد على الأجهزة الصغيرة */
    }

    /* التصميم الخاص بكل قسم */
    .section {
        width: 48%;
        background-color: transparent;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        padding: 15px;
    }

    /* عنوان كل قسم */
    .section h2 {
        text-align: center;
        color: #FFD700;
        font-size: 24px;
        margin-bottom: 20px;
    }

    /* تصميم البطاقات */
    .item-card {
        background-color: #1e1e1e;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        padding: 20px;
        transition: transform 0.3s ease-in-out;
    }

    .item-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    /* صورة الفيلم أو المسلسل */
    .item-card img {
        width: 100%;
        height: 250px;
        object-fit: fill;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    /* اسم الفيلم أو المسلسل */
    .item-card h4 {
        font-size: 20px;
        color: #fff;
        margin-bottom: 10px;
    }

    /* تقييم الفيلم أو المسلسل */
    .item-card .rating {
        font-size: 16px;
        color: #FFD700;
        margin-bottom: 10px;
    }

    /* الوصف */
    .item-card p {
        font-size: 14px;
        color: #ccc;
        margin-bottom: 15px;
        height: 100px;
        overflow: scroll;
        scrollbar-width: none;
        text-overflow: ellipsis;
    }

    /* المراجعات */
    /* تصميم المراجعات */
.reviews {
    background-color: #2a2a2a;
    padding: 10px;
    border-radius: 10px;
    margin-top: 10px;
    max-height: 250px; /* تحديد أقصى ارتفاع */
    overflow: scroll; /* إخفاء شريط التمرير */
    scrollbar-width: none;
}

/* تصميم كل مراجعة */
.review {
    margin-bottom: 10px;
    padding: 8px; /* تقليص المسافة داخل المراجعة */
    border-bottom: 1px dashed #444;
    font-size: 13px; /* تقليل حجم الخط */
    line-height: 1.3; /* تحسين المسافات بين الأسطر */
    height: auto; /* إزالة ارتفاع ثابت ليتناسب مع المحتوى */
}

/* تصميم زر الإعجاب */
.like-button {
    background-color: #FF6F61;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 4px 10px; /* تقليص الحجم */
    cursor: pointer;
    font-size: 12px; /* تقليل حجم الخط */
    transition: background-color 0.3s ease, transform 0.2s;
    margin-top: 5px;
}

.like-button:hover {
    background-color: #ff3f29;
    transform: scale(1.1);
}

.like-button:active {
    transform: scale(0.95);
}

/* تصميم عدد الإعجابات */
.like-count {
    font-size: 12px;
    color: #FFD700;
    font-weight: bold;
}
</style>

<div class="container">
    <!-- قسم الأفلام -->
    <div class="section">
        <h2>Movies Top Rated</h2>
        @foreach($topRatedMovies as $index => $movie)
    <div class="item-card">
        <img src="{{ asset('images/'.$movie->image) }}" alt="{{ $movie->name }}">
        <h4>{{ $movie->name }}</h4>
        <div class="rating">⭐ {{ number_format($movie->reviews->avg('rating'), 1) }}</div>
        <p>{{ $movie->description }}</p>

        <!-- عرض المراجعات فقط إذا كانت موجودة -->
        @if(isset($topRatedMoviesReviews[$index]) && $topRatedMoviesReviews[$index]->isNotEmpty())
            <div class="reviews">
                @foreach ($topRatedMoviesReviews[$index] as $review)
                    <div class="review">
                        <strong>{{ $review->user->name }}:</strong>
                        <p>{{ $review->content }}</p>
                        <p style="display: inline">❤️ (<span class="like-count">{{ $review->likes_count }}</span>)</p>
                        <button class="like-button" data-review-id="{{ $review->id }}">
                            ❤️ اضغط للإعجاب
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p>لا توجد مراجعات بعد.</p>
        @endif
    </div>
@endforeach


    </div>

    <!-- قسم المسلسلات -->
    <div class="section">
        <h2>Series Top Rated</h2>@foreach($topRatedSeries as $index => $series)
        <div class="item-card">
            <img src="{{ asset('images/'.$series->image) }}" alt="{{ $series->name }}">
            <h4>{{ $series->name }}</h4>
            <div class="rating">⭐ {{ number_format($series->reviews->avg('rating'), 1) }}</div>
            <p>{{ $series->description }}</p>

            <!-- عرض المراجعات فقط إذا كانت موجودة -->
            @if(isset($topRatedSeriesReviews[$index]) && $topRatedSeriesReviews[$index]->isNotEmpty())
                <div class="reviews">
                    @foreach ($topRatedSeriesReviews[$index] as $review)
                        <div class="review">
                            <strong>{{ $review->user->name }}:</strong>
                            <p>{{ $review->content }}</p>
                            <p style="display: inline">❤️ (<span class="like-count">{{ $review->likes_count }}</span>)</p>
                            <button class="like-button" data-review-id="{{ $review->id }}">
                                ❤️ اضغط للإعجاب
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p>لا توجد مراجعات بعد.</p>
            @endif
        </div>
    @endforeach

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const likeButtons = document.querySelectorAll('.like-button');

        likeButtons.forEach(button => {
            button.addEventListener('click', function () {
                const reviewId = this.getAttribute('data-review-id');
                const likeCountElement = this.previousElementSibling.querySelector('.like-count');

                // إرسال طلب AJAX للإعجاب بالتعليق
                fetch('/like-review', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // تأكد من وجود CSRF
                    },
                    body: JSON.stringify({ review_id: reviewId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        likeCountElement.textContent = data.new_like_count; // تحديث عدد الإعجابات
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
            });
        });
    });
</script>

@endsection
