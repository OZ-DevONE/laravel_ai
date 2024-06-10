@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Детали жалоб на фото') }}</h1>

    <div class="card mb-3">
        <div class="card-header">
            {{ __('Фото: ') }}
        </div>
        <div class="card-body text-center">
            <img src="{{ asset('storage/' . $report->photo->path) }}" class="img-fluid" style="max-width: 300px; max-height: 300px; object-fit: contain;" alt="Photo">
            <p>{{ __('Описание: ') }} {{ $report->photo->description }}</p>
        </div>
    </div>

    <h3>{{ __('Жалобы на это фото') }}</h3>

    @foreach($report->userReports as $userReport)
        <div class="card mb-3">
            <div class="card-header">
                {{ __('Жалоба от: ') }} {{ $userReport->user->name }} ({{ $userReport->user->email }})
            </div>
            <div class="card-body">
                <p>{{ __('Причина: ') }} {{ $userReport->reason }}</p>
                @if($userReport->reason === 'Прочее')
                    <p>{{ __('Описание жалобы: ') }} {{ $userReport->custom_reason }}</p>
                @endif
                <p>{{ __('Статус: ') }} {{ $userReport->status }}</p>
                <p>{{ __('Дата и время подачи: ') }} {{ $userReport->created_at->format('d.m.Y H:i:s') }}</p>
                @if($userReport->admin_comment)
                    <p>{{ __('Комментарий администратора: ') }} {{ $userReport->admin_comment }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
