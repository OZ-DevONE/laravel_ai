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
        <div class="form-group">
            <label for="status">{{ __('Статус') }}</label>
            <select name="status" id="status" class="form-control" required>
                @foreach(App\Models\Report::STATUSES as $status)
                    <option value="{{ $status }}" {{ $report->status == $status ? 'selected' : '' }}>{{ __($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mt-3" id="admin-comment" style="display: none;">
            <label for="admin_comment">{{ __('Комментарий администратора') }}</label>
            <textarea name="admin_comment" id="admin_comment" class="form-control" rows="3" maxlength="1000">{{ $report->admin_comment }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">{{ __('Обновить') }}</button>
    </form>
</div>

<script>
    document.getElementById('status').addEventListener('change', function() {
        var adminComment = document.getElementById('admin-comment');
        if (this.value === 'Ответ от администрации') {
            adminComment.style.display = 'block';
            document.getElementById('admin_comment').required = true;
        } else {
            adminComment.style.display = 'none';
            document.getElementById('admin_comment').required = false;
        }
    });

    // Show the admin comment field if the current status is "Ответ от администрации"
    if (document.getElementById('status').value === 'Ответ от администрации') {
        document.getElementById('admin-comment').style.display = 'block';
        document.getElementById('admin_comment').required = true;
    }
</script>
@endsection
