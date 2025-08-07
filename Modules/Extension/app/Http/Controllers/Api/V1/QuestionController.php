<?php

namespace Modules\Extension\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Http\Requests\Question\StoreQuestion;
use Modules\Extension\Http\Requests\Question\UpdateQuestion;
use Modules\Extension\Models\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        try {
            $questions = Question::with("farmer", "answers.expert")
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

            return ApiResponse::success([
                "questions" => $questions
            ]);

        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestion $request)
    {
        try {
            $question = Question::create($request->validated());
            
            return ApiResponse::success([
                "question"=> $question
            ]);
        } catch (Exception $e) {
            return ApiResponse::error();
        }

    }

    /**
     * Show the specified resource.
     */
    public function show(Question $question)
    {
        try {
            $question = Cache::remember(
                "question_{$question->id}", 
                now()->addDay(), 
                function () use ($question) {
                    return $question->load(["farmer", "answers.expert"]);
                });

            return ApiResponse::success([
                "question"=> $question
            ]);
        } catch (Exception $e) {
            return ApiResponse::error();
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestion $request, Question $question)
    {
        try {
            $question->update($request->validated());
            
            Cache::forget("question_{$question->id}");

            return ApiResponse::success([
                "question"=> $question
            ]);
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        try {
            Gate::authorize("delete", $question);

            $question->delete();
            Cache::forget("question_{$question->id}");
            
            return ApiResponse::success([
                "question"=> $question
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }
}
