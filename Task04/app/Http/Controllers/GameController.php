<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameResult;

class GameController extends Controller
{
    public function index()
    {
        $step = rand(1, 10);
        $start = rand(1, 10);
        $progression = [];

        for ($i = 0; $i < 10; $i++) {
            $progression[] = $start + $i * $step;
        }

        $missingIndex = rand(0, 9);
        $missingNumber = $progression[$missingIndex];
        $progression[$missingIndex] = '.';

        return view('game.index', [
            'progression' => $progression,
            'missingIndex' => $missingIndex,
            'step' => $step
        ]);
    }

    public function play(Request $request)
    {
        $playerAnswer = $request->input('playerAnswer');
        $missingIndex = $request->input('missingIndex');
        $step = $request->input('step');
        $start = $request->input('start');

        $start = (int)$request->input('start');
        $missingIndex = (int)$request->input('missingIndex');
        $step = (int)$request->input('step');

        $correctAnswer = $start + $missingIndex * $step;

        $gameResult = new GameResult();
        $gameResult->player_name = $request->input('playerName');
        $gameResult->correct = ($playerAnswer == $correctAnswer) ? 1 : 0;
        $gameResult->missing_number = $missingIndex;
        $gameResult->progression = implode(', ', range($start, $start + 9 * $step, $step));
        $gameResult->save();

        if ($playerAnswer == $correctAnswer) {
            return redirect('/')->with('message', 'Правильный ответ!');
        } else {
            return redirect('/')->with('message', 'Неправильный ответ! Правильный ответ: ' . $correctAnswer);
        }
    }

    public function showGames()
    {
        // Получение истории игр
        $games = GameResult::all();

        return view('game.history', ['games' => $games]);
    }
}
