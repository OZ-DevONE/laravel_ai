@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Карточки с описанием сайта -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">{{ __('Преимущество 1') }}</div>
                <div class="card-body">
                    {{ __('Без ограничений на генерацию изображений') }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">{{ __('Преимущество 2') }}</div>
                <div class="card-body">
                    {{ __('Использование ИИ DALLE для генерации изображений') }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">{{ __('Преимущество 3') }}</div>
                <div class="card-body">
                    {{ __('Просмотр и взаимодействие с изображениями других пользователей') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Подробное описание возможностей сайта -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">{{ __('О сайте') }}</div>
                <div class="card-body">
                    <p>{{ __('Наш сайт предоставляет пользователям уникальную возможность создавать изображения с помощью ИИ DALLE. Вы можете генерировать изображения без каких-либо ограничений и делиться ими с другими пользователями.') }}</p>
                    <p>{{ __('В Галерее вы найдете работы других пользователей, где можно оставить комментарии и оценить изображения лайком или дизлайком.') }}</p>
                    <p>{{ __('Присоединяйтесь к нашему сообществу и наслаждайтесь бесконечными возможностями для творчества!') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
