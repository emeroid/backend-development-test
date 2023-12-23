<?php

    use App\Models\Comment;
    use App\Models\User;
    use App\Models\Badge;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\DatabaseTransactions;
    use Illuminate\Support\Facades\Event;
    use Tests\TestCase;
    use App\Events\CommentWritten;
    use App\Events\AchievementUnlocked;
    use App\Events\BadgeUnlocked;
    use \Illuminate\Support\Facades\Log;
    use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;


    class CommentEventTest extends TestCase
    {
        use DatabaseTransactions;
        use MakesHttpRequests;

        public function setUp(): void
        {
            parent::setUp();

            $this->setUpBadges();
        }

        protected function setUpBadges()
        {
            // Create badges with their respective values
            Badge::factory()->create(['name' => 'Beginner', 'point' => 0]);
            Badge::factory()->create(['name' => 'Intermediate', 'point' => 4]);
            Badge::factory()->create(['name' => 'Advance', 'point' => 8]);
            Badge::factory()->create(['name' => 'Master', 'point' => 10]);
        }

        public function testFirstCommentAchievement()
        {
            Event::fake();

            // Create a user for testing
            $user = User::factory()->create();
    
            // Create a comment for testing
            $comment = Comment::factory()->create(['user_id' => $user->id, 'body' => 'First Comment']);
    
            event(new CommentWritten($comment));
            Event::assertDispatched(CommentWritten::class);
            
        }

    
        public function testThreeCommentsAchievement()
        {
            $user = User::factory()->create();
    
            Event::fake();
            // Dispatch three CommentWritten events
            for ($i = 0; $i < 3; $i++) {
                CommentWritten::dispatch(Comment::factory()->create(['user_id' => $user->id, 'body' => 'Third Comment']));
            }
    
            // Assert: Check if the AchievementUnlocked event is dispatched
            Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->achievementName === '3 Comments Written';
            });

            // Assert: Check if the achievement is recorded in the database
            $this->assertDatabaseHas('achievements', [
                'user_id' => $user->id,
                'name' => '3 Comments Written',
            ]);

            // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
            Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->badgeName === 'Intermediate';
            });
        }
    
        public function testFiveCommentsAchievement()
        {
            $user = User::factory()->create();
    
            Event::fake();
            // Dispatch three CommentWritten events
            for ($i = 0; $i < 5; $i++) {
                CommentWritten::dispatch(Comment::factory()->create(['user_id' => $user->id, 'body' => 'Fifth Comment']));
            }
    
            // Assert: Check if the AchievementUnlocked event is dispatched
            Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->achievementName === '5 Comments Written';
            });

            // Assert: Check if the achievement is recorded in the database
            $this->assertDatabaseHas('achievements', [
                'user_id' => $user->id,
                'name' => '5 Comments Written',
            ]);

            // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
            Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->badgeName === 'Advanced';
            });
        }

        public function testTenCommentsAchievement()
        {
            $user = User::factory()->create();
    
            Event::fake();
            // Dispatch three CommentWritten events
            for ($i = 0; $i < 10; $i++) {
                CommentWritten::dispatch(Comment::factory()->create(['user_id' => $user->id, 'body' => 'Ten Comment']));
            }
    
            // Assert: Check if the AchievementUnlocked event is dispatched
            Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->achievementName === '10 Comments Written';
            });

            // Assert: Check if the achievement is recorded in the database
            $this->assertDatabaseHas('achievements', [
                'user_id' => $user->id,
                'name' => '10 Comments Written',
            ]);

            // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
            Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->badgeName === 'Master';
            });
        }
    
        public function testTwentyCommentsAchievement()
        {
            $user = User::factory()->create();
    
            Event::fake();
            // Dispatch twenty CommentWritten events
            for ($i = 0; $i < 20; $i++) {
                CommentWritten::dispatch(Comment::factory()->create(['user_id' => $user->id, 'body' => 'Twenty Comment']));
            }
    
            // Assert: Check if the AchievementUnlocked event is dispatched
            Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->achievementName === '20 Comments Written';
            });

            // Assert: Check if the achievement is recorded in the database
            $this->assertDatabaseHas('achievements', [
                'user_id' => $user->id,
                'name' => '20 Comments Written',
            ]);

            // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
            Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
                return $event->user->id === $user->id && $event->badgeName === 'Master';
            });
        } 
    }
