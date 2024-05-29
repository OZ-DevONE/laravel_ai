<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Photo;
use App\Models\Dislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PhotoDashboardController extends Controller
{
    public function index(Request $request)
    {
        $photos = QueryBuilder::for(Photo::with(['user', 'comments.user', 'likes', 'dislikes']))
            ->allowedFilters([
                AllowedFilter::partial('description'),
                AllowedFilter::partial('user.name'),
                AllowedFilter::exact('created_at'),
            ])
            ->allowedSorts([
                'created_at',
                'comments_count',
                'likes_count',
                'dislikes_count',
                '-created_at',
                '-comments_count',
                '-likes_count',
                '-dislikes_count',
            ])
            ->withCounts()
            ->paginate(3)
            ->appends(request()->query());
    
        $filtersApplied = $request->has('filter');
    
        return view('photos.dashboard', compact('photos', 'filtersApplied'));
    }

    public function __construct()
    {
        $this->middleware('auth')->only([
            'storeComment', 
            'updateComment', 
            'destroyComment', 
            'likePhoto', 
            'dislikePhoto'
        ]);
    }

    protected function containsProfanity($text)
    {
        $profanities = ['мат', 'матное слово', 'niggaдяй'];
        foreach ($profanities as $badword) {
            if (stripos($text, $badword) !== false) {
                return true;
            }
        }
        return false;
    }

    public function storeComment(Request $request, $photoId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        if ($this->containsProfanity($request->content)) {
            return back()->withErrors(['content' => 'Ваш комментарий содержит нецензурные слова.']);
        }

        Comment::create([
            'user_id' => Auth::id(),
            'photo_id' => $photoId,
            'content' => $request->content,
        ]);

        return back();
    }

    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        if ($this->containsProfanity($request->content)) {
            return back()->withErrors(['content' => 'Ваш комментарий содержит нецензурные слова.']);
        }

        $comment = Comment::findOrFail($commentId);

        if ($comment->user_id != Auth::id()) {
            abort(403);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return back();
    }

    public function destroyComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if ($comment->user_id != Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return back();
    }

    public function likePhoto($photoId)
    {
        $userId = Auth::id();
        $existingLike = Like::where('user_id', $userId)->where('photo_id', $photoId)->first();
        $existingDislike = Dislike::where('user_id', $userId)->where('photo_id', $photoId)->first();

        if ($existingDislike) {
            $existingDislike->delete();
        }

        if (!$existingLike) {
            Like::create([
                'user_id' => $userId,
                'photo_id' => $photoId,
            ]);
        }

        return back();
    }

    public function dislikePhoto($photoId)
    {
        $userId = Auth::id();
        $existingDislike = Dislike::where('user_id', $userId)->where('photo_id', $photoId)->first();
        $existingLike = Like::where('user_id', $userId)->where('photo_id', $photoId)->first();

        if ($existingLike) {
            $existingLike->delete();
        }

        if (!$existingDislike) {
            Dislike::create([
                'user_id' => $userId,
                'photo_id' => $photoId,
            ]);
        }

        return back();
    }
}
