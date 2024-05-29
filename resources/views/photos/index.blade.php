@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if($photos->isEmpty())
            <div class="col-md-8">
                <div class="alert alert-warning text-center" role="alert">
                    {{ __('У вас нет загруженных фото.') }}
                </div>
            </div>
        @else
            @foreach ($photos as $photo)
                @if ($photo->canBeViewedBy(Auth::user()))
                    <div class="col-md-4 mb-4">
                        <div class="card {{ $photo->is_blocked ? 'border-danger' : '' }}">
                            <img data-src="{{ asset('storage/' . $photo->path) }}" class="lazyload card-img-top" alt="Photo">
                            <div class="card-body">
                                <p class="card-text">{{ __('Описание:') }} {{ $photo->description }}</p>
                                <p class="card-text">{{ __('Лайки:') }} {{ $photo->likes_count }}</p>
                                <p class="card-text">{{ __('Дизлайки:') }} {{ $photo->dislikes_count }}</p>
                                <p class="card-text">{{ __('Комментарии:') }} {{ $photo->comments_count }}</p>
                                @if ($photo->is_blocked)
                                    <p class="text-danger">{{ __('Фото заблокировано') }}</p>
                                    <p>{{ __('Причина:') }} {{ $photo->block_description }}</p>
                                @endif
                                <a href="{{ route('photos.edit', $photo->id) }}" class="btn btn-primary">{{ __('Редактировать') }}</a>
                                <form method="POST" action="{{ route('photos.destroy', $photo->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ __('Удалить') }}</button>
                                </form>
                            </div>
                            @if($photo->comments->isNotEmpty())
                                <div class="card-footer">
                                    @foreach ($photo->comments as $comment)
                                        <div class="comment border p-2 mb-2">
                                            <p class="card-text"><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
                                            <p class="card-text"><small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small></p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
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
