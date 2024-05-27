@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Редактировать Фото</h1>

    <form action="{{ route('admin.adminphoto.update', $photo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea name="description" class="form-control" id="description" rows="3">{{ old('description', $photo->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="path">Путь к фото</label>
            <input type="file" name="path" class="form-control-file" id="path">
            @if ($photo->path)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $photo->path) }}" alt="Фото пользователя" style="width: 200px; height: auto;">
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="likes">Лайки</label>
            <input type="number" name="likes" class="form-control" id="likes" value="{{ old('likes', $photo->likes->count()) }}">
        </div>

        <div class="form-group">
            <label for="dislikes">Дизлайки</label>
            <input type="number" name="dislikes" class="form-control" id="dislikes" value="{{ old('dislikes', $photo->dislikes->count()) }}">
        </div>

        <h3>Комментарии</h3>
        @foreach($photo->comments as $comment)
            <div class="form-group">
                <label for="comment-{{ $comment->id }}">Комментарий от {{ $comment->user->name }}</label>
                <textarea name="comments[{{ $comment->id }}]" class="form-control" id="comment-{{ $comment->id }}" rows="2">{{ old('comments.' . $comment->id, $comment->content) }}</textarea>
                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить Комментарий</button>
                </form>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success">Сохранить изменения</button>
    </form>

    <form action="{{ route('admin.adminphoto.destroy', $photo->id) }}" method="POST" style="display:inline;" class="mt-2">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Удалить Фото</button>
    </form>
</div>
@endsection
