<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with('user')->withCount('questions');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $quizzes = $query->latest()->get();

        return response()->json([
            'status' => true,
            'quiz' => $quizzes
        ]);
    }

    public function show($id)
    {
        $quiz = Quiz::with(['questions.answers', 'user'])->find($id);

        if (!$quiz) {
            return response()->json(['status' => false, 'message' => 'Quiz not found'], 404);
        }

        return response()->json([
            'status' => true,
            'quiz' => $quiz
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.libelleQ' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.libelleR' => 'required|string',
            'questions.*.answers.*.verite' => 'required|boolean',
        ]);

        // Vérification logique : chaque question doit avoir au moins une vraie réponse
        foreach ($request->questions as $index => $question) {
            $hasTrue = collect($question['answers'])->contains('verite', true);
            $hasFalse = collect($question['answers'])->contains('verite', false);

            if (!$hasTrue || !$hasFalse) {
                return response()->json([
                    'message' => "La question " . ($index + 1) . " doit avoir au moins une réponse vraie et une réponse fausse."
                ], 422);
            }
        }

        $quiz = Quiz::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'cover' => 'default.png' // Default cover
        ]);

        foreach ($request->questions as $qData) {
            $question = $quiz->questions()->create([
                'libelleQ' => $qData['libelleQ']
            ]);

            foreach ($qData['answers'] as $aData) {
                $question->answers()->create([
                    'libelleR' => $aData['libelleR'],
                    'verite' => $aData['verite']
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Quiz created successfully',
            'quiz' => $quiz->load('questions.answers')
        ], 201);
    }

    public function myQuizzes(Request $request)
    {
        $quizzes = Quiz::withCount('questions')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'quiz' => $quizzes
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz introuvable.'], 404);
        }

        if ($quiz->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $quiz->delete();

        return response()->json(['message' => 'Quiz supprimé avec succès.']);
    }
}
