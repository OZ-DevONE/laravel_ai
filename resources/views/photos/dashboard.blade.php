@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if($photos->isEmpty())
            <div class="col-md-8">
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
