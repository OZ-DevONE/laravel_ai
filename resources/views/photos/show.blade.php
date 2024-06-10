@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $photo->path) }}" class="img-fluid" style="max-width: 100%; max-height: 500px; object-fit: contain;" alt="Photo">
        </div>
        <div class="col-md-6">
            <h1>{{ $photo->description }}</h1>
            <p>{{ __('Автор: ') . $photo->user->name }}</p>
            <p>{{ __('Дата: ') . $photo->created_at->format('d.m.Y') }}</p>
            <div class="d-flex justify-content-between align-items-center">
                @auth
                    <form method="POST" action="{{ route('photo.like', $photo->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">{{ __('Лайк') }}</button>
                    </form>
                    <span>{{ $photo->likes_count }}</span>
                    <form method="POST" action="{{ route('photo.dislike', $photo->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Дизлайк') }}</button>
                    </form>
                    <span>{{ $photo->dislikes_count }}</span>
                @else
                    <span>{{ __('Лайки:') }} {{ $photo->likes_count }}</span>
                    <span>{{ __('Дизлайки:') }} {{ $photo->dislikes_count }}</span>
                @endauth
            </div>

            @auth
                <!-- Icon and button for adding/removing from favorites -->
                <div class="mt-4">
                    @if($isFavorite)
                        <form method="POST" action="{{ route('photo.unfavorite', $photo->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-star text-warning"></i> {{ __('Удалить из избранных') }}
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('photo.favorite', $photo->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="far fa-star text-secondary"></i> {{ __('Добавить в избранные') }}
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Complaint button -->
                <div class="mt-4">
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

                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#complaintModal">{{ __('Пожаловаться') }}</button>
                </div>

                <!-- Complaint Modal -->
                <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="complaintModalLabel">{{ __('Пожаловаться') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('report.store', $photo->id) }}" onsubmit="return validateForm()">
                                    @csrf
                                    <div class="form-group mt-2">
                                        <label for="reason">{{ __('Причина') }}</label>
                                        <select name="reason" id="reason" class="form-control" required>
                                            <option value="" disabled selected>{{ __('Выберите причину') }}</option>
                                            <option value="Нарушение цензуры">{{ __('Нарушение цензуры') }}</option>
                                            <option value="Оскорбительный контент">{{ __('Оскорбительный контент') }}</option>
                                            <option value="Спам">{{ __('Спам') }}</option>
                                            <option value="Прочее">{{ __('Прочее') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2" id="custom-reason" style="display: none;">
                                        <label for="custom_reason">{{ __('Опишите причину') }}</label>
                                        <textarea name="custom_reason" id="custom_reason" class="form-control" rows="3" placeholder="{{ __('Ваш комментарий...') }}" maxlength="200"></textarea>
                                    </div>
                                    <div class="mt-2">
                                        <p>{{ __('Правила подачи жалобы:') }}</p>
                                        <ul>
                                            <li>{{ __('Выберите подходящую причину для жалобы.') }}</li>
                                            <li>{{ __('Если вы выбираете "Прочее", пожалуйста, подробно опишите причину жалобы.') }}</li>
                                            <li>{{ __('Не спамьте жалобами. Администратор рассмотрит вашу жалобу в ближайшее время.') }}</li>
                                            <li>{{ __('Статус вашей жалобы можно будет просмотреть в выпадающем меню пользователя в правом верхнем углу.') }}</li>
                                        </ul>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">{{ __('Отправить жалобу') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('reason').addEventListener('change', function() {
                        var customReason = document.getElementById('custom-reason');
                        if (this.value === 'Прочее') {
                            customReason.style.display = 'block';
                            document.getElementById('custom_reason').required = true;
                        } else {
                            customReason.style.display = 'none';
                            document.getElementById('custom_reason').required = false;
                        }
                    });

                    function validateForm() {
                        var reason = document.getElementById('reason').value;
                        var customReason = document.getElementById('custom_reason').value;

                        if (reason === 'Прочее' && customReason.trim() === '') {
                            alert('Пожалуйста, опишите причину жалобы.');
                            return false;
                        }

                        return true;
                    }
                </script>

                <h3 class="mt-4">{{ __('Оставить комментарий') }}</h3>
                <form method="POST" action="{{ route('photo.comment.store', $photo->id) }}">
                    @csrf
                    <div class="form-group mt-2">
                        <textarea name="content" class="form-control" rows="3" placeholder="{{ __('Ваш комментарий...') }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-2">{{ __('Отправить') }}</button>
                </form>
            @endauth

            <h3 class="mt-4">{{ __('Комментарии') }}</h3>
            @if($comments->isEmpty())
                <div class="alert alert-warning" role="alert">
                    {{ __('Комментариев пока нет.') }}
                </div>
            @else
                @foreach ($comments as $comment)
                    <div class="card mt-3">
                        <div class="card-body">
                            <p class="card-text">{{ $comment->content }}</p>
                            <p class="card-text"><small class="text-muted">{{ $comment->user->name }} - {{ $comment->created_at->format('d.m.Y H:i') }}</small></p>
                            @if($comment->user_id == auth()->id())
                                <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editCommentForm{{ $comment->id }}" aria-expanded="false" aria-controls="editCommentForm{{ $comment->id }}">
                                    {{ __('Редактировать') }}
                                </button>
                                <div class="collapse" id="editCommentForm{{ $comment->id }}">
                                    <form method="POST" action="{{ route('comment.update', $comment->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group mt-2">
                                            <textarea name="content" class="form-control" rows="3">{{ $comment->content }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">{{ __('Обновить') }}</button>
                                    </form>
                                </div>
                                <form method="POST" action="{{ route('comment.destroy', $comment->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mt-2">{{ __('Удалить') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center mt-4">
                    {{ $comments->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
