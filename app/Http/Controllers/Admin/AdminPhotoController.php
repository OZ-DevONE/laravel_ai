<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Dislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminPhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::withCount(['comments', 'likes', 'dislikes'])->paginate(10);
        return view('admin.photo.index', compact('photos'));
    }

    public function edit($id)
    {
        $photo = Photo::with(['comments', 'likes', 'dislikes'])->findOrFail($id);
        return view('admin.photo.edit', compact('photo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'likes' => 'required|integer|min:0',
            'dislikes' => 'required|integer|min:0',
            'comments.*' => 'nullable|string|max:255',
        ]);

        $photo = Photo::findOrFail($id);
        $photo->description = $request->input('description');
        
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('photos', 'public');
            $photo->path = $path;
        }

        $photo->save();

        // Обновление количества лайков
        $likesDiff = $request->input('likes') - $photo->likes->count();
        if ($likesDiff > 0) {
            for ($i = 0; $i < $likesDiff; $i++) {
                Like::create(['photo_id' => $photo->id, 'user_id' => auth()->id()]);
            }
        } elseif ($likesDiff < 0) {
            $photo->likes()->limit(abs($likesDiff))->delete();
        }

        // Обновление количества дизлайков
        $dislikesDiff = $request->input('dislikes') - $photo->dislikes->count();
        if ($dislikesDiff > 0) {
            for ($i = 0; $i < $dislikesDiff; $i++) {
                Dislike::create(['photo_id' => $photo->id, 'user_id' => auth()->id()]);
            }
        } elseif ($dislikesDiff < 0) {
            $photo->dislikes()->limit(abs($dislikesDiff))->delete();
        }

        if ($request->has('comments')) {
            foreach ($request->comments as $commentId => $content) {
                $comment = Comment::findOrFail($commentId);
                $comment->update(['content' => $content]);
            }
        }

        return redirect()->route('admin.adminphoto.index')->with('success', 'Фото обновлено успешно');
    }

    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);
        if ($photo->path) {
            Storage::disk('public')->delete($photo->path);
        }
        $photo->delete();

        return redirect()->route('admin.adminphoto.index')->with('success', 'Фото удалено успешно');
    }

    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = Comment::findOrFail($commentId);
        $comment->update(['content' => $request->input('content')]);

        return back()->with('success', 'Комментарий обновлен успешно');
    }

    public function destroyComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();

        return back()->with('success', 'Комментарий удален успешно');
    }
}
