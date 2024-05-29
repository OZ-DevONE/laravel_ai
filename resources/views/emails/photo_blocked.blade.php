<!DOCTYPE html>
<html>
<head>
    <title>Ваше фото было заблокировано</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .photo-info {
            margin: 20px 0;
        }
        .photo-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-danger">Ваше фото было заблокировано</h1>
        <p>Здравствуйте, {{ $photo->user->name }},</p>
        <p>Ваше фото было заблокировано по следующей причине:</p>
        <p><strong>{{ $photo->block_description }}</strong></p>
        <div class="photo-info">
            <p><strong>Ссылка на фото:</strong> <a href="{{ url('/photo/' . $photo->id) }}">{{ url('/photo/' . $photo->id) }}</a></p>
            <p><strong>Пользователь:</strong> {{ $photo->user->name }} ({{ $photo->user->email }})</p>
            <p><strong>Путь к фото:</strong> {{ url('storage/' . $photo->path) }}</p>
            <p><strong>Описание:</strong> {{ $photo->description }}</p>
            <p><strong>Лайки:</strong> {{ $photo->likes_count }}</p>
            <p><strong>Дизлайки:</strong> {{ $photo->dislikes_count }}</p>
            <p><strong>Комментарии:</strong> {{ $photo->comments_count }}</p>
            <p><strong>Дата создания:</strong> {{ $photo->created_at }}</p>
            <p><strong>Дата обновления:</strong> {{ $photo->updated_at }}</p>
        </div>
    </div>
</body>
</html>
