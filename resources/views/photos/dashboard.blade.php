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
    <div class="row justify-content-center mt-4">
        @if($photos->isEmpty())
            <div class="col-md-8">
                <div class="alert alert-warning text-center" role="alert">
                    @if($filtersApplied)
                        {{ __('По вашему запросу ничего не найдено.') }}
                    @else
                        {{ __('Фотографии не найдены.') }}
                    @endif
                </div>
            </div>
        @else
            @foreach ($photos as $photo)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img data-src="{{ asset('storage/' . $photo->path) }}" class="lazyload card-img-top" alt="Photo">
                        <div class="card-body">
                            <h5 class="card-title">Автор: {{ $photo->user->name }}</h5>
                            <p class="card-text">Описание: {{ $photo->description }}</p>
                            <p class="card-text"><small class="text-muted">{{ $photo->created_at->format('d.m.Y') }}</small></p>
                            <div class="d-flex justify-content-between align-items-center">
                                @auth
                                    <form method="POST" action="{{ route('photo.like', $photo->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">{{ __('Лайк') }}</button>
                                    </form>
                                    <span>{{ $photo->likes_count }}</span>
                                    <form method="POST" action="{{ route('photo.dislike', $photo->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Дизлайк') }}</button>
                                    </form>
                                    <span>{{ $photo->dislikes_count }}</span>
                                @else
                                    <span>{{ __('Лайки:') }} {{ $photo->likes_count }}</span>
                                    <span>{{ __('Дизлайки:') }} {{ $photo->dislikes_count }}</span>
                                @endauth
                            </div>
                            @auth
                                <button class="btn btn-primary btn-sm mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#commentForm{{ $photo->id }}" aria-expanded="false" aria-controls="commentForm{{ $photo->id }}">
                                    {{ __('Оставить комментарий') }}
                                </button>
                                <div class="collapse" id="commentForm{{ $photo->id }}">
                                    <form method="POST" action="{{ route('photo.comment.store', $photo->id) }}">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <textarea name="content" class="form-control" rows="3" placeholder="{{ __('Ваш комментарий...') }}"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">{{ __('Отправить') }}</button>
                                    </form>
                                </div>
                            @endauth
                            @if($photo->comments)
                                @foreach ($photo->comments as $comment)
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <p class="card-text">{{ $comment->content }}</p>
                                            <p class="card-text"><small class="text-muted">{{ $comment->user->name }} - {{ $comment->created_at->format('d.m.Y H:i') }}</small></p>
                                            @if($comment->user_id == auth()->id())
                                                <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editCommentForm{{ $comment->id }}" aria-expanded="false" aria-controls="editCommentForm{{ $comment->id }}">
                                                    {{ __('Редактировать') }}
                                                </button>
                                                <div class="collapse" id="editCommentForm{{ $comment->id }}">
                                                    <form method="POST" action="{{ route('comment.update', $comment->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="form-group mt-2">
                                                            <textarea name="content" class="form-control" rows="3">{{ $comment->content }}</textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2">{{ __('Обновить') }}</button>
                                                    </form>
                                                </div>
                                                <form method="POST" action="{{ route('comment.destroy', $comment->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm mt-2">{{ __('Удалить') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
