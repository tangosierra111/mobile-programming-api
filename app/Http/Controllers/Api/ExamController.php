<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AttemptResource;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends ApiController
{
    public function show(Request $request, Exam $exam): JsonResponse
    {
        if ($exam->status !== 'published' && $request->user()->role === 'student') {
            return $this->error('Ujian belum tersedia.', 403);
        }

        $exam->load(['course', 'questions']);

        return $this->success(
            (new ExamResource($exam))->resolve($request),
            'Ujian berhasil diambil.',
        );
    }

    public function startAttempt(Request $request, Exam $exam): JsonResponse
    {
        if ($exam->status !== 'published') {
            return $this->error('Ujian belum tersedia.', 409);
        }

        $existing = ExamAttempt::query()
            ->where('exam_id', $exam->id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            return $this->error('Masih ada percobaan ujian yang aktif.', 409);
        }

        $attempt = DB::transaction(function () use ($request, $exam) {
            $nextNumber = ExamAttempt::query()
                ->where('exam_id', $exam->id)
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->max('attempt_number') + 1;

            return ExamAttempt::create([
                'exam_id' => $exam->id,
                'user_id' => $request->user()->id,
                'attempt_number' => $nextNumber,
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        });

        return $this->success(
            (new AttemptResource($attempt))->resolve($request),
            'Percobaan ujian berhasil dimulai.',
            201,
        );
    }
}
