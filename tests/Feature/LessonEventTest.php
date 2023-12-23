<?php


    use App\Events\LessonEvent;
    use App\Models\Lesson;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Support\Facades\Event;
    use Tests\TestCase;
    use App\Events\LessonWatched;

    class LessonEventTest extends TestCase
    {
        use RefreshDatabase;

        public function testLessonEventFired()
        {
            // Create a user and a lesson
            $user = User::factory()->create();
            $lesson = Lesson::factory()->create();

            // Act: Trigger the event
            Event::fake();
            LessonWatched::dispatch($lesson, $user);

            // Assert: Check if the event was fired
            Event::assertDispatched(LessonWatched::class, function ($event) use ($lesson, $user) {
                return $event->lesson->id === $lesson->id && $event->user->id === $user->id;
            });
            
        }
    }
