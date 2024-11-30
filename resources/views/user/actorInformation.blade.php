@extends('user.userPanel')
@section('content')

<style>
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: transparent; /* لون خلفية داكن */
        color: white; /* نص أبيض */
        padding: 20px;
    }

    .main-box {
        width: 70%;
        background-color: #1e1e1e; /* لون صندوق غامق */
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        padding: 20px;
        text-align: center;
    }

    .image-container {
        width:100%;
        margin-bottom: 20px;
    }

    .work-image {
        max-width: 100%;
        width:100%;
        height: 500px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
    }

    .year {
        font-size: 18px;
        color: #ccc;
    }

    .rating-country {
        display: flex;
        justify-content: space-around;
        margin: 10px 0;
        font-size: 16px;
    }

    .details {
        margin-bottom: 20px;
        font-size: 16px;
        color: #ddd;
    }

    .description-box {
        background-color: #2a2a2a;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        max-height: 150px;
        overflow:scroll;scrollbar-width:none;
        text-align: center;
    }

    .description {
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        font-size: 14px;
        line-height: 1.6;
    }

    .categories, .actors {
        margin-top: 20px;
        text-align: left;
    }

    .categories ul, .actors ul {
        list-style: none;
        padding: 0;
    }

    .categories li, .actors li {
        margin: 5px 0;
        font-size: 14px;
        color: #ddd;
    }

    .p {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

</style>

<div class="container">
    <div class="main-box">
        <!-- صورة الممثل -->
        <div class="image-container">
            <img src="{{ asset('images/'.$actor->photo) }}" alt="{{ $actor->name }}" class="work-image">
        </div>
        <!-- اسم الممثل -->
        <h2 class="title">
            {{ $actor->name }}
        </h2>
        <!-- تاريخ الميلاد والبلد -->
        <div class="rating-country">
            <span class="birth-date">Birth Date: {{ $actor->birth_date }}</span>
            <span class="country">Country: {{ $actor->country }}</span>
        </div>
        <!-- العمر الحالي -->
        <div class="details">
            <span>Age: {{ \Carbon\Carbon::parse($actor->birth_date)->age }} years</span>
        </div>
        <!-- السيرة الذاتية -->
        <div class="description-box">
            <p class="description" style="">{{ $actor->biography }}</p>
        </div>
        <!-- قائمة الأفلام والمسلسلات -->
        <div class="p">
            <!-- قائمة الأفلام -->
            <div class="categories">
                <strong>Movies:</strong>
                <ul>
                    @foreach($actor->movies as $movie)
                       <a href="{{route('user.Movie_Series',['id'=>$movie->id,'type'=>'movie'])}}"> <li>{{ $movie->name }}</li></a>
                    @endforeach
                </ul>
            </div>
            <!-- قائمة المسلسلات -->
            <div class="actors">
                <strong>Series:</strong>
                <ul>
                    @foreach($actor->series as $series)
                       <a href="{{route('user.Movie_Series',['id'=>$series->id,'type'=>'series'])}}"> <li>{{ $series->name }}</li></a>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
