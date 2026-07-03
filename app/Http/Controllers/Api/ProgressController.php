<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateProgressRequest;
use App\Http\Resources\ProgressResource;
use App\Models\Meeting;
use App\Models\UserMeetingProgress;
use Illuminate\Http\JsonResponse;

class ProgressController extends ApiController
{
    public function update(
        UpdateProgressRequest $request,
        Meeting $meeting,
    ): JsonResponse {
        if ($meeting->status !== 'published') {
            return $this->error('Materi belum tersedia.', 403);
        }

        $validated = $request->validated();
        $status = $validated['status'];
        $now = now();

        $progress = UserMeetingProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'meeting_id' => $meeting->id,
            ],
            [
                'status' => $status,
                'progress_percent' => $validated['progress_percent'],
                'started_at' => $status === 'not_started' ? null : $now,
                'completed_at' => $status === 'completed' ? $now : null,
                'last_opened_at' => $now,
            ],
        );

        return $this->success(
            (new ProgressResource($progress))->resolve($request),
            'Progres belajar berhasil diperbarui.',
        );
    }
}
