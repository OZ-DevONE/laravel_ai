@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Избранные фотографии') }}</h1>
    <div class="row">
        @forelse($favorites as $favorite)
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('storage/' . $favorite->photo->path) }}" class="card-img-top" alt="Photo">
                    <div class="card-body">
                        <h5 class="card-title">{{ $favorite->photo->description }}</h5>
                        <p class="card-text">{{ __('Автор: ') . $favorite->photo->user->name }}</p>
                        <p class="card-text">{{ __('Дата: ') . $favorite->photo->created_at->format('d.m.Y') }}</p>
                        <form method="POST" action="{{ route('photo.unfavorite', $favorite->photo->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">{{ __('Удалить из избранных') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>{{ __('Вы еще не добавили фотографии в избранные.') }}</p>
        @endforelse
    </div>
</div>
@endsection
