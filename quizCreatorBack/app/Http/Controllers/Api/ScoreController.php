<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // Sauvegarder un nouveau score
    public function store(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'answers_ids' => 'required|array',
            'answers_ids.*' => 'integer|exists:answers,id'
        ]);

        // Calcul sécurisé du score côté serveur
        $score = \App\Models\Answer::whereIn('id', $request->answers_ids)
            ->where('verite', true)
            ->whereHas('question', function ($q) use ($request) {
                $q->where('quiz_id', $request->quiz_id);
            })
            ->count();

        $record = Score::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $request->quiz_id,
            'score' => $score,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Score enregistré avec succès !',
            'data' => $record
        ], 201);
    }

    // Récupérer les meilleurs scores pour un quiz spécifique
    public function bestScores($quiz_id)
    {
        $scores = Score::with('user:id,name')
            ->where('quiz_id', $quiz_id)
            ->orderBy('score', 'desc')
            ->take(10) // Les 10 meilleurs
            ->get();

        return response()->json([
            'status' => true,
            'scores' => $scores
        ]);
    }
}
