@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Все жалобы') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-4">
        <div class="form-row">
            <div class="col">
                <select name="filter[status]" class="form-control">
                    <option value="">{{ __('Все статусы') }}</option>
                    @foreach(App\Models\Report::STATUSES as $status)
                        <option value="{{ $status }}" {{ request('filter.status') == $status ? 'selected' : '' }}>{{ __($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="date" name="filter[created_at]" class="form-control" placeholder="{{ __('Дата') }}" value="{{ request('filter.created_at') }}">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">{{ __('Фильтр') }}</button>
            </div>
        </div>
    </form>

    @if($reports->isEmpty())
        <div class="alert alert-warning" role="alert">
            {{ __('Жалобы не найдены.') }}
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">{{ __('ID') }}</th>
                    <th scope="col">{{ __('Пользователь') }}</th>
                    <th scope="col">{{ __('Фото') }}</th>
                    <th scope="col">{{ __('Причина') }}</th>
                    <th scope="col">{{ __('Комментарий') }}</th>
                    <th scope="col">{{ __('Статус') }}</th>
                    <th scope="col">{{ __('Действия') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <th scope="row">{{ $report->id }}</th>
                        <td>{{ $report->user->name }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $report->photo->path) }}" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: contain;" alt="Photo">
                        </td>
                        <td>{{ $report->reason }}</td>
                        <td>{{ $report->custom_reason ?: '—' }}</td>
                        <td>{{ $report->status }}</td>
                        <td>
                            <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-warning btn-sm">{{ __('Редактировать') }}</a>
                            <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('Удалить') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection
