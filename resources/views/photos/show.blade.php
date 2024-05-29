@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $photo->path) }}" class="img-fluid" style="max-width: 100%; max-height: 500px; object-fit: contain;" alt="Photo">
        </div>
        <div class="col-md-6">
            <h1>{{ $photo->description }}</h1>
            <p>{{ __('Автор: ') . $photo->user->name }}</p>
            <p>{{ __('Дата: ') . $photo->created_at->format('d.m.Y') }}</p>
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
                <h3 class="mt-4">{{ __('Оставить комментарий') }}</h3>
                <form method="POST" action="{{ route('photo.comment.store', $photo->id) }}">
                    @csrf
                    <div class="form-group mt-2">
                        <textarea name="content" class="form-control" rows="3" placeholder="{{ __('Ваш комментарий...') }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-2">{{ __('Отправить') }}</button>
                </form>
            @endauth

            <h3 class="mt-4">{{ __('Комментарии') }}</h3>
            @if($comments->isEmpty())
                <div class="alert alert-warning" role="alert">
                    {{ __('Комментариев пока нет.') }}
                </div>
            @else
                @foreach ($comments as $comment)
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

                <div class="d-flex justify-content-center mt-4">
                    {{ $comments->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
