<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Display;
use Illuminate\Http\JsonResponse;

class DisplayContentsController extends Controller
{
    public function __invoke(string $uniqueCode): JsonResponse
    {
        $display = Display::where('unique_code', $uniqueCode)->firstOrFail();

        $display->touchLastSeen();

        $regularContents = collect();

        if ($display->playlist_id) {
            $regularContents = $display->playlist->contents()
                ->active()
                ->scheduledToday()
                ->where('is_priority', false)
                ->get();
        }

        // Priority/emergency content interrupts every active display,
        // regardless of which playlist is assigned to it.
        $priorityContents = Content::query()
            ->active()
            ->scheduledToday()
            ->where('is_priority', true)
            ->orderBy('order')
            ->get();

        $mapContent = fn (Content $content) => [
            'id' => $content->id,
            'title' => $content->title,
            'type' => $content->type,
            'file_url' => $content->file_url,
            'text_body' => $content->text_body,
            'background_hex' => $content->background_hex,
            'duration' => $content->duration,
            'is_priority' => $content->is_priority,
        ];

        return response()->json([
            'display' => [
                'name' => $display->name,
                'orientation' => $display->orientation,
            ],
            'contents' => $regularContents->values()->map($mapContent),
            'priority_contents' => $priorityContents->values()->map($mapContent),
            'server_time' => now()->toIso8601String(),
        ]);
    }
}
