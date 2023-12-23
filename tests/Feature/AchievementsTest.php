<?php 

    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Support\Facades\Event;
    use Tests\TestCase;
    use App\Events\AchievementUnlocked;
    use App\Events\BadgeUnlocked;

    class AchievementsTest extends TestCase
    {
        use RefreshDatabase;

        public function testUnlockingAchievements()
        {
            // Create a user
            $user = User::factory()->create();

            Event::fake();
            // ... Trigger events here ...

            // Assert: Check if the AchievementUnlocked and BadgeUnlocked events were fired
            Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id;
            });

            Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id;
            });
        }
    }
