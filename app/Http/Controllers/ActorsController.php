<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActorsController extends Controller
{
    public function index() {
        $actors = Actor::with(['series', 'movies'])->get()->map(function ($actor) {
            if ($actor->death_date) {
                $actor->age = intval(Carbon::parse($actor->birth_date)->diffInYears(Carbon::parse($actor->death_date)));
            } else {
                $actor->age = intval(Carbon::parse($actor->birth_date)->diffInYears(Carbon::now()));
            }

            return $actor;
        });

        return view('admin.actors', compact('actors'));
    }

public function store(Request $request){

        // التحقق من المدخلات
      $validated = $request->validate([
        'name' => 'required',
        'country' => 'required',
        'birth_date' => 'required|date',
        'death_date' => 'nullable|date',
        'bio' => 'nullable',
        'image' => 'required|mimes:png,jpg,jpeg', // التحقق من أن الملف صورة
    ], [
        'name.required' => 'Enter Name',
        'country.required' => 'Enter Country',
        'birth_date.required' => 'Enter Birth Date for Actor',
        'image.required' => 'Enter Image',
        'photo.image' => 'The file must be an image.',
    ]);
    // تحقق من وجود الملف
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // لتفادي تكرار الأسماء
        $imagePath = $image->storeAs('actor_images', $imageName, 'kok'); // تخزين الصورة في الدليل العام
    } else {
        return redirect()->back()->withErrors(['image' => 'Image upload failed']);
    }

    // إنشاء الممثل
    Actor::create([
        'name' => $validated['name'],
        'country' => $validated['country'],
        'birth_date'=>$validated['birth_date'],
        'death_date'=>$validated['death_date'],
        'photo' => $imagePath ,// تخزين مسار الصورة
        'biography'=>$validated['bio']
    ]);

    return redirect()->back()->with('success', 'Actor created successfully');
}
public function destroy($id){
    $actor=Actor::findOrFail($id);
    Storage::disk('kok')->delete($actor->photo);
    $actor->delete();
    return redirect()->back();
}
public function edit($id){
    $actor=Actor::findOrFail($id);
    return view('admin.actor_edit',compact('actor'));
}
public function update(Request $request,$id){
        $validated=$request->validate([
            'name'=>'required',
            'country'=>'required',
            'birth_date'=>'required|date',
            'death_date' => 'nullable|date',
            'bio'=>'nullable',
            'image'=>'nullable|image'
        ],);

        $actor=Actor::findOrFail($id);
        if($request->hasFile('image'))
        {
            Storage::disk('kok')->delete($actor->photo);
            $imageName=time().'_'.$request->file('image')->getClientOriginalName();
            $imagePath=$request->file('image')->storeAs('actor_images',$imageName,'kok');
             $actor->photo=$imagePath;

        }

        $actor->name=$validated['name'];
        $actor->country=$validated['country'];
        $actor->birth_date=$validated['birth_date'];
        $actor->death_date=$validated['death_date'];
        $actor->biography=$validated['bio'];
        $actor->save();
        return redirect()->route('actor.index');
}
public function showActor(){
    $actors = Actor::all();
    return view('user.showActor', compact('actors'));
}

public function getActorDetails($id)
{
    // تأكد من أن الموديل يحتوي على علاقة مع Movies و Series
    $actor = Actor::with(['Movies', 'Series'])->findOrFail($id);

    return response()->json([
        'name' => $actor->name,
        'photo' => asset('images/' . $actor->photo),  // إرسال الرابط الكامل للصورة
        'movies' => $actor->movies->pluck('name'),   // تأكد من اسم العلاقة "movies"
        'series' => $actor->series->pluck('name')    // تأكد من اسم العلاقة "series"
    ]);
}
public function actorInformation($id){
    $actor=Actor::findOrFail($id);
    return view('user.actorInformation',compact('actor'));
}

}