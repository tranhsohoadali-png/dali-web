<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('product')->latest();
        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }
        $reviews = $query->paginate(20)->withQueryString();
        $pending = Review::where('is_approved', false)->count();
        return view('admin.reviews.index', compact('reviews', 'pending'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Đã duyệt đánh giá!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Đã xoá đánh giá!');
    }
}
