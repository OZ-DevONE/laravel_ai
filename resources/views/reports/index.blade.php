@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Мои жалобы') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->has('limit'))
        <div class="alert alert-danger">
            {{ $errors->first('limit') }}
        </div>
    @endif

    <form method="GET" action="{{ route('reports.index') }}" class="mb-4">
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
                <button type="submit" class="btn btn-primary">{{ __('Фильтр') }}</button>
            </div>
        </div>
    </form>

    @if($reports->isEmpty())
        <div class="alert alert-warning" role="alert">
            {{ __('У вас нет поданных жалоб.') }}
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">{{ __('ID') }}</th>
                    <th scope="col">{{ __('Фото') }}</th>
                    <th scope="col">{{ __('Причина') }}</th>
                    <th scope="col">{{ __('Комментарий') }}</th>
                    <th scope="col">{{ __('Статус') }}</th>
                    <th scope="col">{{ __('Комментарий Администрации') }}</th>
                    <th scope="col">{{ __('Действия') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <th scope="row">{{ $report->id }}</th>
                        <td>
                            <img src="{{ asset('storage/' . $report->photo->path) }}" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: contain;" alt="Photo">
                        </td>
                        <td>{{ $report->reason }}</td>
                        <td>{{ $report->custom_reason ?: '—' }}</td>
                        <td>{{ $report->status }}</td>
                        <td>
                            @if($report->admin_comment)
                                <span>{{ $report->admin_comment }}</span>
                            @else
                                <span>{{ __('—') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($report->status == 'Новая')
                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Удалить') }}</button>
                                </form>
                            @else
                                <span>{{ __('Удалить нельзя') }}</span>
                            @endif
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
