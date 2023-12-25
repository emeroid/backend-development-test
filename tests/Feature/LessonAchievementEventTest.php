<?php

use App\Listeners\LessonWatchedListener;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use \Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;


class LessonAchievementEventTest extends TestCase
{
    use RefreshDatabase;
    use MakesHttpRequests;

    private LessonWatchedListener $listener;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpBadges();

        $this->listener = app(LessonWatchedListener::class);
    }

    protected function setUpBadges()
    {
        // Create badges with their respective values
        Badge::factory()->create(['name' => 'Beginner', 'point' => 0]);
        Badge::factory()->create(['name' => 'Intermediate', 'point' => 1]);
        Badge::factory()->create(['name' => 'Advanced', 'point' => 2]);
        Badge::factory()->create(['name' => 'Master', 'point' => 3]);
    }

    // public function testFirstCommentAchievement()
    // {
    //     Event::fake();

    //     // Create a user for testing
    //     $user = User::factory()->create();

    //     // Create a comment for testing
    //     $lesson = Lesson::factory()->create(['title' => 'First Lesson Watched']);
    //     $user->lessons()->attach($lesson, ['watched' => true]);
    //     event(new LessonWatched($lesson, $user));
    //     Event::assertDispatched(LessonWatched::class);
    // }


    // public function testFiveLessonsAchievement()
    // {
    //     $user = User::factory()->create();

    //     Event::fake();
    //     // Dispatch three LessonWatched events
    //     for ($i = 0; $i < 5; $i++) {
    //         $lesson = Lesson::factory()->create(['title' => 'Fifth Lesson']);
    //         $user->lessons()->attach($lesson, ['watched' => true]);
    //         $this->dispatchLessonWatchedEvent($lesson, $user);
    //     }

    //     // Assert: Check if the AchievementUnlocked event is dispatched
    //     Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
    //         return $event->user->id === $user->id && $event->achievementName === '5 Lessons Watched';
    //     });

    //     // Assert: Check if the achievement is recorded in the database
    //     $this->assertDatabaseHas('achievements', [
    //         'user_id' => $user->id,
    //         'name' => '5 Lessons Watched',
    //     ]);

    //     // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
    //     Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
    //         return $event->user->id === $user->id && $event->badgeName === 'Intermediate';
    //     });
    // }

    public function testTenLessonsAchievement()
    {
        $user = User::factory()->create();

        Event::fake();
        // Dispatch three LessonWatched events
        for ($i = 0; $i < 10; $i++) {
            $lesson = Lesson::factory()->create(['title' => 'Tenth Lesson']);
            $user->lessons()->attach($lesson, ['watched' => true]);
            $this->dispatchLessonWatchedEvent($lesson, $user);
        }

        dd($user->badges()->pluck("name")->toArray());

        // Assert: Check if the AchievementUnlocked event is dispatched
        Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
            return $event->user->id === $user->id && $event->achievementName === '10 Lessons Watched';
        });

        // Assert: Check if the achievement is recorded in the database
        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '10 Lessons Watched',
        ]);

        // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
        Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
            return $event->user->id === $user->id && $event->badgeName === 'Advanced';
        });
    }

    // public function testTwentyFiveLessonsAchievement()
    // {
    //     $user = User::factory()->create();

    //     Event::fake();
    //     // Dispatch three LessonWatched events
    //     for ($i = 0; $i < 25; $i++) {
    //         $lesson = Lesson::factory()->create(['title' => 'Twenty Fifth Comment']);
    //         $user->lessons()->attach($lesson, ['watched' => true]);
    //         $this->dispatchLessonWatchedEvent($lesson, $user);
    //     }

    //     // Assert: Check if the AchievementUnlocked event is dispatched
    //     Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
    //         return $event->user->id === $user->id && $event->achievementName === '25 Lessons Watched';
    //     });

    //     // Assert: Check if the achievement is recorded in the database
    //     $this->assertDatabaseHas('achievements', [
    //         'user_id' => $user->id,
    //         'name' => '25 Lessons Watched',
    //     ]);

    //     // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
    //     Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
    //         return $event->user->id === $user->id && $event->badgeName === 'Master';
    //     });
    // }

    // public function testFiftyLessonsAchievement()
    // {
    //     $user = User::factory()->create();

    //     Event::fake();
    //     // Dispatch twenty LessonWatched events
    //     for ($i = 0; $i < 50; $i++) {
    //         $lesson = Lesson::factory()->create(['title' => 'Fifty Lesson']);
    //         $user->lessons()->attach($lesson, ['watched' => true]);
    //         $this->dispatchLessonWatchedEvent($lesson, $user);
    //     }

    //     // Assert: Check if the AchievementUnlocked event is dispatched
    //     Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
    //         return $event->user->id === $user->id && $event->achievementName === '50 Lessons Watched';
    //     });

    //     // Assert: Check if the achievement is recorded in the database
    //     $this->assertDatabaseHas('achievements', [
    //         'user_id' => $user->id,
    //         'name' => '50 Lessons Watched',
    //     ]);

    //     // Assert: Check if the BadgeUnlocked event is dispatched (if applicable)
    //     Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
    //         return $event->user->id === $user->id && $event->badgeName === 'Master';
    //     });
    // }

    private function dispatchLessonWatchedEvent(Lesson $lesson, User $user): void
    {
        $event = new LessonWatched($lesson, $user);
        event($event);
        $this->listener->handle($event);
    }
}