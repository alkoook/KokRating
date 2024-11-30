@extends('user.userPanel')

@section('content')



<style>

.flex img{
    width:300px;height:300px;border-radius:20px;margin-bottom: 20px;
}
</style>

<div class="container mx-auto py-10" >
    <!-- خانة البحث -->
    <div class="mb-4">
        <input
            type="text"
            id="searchActor"
            placeholder="بحث عن ممثل..."
            class="px-4 py-2 border rounded-lg w-full"
            style="top:-40px; position:relative" />
    </div>

    <!-- Grid الكروت -->
    <div id="actorGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($actors as $actor)
        <div
            id="actor-{{ $actor->id }}"
            class="bg-white shadow-lg rounded-lg overflow-hidden transform transition duration-500 hover:scale-105 cursor-pointer actor-card"
            onclick="toggleActorDetails({{ $actor->id }})">
            <!-- صورة الممثل -->
            <img src="{{ asset('images/' . $actor->photo) }}" alt="{{ $actor->name }}" class="w-full h-48 object-fill">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ $actor->name }}</h2>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- تفاصيل الممثل -->
@foreach ($actors as $actor)
<div id="actorDetails-{{ $actor->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="top:10px;">
    <div class="bg-white rounded-lg max-w-4xl w-full p-6 shadow-lg relative" onclick="event.stopPropagation()">
        <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-800" onclick="toggleActorDetails({{ $actor->id }})">X</button>

        <div class="flex flex-col md:flex-row gap-6" id="kok">
            <!-- القسم الأيسر -->
            <div class="md:w-1/2 flex flex-col items-center text-center md:text-left">
                <h2 class="text-2xl font-bold mb-4">{{ $actor->name }}</h2>
                <img src="{{ asset('images/' . $actor->photo) }}"
                     alt="{{ $actor->name }}">                <p class="text-gray-600 border p-3 rounded-lg h-32 overflow-auto"
                   style="scrollbar-width: none;">
                    {{ $actor->biography }}
                </p>
            </div>

            <!-- القسم الأيمن -->
            <div class="md:w-1/2" style="overflow: hidden">
                <div class="mb-6">
                    <div class="mov" style="
                    border-radius:20px;
                    border:1px dotted black;
                    padding:10px;
                    position: relative;
                    top:0px;
                    max-height:200px;
                    overflow:scroll;
                    scrollbar-width:none;
                    ">
                    <h3 class="text-xl font-semibold mb-2" style="width:100%;background:black ;color:white;border-radius:10px;text-align:center;">Movies</h3>
                    <ul class="list-disc list-inside">
                        @foreach ($actor->movies as $movie)
                        <a href="{{route('user.Movie_Series',['id'=>$movie->id,'type'=>'movie'])}}" style="font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif"><li>{{ $movie->name }}</li></a>
                        @endforeach
                    </ul>
                </div>
            </div>
                <div>
                    <div class="ser" style="
                    border-radius:20px;
                    border:1px dotted black;
                    padding:0px 10px;
                    position: relative;
                    top:0px;
                    max-height:200px;
                    overflow:scroll;
                    scrollbar-width:none;
                    ">
                    <h3 class="text-xl font-semibold mb-2" style="width:100%;background:black ;color:white;border-radius:10px;text-align:center;">Series</h3>
                        <ul class="list-disc list-inside">
                            @foreach ($actor->series as $series)
                                <a href="{{route('user.Movie_Series',['id'=>$series->id,'type'=>'series'])}}" style="font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif"><li>{{ $series->name }}</li></a>
                            @endforeach
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endforeach

<script>
// دالة لتبديل ظهور تفاصيل الممثل عند الضغط
function toggleActorDetails(actorId) {
    const detailsElement = document.getElementById(`actorDetails-${actorId}`);

    if (detailsElement.classList.contains('hidden')) {
        detailsElement.classList.remove('hidden');
    } else {
        detailsElement.classList.add('hidden');
    }
}

// البحث عن الممثلين
document.getElementById('searchActor').addEventListener('input', function () {
    const searchQuery = this.value.toLowerCase();
    const actorCards = document.querySelectorAll('.actor-card');

    actorCards.forEach(card => {
        const actorName = card.querySelector('h2').textContent.toLowerCase();
        if (actorName.includes(searchQuery)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});

// إخفاء التفاصيل عند الضغط خارج التفاصيل
document.addEventListener('click', function (event) {
    const detailsWrapper = document.querySelectorAll('.fixed');

    // إغلاق التفاصيل فقط إذا كان الضغط خارج تفاصيل الممثل
    detailsWrapper.forEach(details => {
        if (!details.contains(event.target) && !event.target.closest('.actor-card')) {
            details.classList.add('hidden');
        }
    });
});
</script>
@endsection
