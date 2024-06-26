<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Dislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::withCount(['comments', 'likes', 'dislikes']);
    
        // Фильтрация по статусу блокировки
        if ($request->filled('is_blocked')) {
            $query->where('is_blocked', $request->input('is_blocked'));
        }
    
        // Фильтрация по пользователю
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
    
        // Фильтрация по дате
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
    
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }
    
        $photos = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = \App\Models\User::all(); // Получение всех пользователей для фильтрации
    
        return view('admin.photo.index', compact('photos', 'users'));
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
            'is_blocked' => 'required|boolean',
            'block_description' => 'nullable|string|max:255',
        ]);
    
        $photo = Photo::withCount(['likes', 'dislikes', 'comments'])->findOrFail($id);
        $photo->description = $request->input('description');
    
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('photos', 'public');
            $photo->path = $path;
        }
    
        $photo->is_blocked = $request->input('is_blocked');
        $photo->block_description = $request->input('block_description');
        $photo->save();
    
        if ($photo->is_blocked) {
            $photo->blockPhoto($request->input('block_description'));
        }
    
        // Обновление количества лайков
        $likesDiff = $request->input('likes') - $photo->likes_count;
        if ($likesDiff > 0) {
            for ($i = 0; $i < $likesDiff; $i++) {
                Like::create(['photo_id' => $photo->id, 'user_id' => auth()->id()]);
            }
        } elseif ($likesDiff < 0) {
            $photo->likes()->limit(abs($likesDiff))->delete();
        }
    
        // Обновление количества дизлайков
        $dislikesDiff = $request->input('dislikes') - $photo->dislikes_count;
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
