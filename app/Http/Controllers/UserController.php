<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Movie;
use App\Models\Review;
use App\Models\Series;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Symfony\Contracts\Service\Attribute\Required;

class UserController extends Controller
{

    //<<<<<<<<<<<<<<<<<<<<<Admin Methods>>>>>>>>>>>>>>>>>>
  public function index(){
    $users=User::where('is_admin',1)->get();
    return view('admin.users',compact('users'));
  }
  public function store(Request $request)
  {
      // التحقق من المدخلات
      $validated = $request->validate([
          'name' => 'required',
          'email' => 'required|email',
          'password' => 'required|min:8',
          'phone' => 'required',
          'image' => 'required|mimes:png,jpg,jpeg', // التحقق من أن الملف صورة
      ], [
          'name.required' => 'Enter Name',
          'email.required' => 'Enter Email',
          'password.required' => 'Enter Password',
          'phone.required' => 'Enter Phone',
          'image.required' => 'Enter Image',
          'image.image' => 'The file must be an image.',
      ]);
      // تحقق من وجود الملف
      if ($request->hasFile('image')) {
          $image = $request->file('image');
          $imageName = time() . '_' . $image->getClientOriginalName(); // لتفادي تكرار الأسماء
          $imagePath = $image->storeAs('user_images', $imageName, 'kok'); // تخزين الصورة في الدليل العام
      } else {
          return redirect()->back()->withErrors(['image' => 'Image upload failed']);
      }

      // إنشاء المستخدم
     $user= User::create([
          'name' => $validated['name'],
          'email' => $validated['email'],
          'password' => bcrypt($validated['password']), // تشفير كلمة المرور
          'phone' => $validated['phone'],
          'image' => $imagePath // تخزين مسار الصورة
      ]);

      return redirect()->back();
  }
//   public function destroy($id) {
//     $user=User::findOrFail($id);
//     Storage::disk('kok')->delete($user->image);
//     $user->delete();

//     return redirect()->back();
//   }
  public function edit($id){
    $user=User::findOrFail($id);
    return view ('admin.user_edit',compact('user'));
  }
  public function update(Request $request,$id){

     // العثور على الفيلم
     $user = User::findOrFail($id);


      // التحقق من المدخلات
      $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8',
        'phone' => 'required',
        'image' => 'nullable|mimes:png,jpg,jpeg', // التحقق من أن الملف صورة
    ], [
        'name.required' => 'Enter Name',
        'email.required' => 'Enter Email',
        'password.required' => 'Enter Password',
        'phone.required' => 'Enter Phone',
        'image.required' => 'Enter Image',
    ]);


     // تحديث بيانات الفيلم
     $user->name = $validated['name'];
     $user->email = $validated['email'];
     $user->password = bcrypt($validated['password']);
     $user->phone = $validated['phone'];

     // إذا تم رفع صورة جديدة
     if ($request->hasFile('image')) {
         // حذف الصورة القديمة إذا كانت موجودة
         if ($user->image) {
             Storage::disk('kok')->delete($user->image);
         }
         $imageName=$validated['image']->getClientOriginalName();
         $imagePath=$validated['image']->storeAs('/user_images',$imageName,'kok');
         $user->image=$imagePath;
     }
    $user->save();
    return redirect()->route('admin.users.index');
  }







  //<<<<<<<<<<<<<<<<<<User Methods>>>>>>>>>>>>>>>>>>
  public function home() {
    // جلب الأفلام وترتيبها حسب متوسط التقييمات
    $topRatedMovies = Movie::with('reviews') // تحميل التقييمات
        ->get()
        ->sortByDesc(fn($movie) => $movie->reviews->avg('rating'))
        ->take(6); // اختيار أعلى 6 أفلام فقط

    // جلب المسلسلات وترتيبها حسب متوسط التقييمات
    $topRatedSeries = Series::with('reviews')
        ->get()
        ->sortByDesc(fn($series) => $series->reviews->avg('rating'))
        ->take(6); // اختيار أعلى 6 مسلسلات فقط

 // جلب أعلى المراجعات إعجابًا للأفلام
 $topRatedMoviesReviews = [];
 foreach ($topRatedMovies as $index => $movie) {
     $topRatedMoviesReviews[$index] = $movie->reviews()
         ->with('user')
         ->withCount('likes')
         ->orderByDesc('likes_count')
         ->take(1)
         ->get();
 }


// جلب أعلى المراجعات إعجابًا للمسلسلات
$topRatedSeriesReviews = [];
foreach ($topRatedSeries as $index => $series) {
    $topRatedSeriesReviews[$index] = $series->reviews()
        ->with('user')
        ->withCount('likes')
        ->orderByDesc('likes_count')
        ->take(1)
        ->get();
}



    return view('user.userHome', compact('topRatedMovies', 'topRatedMoviesReviews', 'topRatedSeries', 'topRatedSeriesReviews'));
}



  public function showAllMovie(){
    $movies=Movie::all();
    $categories=Category::all();
    return view('user.showAllMovie',compact('movies','categories'));
  }

  public function showAllSeries(){
    $series=Series::all();
    $categories=Category::all();
    return view('user.showAllSeries',compact('series','categories'));
  }

  public function movieRating(Request $request){
    $validate = $request->validate([
        'review' => 'required',
        'rating' => 'required',
    ]);

    // تحقق إذا كان المستخدم قد قيم الفيلم بالفعل
    $existingReview = Review::where('user_id', auth()->user()->id)
                            ->where('reviewable_type', 'App\Models\Movie')
                            ->where('reviewable_id', $request->movie_id)
                            ->first();

    if ($existingReview) {
        // إذا كان التقييم موجودًا، جعل التقييم القديم null
        $existingReview->rating = null;
        $existingReview->save();
    }

    // إضافة التقييم الجديد
    $newReview = new Review();
    $newReview->content = $validate['review'];
    $newReview->rating = $validate['rating'];  // التقييم الجديد
    $newReview->reviewable_type = 'App\Models\Movie';
    $newReview->reviewable_id = $request->movie_id;
    $newReview->user_id = auth()->user()->id;
    $newReview->save();

    return redirect()->back();
  }
  public function seriesRating(Request $request){
    $validate = $request->validate([
        'review' => 'required',
        'rating' => 'required',
    ]);

    // تحقق إذا كان المستخدم قد قيم الفيلم بالفعل
    $existingReview = Review::where('user_id', auth()->user()->id)
                            ->where('reviewable_type', 'App\Models\Series')
                            ->where('reviewable_id', $request->series_id)
                            ->first();

    if ($existingReview) {
        // إذا كان التقييم موجودًا، جعل التقييم القديم null
        $existingReview->rating = null;
        $existingReview->save();
    }

    // إضافة التقييم الجديد
    $newReview = new Review();
    $newReview->content = $validate['review'];
    $newReview->rating = $validate['rating'];  // التقييم الجديد
    $newReview->reviewable_type = 'App\Models\Series';
    $newReview->reviewable_id = $request->series_id;
    $newReview->user_id = auth()->user()->id;
    $newReview->save();

    return redirect()->back();
  }
    public function informationAboutMovieOrSeries($id, $type)
    {

        if ($type == 'movie') {
        $item = Movie::findOrFail($id);
            return view('user.Movie_Series', compact('item', 'type'));
        }

        if ($type == 'series') {
        $item = Series::findOrFail($id);
            return view('user.Movie_Series', compact('item', 'type'));
        }

        return redirect()->route('user.home')->with('error', 'Invalid type!');
    }



  public function destroy(Request $request)
    {
        return $request;
    // تسجيل الخروج
    Auth::guard('web')->logout();

    // إلغاء الجلسة وتجديد التوكن
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // التحقق من نوع المستخدم
    if (auth()->check() && auth()->user()->is_admin==0) {
        // إذا كان Admin، توجيهه إلى صفحة الـ Admin
        return redirect()->route('admin.home');
    } else {
        // إذا كان User، توجيهه إلى صفحة الـ Home أو صفحة الـ User
        return redirect()->route('user.home');
    }}
}