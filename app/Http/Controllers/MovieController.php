<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Category;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $movies = Movie::with('actors')->paginate(20);
        $actors=Actor::all();

        return view('admin.show_all_movies',compact('movies','categories','actors'));
    }

    public function store(Request $request)
    {
        $validatedData= $request->validate([
                'name' => 'required',
                'description' => 'required',
                'duration' => 'required|integer|min:45|max:240',
                'year' => 'required|integer',
                'country' => 'required',
                'categories'=>'required|array',
                'actor'=>'required|array',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048',
            ]);
            $imageName=$request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('\movie_images',$imageName,'kok');

        $movie= Movie::create([
                'name' => $validatedData['name'],
                'description' =>$validatedData['description'],
                'duration' =>$validatedData['duration'],
                'year' => $validatedData['year'],
                'country' =>$validatedData['country'],
                'image' => $imagePath,
            ]);

            if ($request->has('categories')) {
                $movie->categories()->attach($validatedData['categories']);
            }


              $movie->actors()->sync($request->actor);



            return redirect()->back()->with('success', 'تمت إضافة الفيلم بنجاح!');
    }
    public function destroy($id)
    {
        // العثور على الفيلم
        $movie = Movie::findOrFail($id);

        // حذف الصورة (إذا كانت موجودة)
            Storage::disk('kok')->delete('movie_images/'.$movie->image);


        // حذف الفئات المرتبطة بالفيلم في جدول categoryables
        $movie->categories()->detach();  // إلغاء ارتباط الفئات بالفيلم (حذف السجلات المرتبطة في categoryables)
        $movie->actors()->detach();  // إلغاء ارتباط الفئات بالفيلم (حذف السجلات المرتبطة في categoryables)

        // حذف الفيلم
        $movie->delete();

        // إعادة التوجيه مع رسالة النجاح
        return redirect()->back()->with('success', 'Movie deleted successfully!');
    }
    public function edit($id){
        $movie=Movie::findOrFail($id);
        $categories=Category::all();
        $actors= Actor::all();
        return view('admin.movie_edit',compact('movie','categories','actors'));
    }
    public function update(Request $request, $id)
    {
        // العثور على الفيلم
        $movie = Movie::findOrFail($id);


        // التحقق من البيانات المُرسلة
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:40|max:240',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'required|string',
            'country' => 'required|string',
            'categories' => 'required|array', // الفئات يجب أن تكون مصفوفة
            'actors' => 'required|array', // الفئات يجب أن تكون مصفوفة
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // في حال تم رفع صورة جديدة
        ]);



        // تحديث بيانات الفيلم
        $movie->name = $validated['name'];
        $movie->duration = $validated['duration'];
        $movie->year = $validated['year'];
        $movie->description = $validated['description'];
        $movie->country = $validated['country']; // تأكد من تحديث حقل البلد أيضًا

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($movie->image && file_exists(public_path('images/movie_images/' . $movie->image))) {
                Storage::disk('kok')->delete('movie_images/'.$movie->image);
            }
            $imageName=$validated['image']->getClientOriginalName();
            $imagePath=$validated['image']->storeAs('/movie_images',$imageName,'kok');
            $movie->image=$imagePath;
        }

        // تحديث الفئات المرتبطة بالفيلم
        $movie->categories()->sync($validated['categories']); // التزامن مع الفئات الجديدة

        $movie->actors()->sync($validated['actors']); // التزامن مع الممثلين الجديدة


        // حفظ التغييرات
        $movie->save();

        // إعادة التوجيه مع رسالة النجاح
        return redirect()->route('movies.index')->with('success', 'Movie updated successfully!');
    }
}