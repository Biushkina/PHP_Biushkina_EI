<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/db.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    $response->getBody()->write('Главная страница');
    return $response;
});

$app->get('/games', function ($request, $response) {
    $db = Database::getInstance();
    $result = $db->query("SELECT * FROM game_results");

    $games = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $games[] = $row;
    }

    $response->getBody()->write(json_encode($games));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/games', function ($request, $response) {
    $data = json_decode($request->getBody(), true);
    $playerName = $data['name'] ?? 'Игрок';

    $start = rand(1, 20);
    $step = rand(1, 10);
    $progression = [];
    for ($i = 0; $i < 10; $i++) {
        $progression[] = $start + ($i * $step);
    }

    $missingIndex = rand(0, 9);
    $missingNumber = $progression[$missingIndex];
    $progression[$missingIndex] = '.';

    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT INTO game_results (player_name, correct, missing_number, progression) VALUES (:player_name, 0, :missing_number, :progression)");
    $stmt->bindValue(':player_name', $playerName, SQLITE3_TEXT);
    $stmt->bindValue(':missing_number', $missingNumber, SQLITE3_INTEGER);
    $stmt->bindValue(':progression', implode(' ', $progression), SQLITE3_TEXT);
    $stmt->execute();

    $gameData = [
        'progression' => $progression,
        'missingIndex' => $missingIndex,
        'missingNumber' => $missingNumber
    ];

    $response->getBody()->write(json_encode($gameData));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/games/check', function ($request, $response) {
    $data = json_decode($request->getBody(), true);
    $playerName = $data['name'] ?? 'Игрок';
    $playerAnswer = $data['answer'];
    $progressionData = $data['progressionData'];

    $isCorrect = ($playerAnswer == $progressionData['missingNumber']);

    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE game_results SET correct = :correct WHERE player_name = :player_name AND missing_number = :missing_number");
    $stmt->bindValue(':player_name', $playerName, SQLITE3_TEXT);
    $stmt->bindValue(':correct', $isCorrect ? 1 : 0, SQLITE3_INTEGER);
    $stmt->bindValue(':missing_number', $progressionData['missingNumber'], SQLITE3_INTEGER);
    $stmt->execute();

    $message = $isCorrect ? "Правильно!" : "Неправильно! Правильный ответ: " . $progressionData['missingNumber'];
    $response->getBody()->write(json_encode(['message' => $message]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();