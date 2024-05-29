<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            $page = $request->get('page', 1);
            $cacheKey = 'user_' . $user->id . '_photos_page_' . $page;
            
            $photos = Cache::remember($cacheKey, now()->addMinutes(10), function() use ($user) {
                return $user->photos()->with(['user', 'comments.user', 'likes', 'dislikes'])->withCounts()->paginate(10);
            });
        } else {
            $photos = collect();
        }
        
        return view('photos.index', compact('photos'));
    }    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('photos.create');
    }

    public function show($id)
    {
        $photo = Photo::with(['user', 'comments.user', 'likes', 'dislikes'])->findOrFail($id);
        $comments = $photo->comments()->paginate(3);

        return view('photos.show', compact('photo', 'comments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Проверяем, авторизован ли пользователь
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Вы должны быть авторизованы для загрузки фото.');
        }
    
        // Валидация входных данных
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string|max:255',
        ], [
            'photo.required' => 'Фото обязательно для загрузки.',
            'photo.image' => 'Файл должен быть изображением.',
            'photo.mimes' => 'Фото должно быть в формате jpeg, png или jpg.',
            'photo.max' => 'Фото не должно превышать 2 МБ.',
            'description.required' => 'Описание обязательно для заполнения.',
            'description.max' => 'Описание не должно превышать 255 символов.',
        ]);
    
        // Сохранение фото
        try {
            $path = $request->file('photo')->store('photos', 'public');
    
            Auth::user()->photos()->create([
                'path' => $path,
                'description' => $request->description,
            ]);

            // Очистка кэша после загрузки нового фото
            $user = Auth::user();
            $pages = ceil($user->photos()->count() / 10);
            for ($page = 1; $page <= $pages; $page++) {
                $cacheKey = 'user_' . $user->id . '_photos_page_' . $page;
                Cache::forget($cacheKey);
            }
    
            return redirect()->route('photos.index')->with('status', 'Фото успешно загружено');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => 'Не удалось загрузить фото. Пожалуйста, попробуйте снова.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $photo = Photo::findOrFail($id);
        return view('photos.edit', compact('photo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $photo = Photo::findOrFail($id);

        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string|max:255',
        ], [
            'photo.image' => 'Файл должен быть изображением.',
            'photo.mimes' => 'Фото должно быть в формате jpeg, png или jpg.',
            'photo.max' => 'Фото не должно превышать 2 МБ.',
            'description.max' => 'Описание не должно превышать 255 символов.',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photos', 'public');
                $photo->path = $path;
            }

            $photo->description = $request->description;
            $photo->save();

            $user = Auth::user();
            $pages = ceil($user->photos()->count() / 10);
            for ($page = 1; $page <= $pages; $page++) {
                $cacheKey = 'user_' . $user->id . '_photos_page_' . $page;
                Cache::forget($cacheKey);
            }

            return redirect()->route('photos.index')->with('status', 'Фото успешно обновлено');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => 'Не удалось обновить фото. Пожалуйста, попробуйте снова.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        try {
            $photo->delete();

            // Очистка кэша после удаления фото
            $user = Auth::user();
            $pages = ceil(max($user->photos()->count() - 1, 1) / 10); // Пересчет количества страниц
            for ($page = 1; $page <= $pages; $page++) {
                $cacheKey = 'user_' . $user->id . '_photos_page_' . $page;
                Cache::forget($cacheKey);
            }

            return redirect()->route('photos.index')->with('status', 'Фото успешно удалено');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => 'Не удалось удалить фото. Пожалуйста, попробуйте снова.']);
        }
    }
}
