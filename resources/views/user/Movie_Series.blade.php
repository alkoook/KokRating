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
    overflow: scroll;
    text-align: center;
    scrollbar-width: none
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
.p{
    display: grid;
    grid-template-columns: 1fr 1fr;

}

</style>

<div class="container">
    <div class="main-box">
        <!-- صورة العمل -->
        <div class="image-container">
            <img src="{{ asset('images/'.$item->image) }}" alt="{{ $item->name }}" class="work-image">
        </div>
        <!-- اسم العمل والسنة -->
        <h2 class="title">
            {{ $item->name }} <span class="year">({{ $item->year }})</span>
        </h2>
        <!-- التقييم والبلد -->
        <div class="rating-country">
            <span class="rating">Rating: {{ $item->reviews->avg('rating') }} ⭐</span>
            <span class="country">Country: {{ $item->country }}</span>
        </div>
        <!-- عدد الحلقات أو المدة -->
        <div class="details">
            @if($type == 'movie')
                <span>Duration: {{ $item->duration }} minutes</span>
            @elseif($type == 'series')
                <span>Episodes: {{ $item->episode }}</span>
            @endif
        </div>
        <!-- الوصف -->
        <div class="description-box">
            <p class="description">{{ $item->description }}</p>
        </div>
        <!-- التصنيف -->
        <div class="p">
        <div class="categories">
            <strong>Categories:</strong>
            <ul>
                @foreach($item->categories as $category)
                    <li>{{ $category->name }}</li>
                @endforeach
            </ul>
        </div>
        <!-- الممثلون -->
        <div class="actors">
            <strong>Actors:</strong>
            <ul>
                @foreach($item->actors as $actor)
                <a href="{{route('user.actorInformation',['id'=>$actor->id])}}"><li>{{ $actor->name }}</li></a>
                @endforeach
            </ul>
        </div>
    </div>
    </div>
</div>
@endsection
