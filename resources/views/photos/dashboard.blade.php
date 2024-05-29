@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <button class="btn btn-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                {{ __('Фильтры') }}
            </button>
            <div class="collapse" id="filterCollapse">
                <form method="GET" action="{{ route('photo.index') }}">
                    <div class="mb-3">
                        <label for="user_name" class="form-label">{{ __('Имя автора') }}</label>
                        <input type="text" class="form-control" id="user_name" name="filter[user.name]" value="{{ request('filter.user.name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Описание') }}</label>
                        <input type="text" class="form-control" id="description" name="filter[description]" value="{{ request('filter.description') }}">
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="form-label">{{ __('Дата создания') }}</label>
                        <input type="date" class="form-control" id="created_at" name="filter[created_at]" value="{{ request('filter.created_at') }}">
                    </div>
                    <div class="mb-3">
                        <label for="sort" class="form-label">{{ __('Сортировка') }}</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('По дате (новые)') }}</option>
                            <option value="-created_at" {{ request('sort') == '-created_at' ? 'selected' : '' }}>{{ __('По дате (старые)') }}</option>
                            <option value="likes_count" {{ request('sort') == 'likes_count' ? 'selected' : '' }}>{{ __('По лайкам (убыв.)') }}</option>
                            <option value="-likes_count" {{ request('sort') == '-likes_count' ? 'selected' : '' }}>{{ __('По лайкам (возр.)') }}</option>
                            <option value="comments_count" {{ request('sort') == 'comments_count' ? 'selected' : '' }}>{{ __('По комментариям (убыв.)') }}</option>
                            <option value="-comments_count" {{ request('sort') == '-comments_count' ? 'selected' : '' }}>{{ __('По комментариям (возр.)') }}</option>
                            <option value="dislikes_count" {{ request('sort') == 'dislikes_count' ? 'selected' : '' }}>{{ __('По дизлайкам (убыв.)') }}</option>
                            <option value="-dislikes_count" {{ request('sort') == '-dislikes_count' ? 'selected' : '' }}>{{ __('По дизлайкам (возр.)') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Применить') }}</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        @if($photos->isEmpty())
            <div class="col-md-8">
                <div class="alert alert-warning text-center" role="alert">
                    {{ __('Фотографии не найдены.') }}
                </div>
            </div>
        @else
            @foreach ($photos as $photo)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <a href="{{ route('photo.show', $photo->id) }}">
                            <img data-src="{{ asset('storage/' . $photo->path) }}" class="lazyload card-img-top" alt="Photo">
                        </a>
                        <div class="card-body">
                            <p class="card-text">{{ __('Автор: ') . $photo->user->name }}</p>
                            <p class="card-text"><small class="text-muted">{{ __('Дата: ') . $photo->created_at->format('d.m.Y') }}</small></p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            {{ $photos->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
