<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateAnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\AttemptResource;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamAttemptController extends ApiController
{
    public function updateAnswer(
        UpdateAnswerRequest $request,
        ExamAttempt $attempt,
        ExamQuestion $question,
    ): JsonResponse {
        if ($attempt->user_id !== $request->user()->id) {
            return $this->error('Anda tidak memiliki akses ke percobaan ini.', 403);
        }
        if ($attempt->status !== 'in_progress') {
            return $this->error('Percobaan ujian sudah ditutup.', 409);
        }
        if ($question->exam_id !== $attempt->exam_id) {
            return $this->error('Soal tidak termasuk dalam ujian ini.', 422);
        }

        $answer = ExamAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            $request->validated(),
        );

        return $this->success(
            (new AnswerResource($answer))->resolve($request),
            'Jawaban berhasil disimpan.',
        );
    }

    public function submit(Request $request, ExamAttempt $attempt): JsonResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            return $this->error('Anda tidak memiliki akses ke percobaan ini.', 403);
        }
        if ($attempt->status !== 'in_progress') {
            return $this->error('Percobaan ujian sudah pernah dikirim.', 409);
        }

        $attempt->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return $this->success(
            (new AttemptResource($attempt->fresh()))->resolve($request),
            'Jawaban ujian berhasil dikirim.',
        );
    }
}
