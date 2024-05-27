@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Фотографии</h1>
    @if ($photos->isEmpty())
        <p>У вас нет загруженных фото.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Фото</th>
                    <th>Описание</th>
                    <th>Лайки</th>
                    <th>Дизлайки</th>
                    <th>Комментарии</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($photos as $photo)
                <tr>
                    <td>{{ $photo->id }}</td>
                    <td>{{ $photo->user->name }}</td>
                    <td>
                        <img 
                            data-src="{{ asset('storage/' . $photo->path) }}" 
                            class="lazyload" 
                            alt="Фото пользователя" 
                            style="width: 100px; height: auto;"
                        >
                    </td>
                    <td>{{ $photo->description }}</td>
                    <td>{{ $photo->likes_count }}</td>
                    <td>{{ $photo->dislikes_count }}</td>
                    <td>{{ $photo->comments_count }}</td>
                    <td>
                        <a href="{{ route('admin.adminphoto.edit', $photo->id) }}" class="btn btn-primary">Редактировать</a>
                        <form action="{{ route('admin.adminphoto.destroy', $photo->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $photos->links() }} <!-- Пагинация -->
    @endif
</div>
@endsection
