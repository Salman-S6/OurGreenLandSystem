<?php

namespace Modules\Extension\Http\Controllers\Api\V1;

use App\Helpers\NotifyHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Http\Requests\Answer\StoreAnswer;
use Modules\Extension\Http\Requests\Answer\UpdateAnswer;
use Modules\Extension\Models\Answer;
use Modules\Extension\Models\Question;

class AnswerController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnswer $request, Question $question)
    {
        try {
            $answer = Answer::create($request->validated());

            Cache::forget("question_{$question->id}");

            $user = $request->user();
            NotifyHelper::send($question->farmer, [
                'subject' => "New Answer for your Question",
                'message' => "{$user->name} answered your question \"{$question->title} .\"
                    with \" {$answer->answer_text} \""
            ]);

            return ApiResponse::success([
                "answer" => $answer
            ]);
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswer $request, Question $question, Answer $answer)
    {
        try {
            $answer->update($request->validated());

            Cache::forget("question_{$question->id}");

            $user = $request->user();
            NotifyHelper::send($question->farmer, [
                'subject' => "Updated Answer on your Question",
                'message' => "{$user->name} updated his answer on your question \"{$question->title} .\"
                    with \" {$answer->answer_text} \""
            ]);

            return ApiResponse::success([
                "answer" => $answer
            ]);
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question, Answer $answer)
    {
        try {
            Gate::authorize("delete", [Answer::class, $answer]);

            $answer->delete();
            Cache::forget("question_{$question->id}");
            
            return ApiResponse::success([
                "answer" => $answer
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }
}
