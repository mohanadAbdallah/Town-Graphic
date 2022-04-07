<?php

namespace Tests\Feature\Api;

use App\Models\Badge\Badge;
use App\Models\Badge\BadgeTerm;
use App\Models\Event\Event;
use App\Models\Event\UserEvent;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizQuestionChoice;
use App\Models\Quiz\UserQuiz;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    /**
     * @test
     */
    public function user_must_be_singed_in_to_get_events()
    {
        $user = User::factory()->create();
        $response = $this->get('/api/events');

        $response->assertStatus(401);// not authorized access
    }

    /**
     * @test
     */
    public function user_can_show_events()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->get('/api/events');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_can_show_specific_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->get('/api/events/' . $event->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_join_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $currentPoints = $user->userStatics->events_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/join');

        $this->assertDatabaseHas('user_events', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'events_count' => $currentPoints + 1,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function user_cant_join_event_that_not_found()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/0/join');

        $response->assertJson(['message' => 'Event not found'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_join_more_than_one_to_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        //firstly user should be joined to an event
        $joinResponse = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/join');

        // if joining done with 200 status code, then test join the same event
        if ($joinResponse->assertStatus(200)) {

            $user = User::find($user->id);

            $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/join');

            $response->assertJson(['message' => 'User already joined'])
                ->assertStatus(422);
        }
    }

    /**
     * @test
     */
    public function user_left_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $event_id = $event->id;
        //firstly user should be joined to an event
        $joinResponse = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/join');

        // if joining done with 200 status code, then test leaving the event
        if ($joinResponse->assertStatus(200)) {

            $user = User::find($user->id);
            $currentPoints = $user->userStatics->events_count ?? 0;

            $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/left');
            $this->assertDatabaseMissing('user_events', [
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);
            $this->assertDatabaseHas('user_statics', [
                'user_id' => $user->id,
                'events_count' => $currentPoints - 1,
            ]);
            $response->assertStatus(200);
        }
    }

    /**
     * @test
     */
    public function user_cant_left_from_completed_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $event_id = $event->id;
        //firstly user should be joined to an event
        $joinResponse = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/join');

        // if joining done with 200 status code, then complete the event
        if ($joinResponse->assertStatus(200)) {

            $user = User::find($user->id);

            $checkinResponse = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/checkin');

            // if completing done with 200 status code, then test leaving the event
            if ($checkinResponse->assertStatus(200)) {

                $user = User::find($user->id);
                $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/left');

                $response->assertJson(['message' => 'User already completed this event and cant leave it.'])
                    ->assertStatus(422);

            }
        }
    }

    /**
     * @test
     */
    public function user_cant_left_event_if_it_already_left()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $event_id = $event->id;
        //firstly user should be joined to an event
        $joinResponse = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/join');

        // if joining done with 200 status code, then leave the event
        if ($joinResponse->assertStatus(200)) {

            $user = User::find($user->id);

            $leftResponse = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/left');

            // if leaving done with 200 status code, then test leaving another time from the event
            if ($leftResponse->assertStatus(200)) {
                $user = User::find($user->id);

                $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event_id . '/left');

                $response->assertJson(['message' => 'User already left event'])
                    ->assertStatus(422);
            }

        }
    }

    /**
     * @test
     */
    public function user_cant_left_event_that_not_found()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/0/left');

        $response->assertJson(['message' => 'Event not found'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_must_pass_role_to_choose_event_role()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner']
        ]);
        $userEvent = UserEvent::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/role');
        $response->assertStatus(422);

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/role', [
            'role' => $event->roles[0]
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function event_must_be_found_to_choose_event_role()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/0/role', [
            'role' => 'Planter'
        ]);
        $response->assertJson(['message' => 'Event not found'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_must_be_joined_to_event_to_choose_event_role()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner']
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/role', [
            'role' => $event->roles[0]
        ]);
        $response->assertJson(['message' => 'User not joined this event yet.'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function role_must_be_valid_to_choose_event_role()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner']
        ]);
        $userEvent = UserEvent::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $invalidRole = Str::random(5);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/role', [
            'role' => $invalidRole
        ]);
        $response->assertJson(['message' => 'Event does not have role: ' . $invalidRole])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function event_must_be_found_to_checkin()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/0/checkin');
        $response->assertJson(['message' => 'Event not found'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_must_be_joined_to_checkin_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner']
        ]);

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');

        $response->assertJson(['message' => 'User not joined this event yet.'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function event_must_be_not_completed_to_checkin_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner']
        ]);
        $userEvent = UserEvent::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 1,
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');

        $response->assertJson(['message' => 'User is already completed this event.'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_complete_event_when_checkin()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner']
        ]);
        $userEvent = UserEvent::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');

        $this->assertDatabaseHas('user_events', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 1,
        ]);
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function user_complete_event_and_get_points()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner'],
            'points'=>2
        ]);
        $userEvent = UserEvent::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');

        $this->assertDatabaseHas('user_events', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'points_count' => $currentPoints + $event->points,
        ]);
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'source' => 'App\Models\Event\Event',
            'item_id' => $event->id,
            'points' => $event->points,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_event_and_get_badge()
    {
        $user =  User::factory()->create();
        $event = Event::factory()->create([
            'roles' => ['Planter', 'Cleaner'],
            'points'=>2
        ]);
        $badge = Badge::factory()->create();
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Event::class,
            'item_id' => $event->id
        ]);
        $userEvent = UserEvent::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }
 
    /**
     * @test
     */
    public function user_complete_event_and_get_specific_activity_badge()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $event = Event::factory()->create();
        $quiz = Quiz::factory()->create([
            'points' => 2
        ]);
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id
        ]);
        $choice = QuizQuestionChoice::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'is_answer' => 1
        ]);
        $anotherQuestion = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id
        ]);
        $anotherChoice = QuizQuestionChoice::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $anotherQuestion->id,
            'is_answer' => 1
        ]);
        $userJoinQuiz = UserQuiz::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'status' => 0
        ]);
        $badge = Badge::factory()->create([
            'type'=>3
        ]);
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Event::class,
            'item_id' => $event->id
        ]);
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Quiz::class,
            'item_id' => $quiz->id
        ]);

        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $anotherQuestion->id . '/choice/' . $anotherChoice->id);
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/join');
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');
        $user = User::find($user->id);

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }
}
