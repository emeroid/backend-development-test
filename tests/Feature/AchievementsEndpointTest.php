<?php

use App\Models\User;
use App\Models\Achievement; // Import Achievement model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\AchievementService;

class AchievementsEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function testAchievementsEndpoint()
    {
        // Create a user
        $user = User::factory()->create();

        //create badges
        
        // Add achievements to the user
        Achievement::factory()->create(['user_id' => $user->id, 'name' => 'First Lesson Watched']);
        Achievement::factory()->create(['user_id' => $user->id, 'name' => '3 Comments Written']);

        $achievements = new AchievementService;
        // Act: Request the achievements endpoint
        $response = $this->get("/users/{$user->id}/achievements");

        // Assert: Check the response structure and content
        $response->assertStatus(200)
            ->assertJsonStructure([
                'unlocked_achievements',
                'next_available_achievements',
                'current_badge',
                'next_badge',
                'remaing_to_unlock_next_badge',
            ])->assertJson([
                'unlocked_achievements' => $achievements->getUnlockedAchievements($user),
                'next_available_achievements' => $achievements->getNextAvailableAchievements($user),
                'current_badge' => $achievements->getCurrentBadge($user),
                'next_badge' => $achievements->getNextBadge($user),
                'remaing_to_unlock_next_badge' => $achievements->getRemainingToUnlockNextBadge($user),
            ]);
    }
}
