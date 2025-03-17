<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Арифметическая прогрессия</title>
</head>
<body>
    <h1>Арифметическая прогрессия</h1>
    <p>Заполните пропуск в прогрессии</p>
    <p>Прогрессия: 
        @foreach($progression as $value)
            {{ $value }} 
        @endforeach
    </p>

    <form action="/play" method="POST">
        @csrf
        <input type="hidden" name="missingIndex" value="{{ $missingIndex }}">
        <input type="hidden" name="step" value="{{ $step }}">
        <input type="hidden" name="start" value="{{ $progression[0] }}">
        <label for="playerAnswer">Ваш ответ:</label>
        <input type="text" name="playerAnswer" required>
        <br><br>
        <label for="playerName">Ваше имя:</label>
        <input type="text" name="playerName" required>
        <br><br>
        <button type="submit">Ответить</button>
    </form>

    @if(session('message'))
        <p>{{ session('message') }}</p>
    @endif
</body>
</html>