@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-4">Детали пользователя</h1>
    <div class="card">
        <div class="card-header">
            {{ $user->name }}
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Имя:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Администратор:</strong> {{ $user->is_admin ? 'Да' : 'Нет' }}</p>
            <p><strong>Заблокирован:</strong> {{ $user->is_banned ? 'Да' : 'Нет' }}</p>
            <p><strong>IP-адрес:</strong> {{ $user->ip_address }}</p>
            <p><strong>User Agent:</strong> {{ $user->user_agent }}</p>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Назад к пользователям</a>
        </div>
    </div>
</div>
@endsection
