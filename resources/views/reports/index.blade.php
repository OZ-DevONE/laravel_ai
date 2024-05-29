@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Мои жалобы') }}</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($reports->isEmpty())
        <div class="alert alert-warning" role="alert">
            {{ __('У вас нет поданных жалоб.') }}
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">{{ __('Фото') }}</th>
                    <th scope="col">{{ __('Причина') }}</th>
                    <th scope="col">{{ __('Комментарий') }}</th>
                    <th scope="col">{{ __('Статус') }}</th>
                    <th scope="col">{{ __('Действия') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $report->photo->path) }}" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: contain;" alt="Photo">
                        </td>
                        <td>{{ $report->reason }}</td>
                        <td>{{ $report->custom_reason ?: '—' }}</td>
                        <td>{{ $report->status }}</td>
                        <td>
                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
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
            {{ $reports->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
