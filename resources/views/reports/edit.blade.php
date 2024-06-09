@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Редактировать жалобу') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('reports.update', $report->id) }}">
        @csrf
        @method('PUT')

        <!-- Поле для отображения причины -->
        <div class="form-group">
            <label for="reason">{{ __('Причина') }}</label>
            <p id="reason-display">{{ $report->reason }}</p>
            <input type="hidden" name="reason" value="{{ $report->reason }}">
        </div>

        <!-- Поле для комментария -->
        <div class="form-group">
            <label for="custom_reason">{{ __('Комментарий') }}</label>
            <textarea name="custom_reason" id="custom_reason" class="form-control" rows="3">{{ $report->custom_reason }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Обновить') }}</button>
    </form>
</div>
@endsection
