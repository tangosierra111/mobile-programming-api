<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\UserMeetingProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $courses = Course::query()->where('is_active', true)->orderBy('name')->get();

        return $this->success(
            CourseResource::collection($courses)->resolve($request),
            'Daftar mata kuliah berhasil diambil.',
        );
    }

    public function dashboard(Request $request, Course $course): JsonResponse
    {
        abort_unless($course->is_active, 404);

        $course->load(['meetings.keywords', 'exams']);
        $progressByMeeting = UserMeetingProgress::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('meeting_id', $course->meetings->modelKeys())
            ->get()
            ->keyBy('meeting_id');

        $meetingItems = $course->meetings->map(function ($meeting) use ($progressByMeeting) {
            $progress = $progressByMeeting->get($meeting->id);
            $sortOrder = $meeting->meeting_number >= 8
                ? $meeting->sort_order + 1
                : $meeting->sort_order;

            return [
                'id' => 'meeting-'.$meeting->id,
                'source_id' => $meeting->id,
                'type' => 'meeting',
                'route_key' => 'meeting-'.$meeting->meeting_number,
                'meeting_number' => $meeting->meeting_number,
                'exam_type' => null,
                'slug' => $meeting->slug,
                'title' => $meeting->title,
                'accent_color' => $meeting->accent_color,
                'background_color' => $meeting->background_color,
                'status' => $meeting->status,
                'interactive_demo_key' => $meeting->interactive_demo_key,
                'sort_order' => $sortOrder,
                'keywords' => $meeting->keywords->pluck('keyword')->values(),
                'progress' => $progress ? $this->progressArray($progress) : null,
            ];
        });

        $examItems = $course->exams->map(function ($exam) {
            $isUts = $exam->exam_type === 'uts';

            return [
                'id' => 'exam-'.$exam->id,
                'source_id' => $exam->id,
                'type' => 'exam',
                'route_key' => 'exam-'.$exam->exam_type,
                'meeting_number' => null,
                'exam_type' => $exam->exam_type,
                'slug' => null,
                'title' => $exam->title,
                'accent_color' => $isUts ? '#2563EB' : '#0F766E',
                'background_color' => $isUts ? '#EAF4FF' : '#E6FFFA',
                'status' => $exam->status,
                'interactive_demo_key' => null,
                'sort_order' => $isUts ? 8 : 16,
                'keywords' => [$exam->exam_type, strtolower($exam->title), 'evaluasi'],
                'progress' => null,
            ];
        });

        $publishedMeetingIds = $course->meetings
            ->where('status', 'published')
            ->pluck('id');
        $completed = $progressByMeeting
            ->whereIn('meeting_id', $publishedMeetingIds)
            ->where('status', 'completed')
            ->count();
        $published = $publishedMeetingIds->count();

        return $this->success([
            'course' => (new CourseResource($course))->resolve($request),
            'items' => $meetingItems->concat($examItems)->sortBy('sort_order')->values(),
            'progress_summary' => [
                'published_meetings' => $published,
                'completed_meetings' => $completed,
                'progress_percent' => $published > 0
                    ? round($completed / $published * 100, 2)
                    : 0.0,
            ],
        ], 'Dashboard berhasil diambil.');
    }

    private function progressArray(UserMeetingProgress $progress): array
    {
        return [
            'id' => $progress->id,
            'user_id' => $progress->user_id,
            'meeting_id' => $progress->meeting_id,
            'status' => $progress->status,
            'progress_percent' => $progress->progress_percent,
            'started_at' => $progress->started_at?->toISOString(),
            'completed_at' => $progress->completed_at?->toISOString(),
            'last_opened_at' => $progress->last_opened_at?->toISOString(),
        ];
    }
}
