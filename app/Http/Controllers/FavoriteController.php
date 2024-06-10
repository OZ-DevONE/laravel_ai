<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function addToFavorites($photoId)
    {
        $user = Auth::user();

        $favorite = Favorite::firstOrCreate([
            'user_id' => $user->id,
            'photo_id' => $photoId
        ]);

        return back()->with('success', 'Фото добавлено в избранные!');
    }

    public function removeFromFavorites($photoId)
    {
        $user = Auth::user();

        Favorite::where('user_id', $user->id)->where('photo_id', $photoId)->delete();

        return back()->with('success', 'Фото удалено из избранных.');
    }

    public function viewFavorites()
    {
        $user = Auth::user();
        $favorites = Favorite::where('user_id', $user->id)->with('photo')->get();

        return view('photos.stars', compact('favorites'));
    }
}
