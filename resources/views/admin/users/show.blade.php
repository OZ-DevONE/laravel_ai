@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-4">User Details</h1>
    <div class="card">
        <div class="card-header">
            {{ $user->name }}
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Is Admin:</strong> {{ $user->is_admin ? 'Yes' : 'No' }}</p>
            <p><strong>Is Banned:</strong> {{ $user->is_banned ? 'Yes' : 'No' }}</p>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back to Users</a>
        </div>
    </div>
</div>
@endsection
