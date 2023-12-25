<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AchievementService;

class AchievementsController extends Controller
{
    public function __construct(private AchievementService $achievementService)
    {
    }
    
    public function index(User $user)
    {
        try{
            return response()->json([
                'unlocked_achievements' => $this->achievementService->getUnlockedAchievements($user),
                'next_available_achievements' => $this->achievementService->getNextAvailableAchievements($user),
                'current_badge' => $this->achievementService->getCurrentBadge($user),
                'next_badge' => $this->achievementService->getNextBadge($user),
                'remaing_to_unlock_next_badge' =>  $this->achievementService->getRemainingToUnlockNextBadge($user)
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'status' => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
