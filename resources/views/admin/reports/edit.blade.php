@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Редактирование жалобы') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.reports.update', $report->id) }}">
        @csrf
        @method('PUT')

        <h3>{{ __('Жалобы по этой фотографии') }}</h3>
        @foreach($report->userReports as $userReport)
            <div class="form-group mt-3">
                <label>{{ __('Пользователь: ') }} {{ $userReport->user->name }} ({{ $userReport->user->email }})</label>
                <p>{{ __('Причина: ') }} {{ $userReport->reason }}</p>

                <label for="status_{{ $userReport->user_id }}">{{ __('Статус') }}</label>
                <select name="status[{{ $userReport->user_id }}]" id="status_{{ $userReport->user_id }}" class="form-control" required>
                    @foreach(App\Models\Report::STATUSES as $status)
                        <option value="{{ $status }}" {{ $userReport->status == $status ? 'selected' : '' }}>{{ __($status) }}</option>
                    @endforeach
                </select>

                <label for="admin_comment_{{ $userReport->user_id }}" class="mt-3">{{ __('Комментарий администратора') }}</label>
                <textarea name="admin_comment[{{ $userReport->user_id }}]" id="admin_comment_{{ $userReport->user_id }}" class="form-control" rows="3" maxlength="1000" placeholder="{{ __('Оставьте пустым, если нет комментария') }}">{{ $userReport->admin_comment }}</textarea>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary mt-3">{{ __('Обновить') }}</button>
    </form>
</div>
@endsection
