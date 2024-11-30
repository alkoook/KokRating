<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function movieReview()
    {
        $reviews=Review::where('reviewable_type','App\Models\Movie')->with('reviewable')->get();
        return view('user.movieReviews',compact('reviews'));
    }
    public function seriesReview()
    {
        $reviews=Review::where('reviewable_type','App\Models\Series')->with('reviewable')->get();
        return view('user.seriesReviews',compact('reviews'));
    }
    public function likeReview(Request $request)
    {
        // تحقق من أن المستخدم مسجل الدخول
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to like a review.',
            ], 401); // حالة الخطأ 401 تعني أن المستخدم غير مسجل دخول
        }

        // التحقق من أن المراجعة موجودة
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
        ]);

        $review = Review::findOrFail($request->review_id);
        $user = auth()->user();

        // تحقق مما إذا كان المستخدم قد أعجب بالمراجعة مسبقًا
        $alreadyLiked = $review->likes()->where('user_id', $user->id)->exists();

        if ($alreadyLiked) {
            // إذا كان المستخدم قد أعجب بالفعل، احذف الإعجاب
            $review->likes()->where('user_id', $user->id)->delete();
        } else {
            // إذا لم يكن معجبًا بعد، أضف إعجابًا جديدًا
            $review->likes()->create(['user_id' => $user->id]);
        }

        // إعادة العدد الجديد للإعجابات
        return response()->json([
            'success' => true,
            'new_like_count' => $review->likes()->count(),
        ]);
    }
    public function showAllReviewForAdmin()
    {
        $users=User::has('reviews')->get();
        $reviews=Review::all();
        return view('admin.reviews',compact('reviews','users'));
    }
    public function delete($id)
    {
        $review=Review::findOrFail($id);
        $review->delete();
        return redirect()->back();

    }

}