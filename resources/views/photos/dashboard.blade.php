@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <button class="btn btn-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                {{ __('Фильтры') }}
            </button>
            <div class="collapse" id="filterCollapse">
                <form method="GET" action="{{ route('photo.index') }}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="user_name" class="form-label">{{ __('Имя автора') }}</label>
                            <input type="text" class="form-control" id="user_name" name="filter[user.name]" value="{{ request('filter.user.name') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="description" class="form-label">{{ __('Описание') }}</label>
                            <input type="text" class="form-control" id="description" name="filter[description]" value="{{ request('filter.description') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="created_at" class="form-label">{{ __('Дата создания') }}</label>
                            <input type="date" class="form-control" id="created_at" name="filter[created_at]" value="{{ request('filter.created_at') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sort_date" class="form-label">{{ __('Сортировка по дате') }}</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownSortDate" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('Выберите сортировку') }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownSortDate">
                                    <li><a class="dropdown-item" href="#" onclick="setSort('created_at')">{{ __('По дате (новые)') }}</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="setSort('-created_at')">{{ __('По дате (старые)') }}</a></li>
                                </ul>
                            </div>
                            <input type="hidden" id="sort" name="sort" value="{{ request('sort') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sort_other" class="form-label">{{ __('Сортировка по другим параметрам') }}</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownSortOther" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('Выберите сортировку') }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownSortOther">
                                    <li><a class="dropdown-item" href="#" onclick="setSort('likes_count')">{{ __('По лайкам (убыв.)') }}</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="setSort('-likes_count')">{{ __('По лайкам (возр.)') }}</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="setSort('comments_count')">{{ __('По комментариям (убыв.)') }}</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="setSort('-comments_count')">{{ __('По комментариям (возр.)') }}</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="setSort('dislikes_count')">{{ __('По дизлайкам (убыв.)') }}</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="setSort('-dislikes_count')">{{ __('По дизлайкам (возр.)') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">{{ __('Применить') }}</button>
                            <a href="{{ route('photo.index') }}" class="btn btn-secondary">{{ __('Очистить') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        @if($photos->isEmpty())
            <div class="col-md-10">
                <div class="alert alert-warning text-center" role="alert">
                    {{ __('Фотографии не найдены.') }}
                </div>
            </div>
        @else
            @foreach ($photos as $photo)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <a href="{{ route('photo.show', $photo->id) }}">
                            <img data-src="{{ asset('storage/' . $photo->path) }}" class="lazyload card-img-top" alt="Photo">
                        </a>
                        <div class="card-body">
                            <p class="card-text">{{ __('Автор: ') . $photo->user->name }}</p>
                            <p class="card-text"><small class="text-muted">{{ __('Дата: ') . $photo->created_at->format('d.m.Y') }}</small></p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            {{ $photos->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

<script>
    function setSort(value) {
        document.getElementById('sort').value = value;
        document.querySelector('form').submit();
    }
</script>
