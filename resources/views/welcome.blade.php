@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @else
        <!-- Слайдер с изображениями -->
        <div id="imageSlider" class="carousel slide mb-4" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{asset('img/img.png')}}" class="d-block w-100" alt="Example Image 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Фото сгенерированное ИИ</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{asset('img/8890cd5e-fdc5-4df9-ad58-250f3e23fcc0.png')}}" class="d-block w-100" alt="Example Image 2">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Фото сгенерированное ИИ</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{asset('img/e1e17a07-5878-4575-a437-98edc30d7fba.png')}}" class="d-block w-100" alt="Example Image 3">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Фото сгенерированное ИИ</h5>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#imageSlider" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Предыдущий</span>
            </a>
            <a class="carousel-control-next" href="#imageSlider" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Следующий</span>
            </a>
        </div>

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

        <!-- Сокращенное описание ИИ для генерации изображений -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">{{ __('ИИ для генерации изображений') }}</div>
                    <div class="card-body">
                        <p>{{ __('ИИ для генерации изображений, или нейросети для создания картинок - это тип программного обеспечения, основанный на нейронных сетях глубокого обучения. Они обучаются на большом наборе данных (изображений) и затем могут генерировать новые изображения на основе текстовых запросов пользователя.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Подробное описание ИИ для генерации изображений -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">{{ __('Подробное описание ИИ для генерации изображений') }}</div>
                    <div class="card-body">
                        <p>{{ __('Принцип работы: Нейросети для генерации фото прогнозируют значения пикселей на основе шаблонов, полученных из базы данных изображений, и создают новый результат - картинку, соответствующую текстовому описанию. Пользователь вводит запрос на естественном языке, а нейросеть комбинирует известные ей визуальные элементы для создания изображения.') }}</p>
                        <p>{{ __('Виды и возможности: Существуют разные виды нейросетей для генерации изображений: создающие картинки полностью с нуля по текстовому запросу, накладывающие визуальные эффекты на существующие фото (стилизация под художников, замена объектов и т.д.). Нейросети позволяют быстро и эффективно создавать персонализированные изображения, в том числе сюрреалистические и креативные, а также редактировать и миксовать сгенерированные фото. Они хорошо справляются с генерацией реалистичных лиц людей.') }}</p>
                        <p>{{ __('Важно: Злоупотребление такими технологиями может привести к распространению дезинформации и подделок. Важно использовать ИИ для генерации изображений ответственно и этично.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
