@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Редактировать Фото</h1>

    <form action="{{ route('admin.adminphoto.update', $photo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" class="form-control" id="description" rows="3">{{ old('description', $photo->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="path" class="form-label">Путь к фото</label>
            <input type="file" name="path" class="form-control" id="path">
            @if ($photo->path)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $photo->path) }}" alt="Фото пользователя" class="img-thumbnail" style="width: 200px; height: auto;">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="likes" class="form-label">Лайки</label>
            <input type="number" name="likes" class="form-control" id="likes" value="{{ old('likes', $photo->likes->count()) }}">
        </div>

        <div class="mb-3">
            <label for="dislikes" class="form-label">Дизлайки</label>
            <input type="number" name="dislikes" class="form-control" id="dislikes" value="{{ old('dislikes', $photo->dislikes->count()) }}">
        </div>

        <div class="mb-3">
            <label for="is_blocked" class="form-label">Заблокировано</label>
            <select name="is_blocked" id="is_blocked" class="form-select">
                <option value="0" {{ old('is_blocked', $photo->is_blocked) == 0 ? 'selected' : '' }}>Нет</option>
                <option value="1" {{ old('is_blocked', $photo->is_blocked) == 1 ? 'selected' : '' }}>Да</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="block_description" class="form-label">Причина блокировки</label>
            <textarea name="block_description" class="form-control" id="block_description" rows="3">{{ old('block_description', $photo->block_description) }}</textarea>
        </div>

        <h3>Комментарии</h3>
        @foreach($photo->comments as $comment)
            <div class="mb-3">
                <label for="comment-{{ $comment->id }}" class="form-label">Комментарий от {{ $comment->user->name }}</label>
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

    <form action="{{ route('admin.adminphoto.destroy', $photo->id) }}" method="POST" class="mt-2">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Удалить Фото</button>
    </form>
</div>
@endsection
