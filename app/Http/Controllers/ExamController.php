<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExamController extends Controller
{

    public function index()
    {
        $userId = Auth::user()->id;

        $exams = Exam::withCount('questions')
            ->with(['results' => function ($query) {
                $query->where('user_id', Auth::user()->id);
            }])
            ->get();

        return Inertia::render('ExamList', [
            'exams' => $exams,
        ]);
    }


    public function show(Exam $exam)
    {
        return Inertia::render('Exam', [
            'exam' => $exam,
            'questions' => $exam->questions()->with('answers')->get(),
        ]);
    }
}
