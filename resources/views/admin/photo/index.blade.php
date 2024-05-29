@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Фотографии</h1>

    <!-- Форма фильтрации -->
    <form action="{{ route('admin.adminphoto.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="is_blocked">Статус</label>
                    <select name="is_blocked" id="is_blocked" class="form-control">
                        <option value="">Все</option>
                        <option value="1" {{ request('is_blocked') == '1' ? 'selected' : '' }}>Заблокировано</option>
                        <option value="0" {{ request('is_blocked') == '0' ? 'selected' : '' }}>Активно</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="user_id">Пользователь</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">Все</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="from_date">С даты</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="to_date">По дату</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Фильтр</button>
                <a href="{{ route('admin.adminphoto.index') }}" class="btn btn-secondary">Сбросить</a>
            </div>
        </div>
    </form>

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
                    <th>Статус</th>
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
                            src="{{ asset('storage/' . $photo->path) }}" 
                            class="img-fluid" 
                            alt="Фото пользователя" 
                            style="width: 100px; height: auto;"
                        >
                    </td>
                    <td>{{ $photo->description }}</td>
                    <td>{{ $photo->likes_count }}</td>
                    <td>{{ $photo->dislikes_count }}</td>
                    <td>{{ $photo->comments_count }}</td>
                    <td>
                        @if($photo->is_blocked)
                            <span class="text-danger">Заблокировано</span>
                            <br>
                            <small>{{ $photo->block_description }}</small>
                        @else
                            <span class="text-success">Активно</span>
                        @endif
                    </td>
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
