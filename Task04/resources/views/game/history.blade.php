<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История игр</title>
</head>
<body>
    <h1>История игр</h1>
    <table>
        <thead>
            <tr>
                <th>Игрок</th>
                <th>Дата</th>
                <th>Правильный ответ</th>
                <th>Пропущенное число</th>
                <th>Прогрессия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($games as $game)
                <tr>
                    <td>{{ $game->player_name }}</td>
                    <td>{{ $game->game_date }}</td>
                    <td>{{ $game->correct ? 'Да' : 'Нет' }}</td>
                    <td>{{ $game->missing_number }}</td>
                    <td>{{ $game->progression }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
