@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Все жалобы') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                    <th scope="col">{{ __('Фото') }}</th>
                    <th scope="col">{{ __('Количество жалоб') }}</th>
                    <th scope="col">{{ __('Дата и время') }}</th>
                    <th scope="col">{{ __('Действия') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports->groupBy('photo_id') as $photoId => $groupedReports)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $groupedReports->first()->photo->path) }}" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: contain;" alt="Photo">
                        </td>
                        <td>
                            {{ $groupedReports->sum('complaint_count') }}
                        </td>
                        <td>
                            {{ $groupedReports->first()->created_at->format('d.m.Y H:i:s') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.reports.show', $groupedReports->first()->id) }}" class="btn btn-info btn-sm">{{ __('Просмотреть') }}</a>
                            <a href="{{ route('admin.reports.edit', $groupedReports->first()->id) }}" class="btn btn-warning btn-sm">{{ __('Редактировать') }}</a>
                            <form action="{{ route('admin.reports.destroy', $groupedReports->first()->id) }}" method="POST" class="d-inline">
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
