<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CommentWritten;
use App\Services\AchievementService;
use Illuminate\Support\Facades\Log;

class CommentWrittenListener
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
    public function handle(CommentWritten $event): void
    {
        $this->achievementService->unlockAchievement($event->comment->user, 'comments_written');
    }
}
