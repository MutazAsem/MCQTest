<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ResultController extends Controller
{


    public function index()
    {
        $result = Result::with('exam')->where('user_id', Auth::user()->id)->get();

        return Inertia::render('ExamList', [
            'result' => $result,
        ]);
    }
    
    public function store(Request $request)
    {
        $score = 0;
        foreach ($request->answers as $question_id => $answer_id) {
            $correct = Answer::where('id', $answer_id)->where('is_correct', true)->exists();
            if ($correct) $score++;
        }

        Result::create([
            'user_id' => Auth::user()->id,
            'exam_id' => $request->exam_id,
            'score' => $score,
        ]);


        return redirect()->route('exam')->with('success', 'تم إنهاء الاختبار بنجاح! نتيجتك: ' . $score);
        // dd($request->all()); 
    }


}
