<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Category;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeriesController extends Controller
{
    public function index(){
        $series=Series::paginate(18);
        $categories=Category::all();
        $actors= Actor::all();
        return view('admin.show_all_series',compact('series','categories','actors'));
    }
    public function store(Request $request){
        $validatedData= $request->validate([
            'name' => 'required',
            'description' => 'required',
            'episode' => 'required|integer',
            'year' => 'required|integer',
            'country' => 'required',
            'categories'=>'required|array',
            'actor'=>'required|array',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048',
        ]);
        $imageName=$request->file('image')->getClientOriginalName();
        $imagePath = $request->file('image')->storeAs('/series_images',$imageName,'kok');

        $series=Series::create([
            'name'=>$validatedData['name'],
            'description'=>$validatedData['description'],
            'country'=>$validatedData['country'],
            'year'=>$validatedData['year'],
            'episode'=>$validatedData['episode'],
            'image'=>$imagePath,
        ]);
        $series->categories()->attach($validatedData['categories']);
        $series->actors()->attach($validatedData['actor']);
        return redirect()->back()->with('success', 'تمت إضافة الفيلم بنجاح!');

    }
    public function destroy($id)
    {
        $series=Series::findOrFail($id);
        Storage::disk('kok')->delete('series_images/'.$series->image);
        $series->categories()->detach();
        $series->Actors()->detach();
        $series->delete();
        return redirect()->back()->with('success','Series is deleted');
    }

    public function edit($id){
        $series=Series::findOrFail($id);
        $categories=Category::all();
        $actors= Actor::all();
        return view('admin.series_edit',compact('series','categories','actors'));
    }

    public function update(Request $request, $id)
    {
        // العثور على الفيلم
        $series = Series::findOrFail($id);

        // التحقق من البيانات المُرسلة
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'episode' => 'required|integer|min:4',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'required|string',
            'country' => 'required|string',
            'categories' => 'required|array', // الفئات يجب أن تكون مصفوفة
            'actors' => 'required|array', // الممثلين يجب أن تكون مصفوفة
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048', // التأكد من نوع وحجم الصورة
        ]);

        // تحديث البيانات الأساسية
        $series->episode = $validated['episode'];
        $series->year = $validated['year'];
        $series->name = $validated['name'];
        $series->description = $validated['description'];
        $series->country = $validated['country'];

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($series->image) {
                Storage::disk('kok')->delete('series_images/' . $series->image);
            }

            // تخزين الصورة الجديدة
            $imageName = $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('series_images', $imageName, 'kok');
            $series->image = $imagePath;
        }

        // تحديث الفئات المرتبطة بالفيلم
        $series->categories()->sync($validated['categories']);

        // تحديث الممثلين المرتبطين بالفيلم
        $series->actors()->sync($validated['actors']);

        // حفظ التغييرات
        $series->save();

        // إعادة التوجيه مع رسالة النجاح
        return redirect()->route('series.index')->with('success', 'Series updated successfully!');
    }

}
