<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\AchievementService;
use App\Events\LessonWatched;

class LessonWatchedListener
{
    protected AchievementService $achievementService;
    /**
     * Create the event listener.
     */
    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $this->achievementService->unlockAchievement($event->user, 'lessons_watched');
    }
}
