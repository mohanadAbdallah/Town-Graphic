<?php

namespace Tests\Feature\Api;

use App\Models\Badge\Badge;
use App\Models\Badge\BadgeTerm;
use App\Models\Challenge\Challenge;
use App\Models\Challenge\ChallengeStage;
use App\Models\Challenge\UserChallenge;
use App\Models\Challenge\UserChallengeStage;
use App\Models\Event\Event;
use App\Models\User\User;
use Carbon\Carbon;
use Faker\Factory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChallengeControllerTest extends TestCase
{
    /**
     * @test
     */
    public function user_must_be_singed_in_to_get_challenges()
    {
        $user = User::factory()->create();
        $response = $this->get('/api/challenges');

        $response->assertStatus(401);// not authorized access
    }

    /**
     * @test
     */
    public function user_can_show_challenges()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->get('/api/challenges');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_can_show_specific_challenge()
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->get('/api/challenges/' . $challenge->id);

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function user_join_challenge()
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create();
        $currentPoints = $user->userStatics->challenges_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $this->assertDatabaseHas('user_challenges', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'challenges_count' => $currentPoints + 1,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function user_get_expired_message_when_joining_challenge()
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create([
            'start_date' => Carbon::today()->subDays(2),
            'end_date' => Carbon::today()->subDays(1),
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $response->assertStatus(422)
            ->assertJson(['message' => 'Challenge expired!']);
    }

    /**
     * @test
     */
    public function user_get_already_joined_message_when_joining_challenge()
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create();
        $userChallenge = UserChallenge::factory()->create([
            'challenge_id' => $challenge->id,
            'user_id' => $user->id,
            'status' => 0
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $response->assertStatus(422)
            ->assertJson(['message' => 'User already joined']);
    }

    /**
     * @test
     */
    public function user_get_challenge_not_found_when_joining_challenge()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/0/join');

        $response->assertStatus(422)
            ->assertJson(['message' => 'Challenge not found']);
    }

    /**
     * @test
     */
    public function user_get_stage_not_found_when_complete_stage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/0/complete');

        $response->assertStatus(422)
            ->assertJson(['message' => 'Stage not found']);
    }

    /**
     * @test
     */
    public function user_complete_one_action_stage()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 1,
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'item_id' => $stage->id,
            'points' => $stage->points,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'points_count' => $currentPoints + $stage->points,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_multi_action_stage_from_the_first_time_with_count_equal_one()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 1,
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'item_id' => $stage->id,
            'points' => $stage->points,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'points_count' => $currentPoints + $stage->points,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_multi_action_stage_from_the_first_time_with_count_equal_3()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 3,
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 0,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_multi_action_stage_two_times_in_same_day_with_interval_equal_one()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 3,
            'interval' => 1,
            'range' => 7,
        ]);
        $userChallengeStage = UserChallengeStage::factory()->create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 0,
            'data_json' => [['action' => 1, 'dateTime' => Carbon::now()]]
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 0,
        ]);
        $response->assertJson(['message' => 'You cant complete more than ' . $stage->interval . ' action in one day'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_complete_multi_action_stage_two_times_in_same_day_with_interval_equal_two()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 3,
            'interval' => 2,
            'range' => 7,
        ]);
        $userChallengeStage = UserChallengeStage::factory()->create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 0,
            'data_json' => [['action' => 1, 'dateTime' => Carbon::now()]]
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 0,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_multi_action_stage_two_times_in_same_day_with_interval_equal_two_and_count_equal_two()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 2,
            'interval' => 2,
            'range' => 7,
        ]);
        $userChallengeStage = UserChallengeStage::factory()->create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 0,
            'data_json' => [['action' => 1, 'dateTime' => Carbon::now()]]
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 1,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_count_action_stage_but_not_all_count()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 20,
            'type' => 1,
            'action_type' => 3,
            'count' => 10,
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;
        $value = 5;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 0,
        ]);
        $pointsReceived = ($stage->points / $stage->count) * $value;
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'item_id' => $stage->id,
            'points' => $pointsReceived,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'points_count' => $currentPoints + $pointsReceived,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_count_action_stage_in_second_time()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 20,
            'type' => 1,
            'action_type' => 3,
            'count' => 10,
        ]);
        $value = 5;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);
        $user = User::find($user->id);

        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 1,
        ]);
        $pointsReceived = ($stage->points / $stage->count) * $value;
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'item_id' => $stage->id,
            'points' => $pointsReceived,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'points_count' => $currentPoints + $pointsReceived,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cant_complete_count_action_stage_that_fulled()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 20,
            'type' => 1,
            'action_type' => 3,
            'count' => 10,
        ]);
        $value = 5;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);
        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);

        $response->assertJson(['message' => 'User already Completed this stage'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_complete_count_action_stage_value_required()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 20,
            'type' => 1,
            'action_type' => 3,
            'count' => 10,
        ]);
        $value = 5;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
        ]);
        $response->assertJson(['message' => 'value field is required'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_complete_count_action_stage_value_must_be_equal_or_small_than_required()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 20,
            'type' => 1,
            'action_type' => 3,
            'count' => 10,
        ]);
        $value = 15;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);
        $response->assertJson(['message' => 'value must be equal or smaller than required'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_complete_count_action_stage_value_must_be_equal_or_small_than_required_in_second_time()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 20,
            'type' => 1,
            'action_type' => 3,
            'count' => 10,
        ]);
        $value = 6;
        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete', [
            'value' => $value
        ]);
        $response->assertJson(['message' => 'value must be equal or smaller than required'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_complete_multi_action_stage_from_the_first_time_with_count_equal_one_and_get_badge()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'type' => 1,
            'parent_id'=>0
        ]);
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 1,
            'parent_id'=>$stage->id
        ]);
        $badge = Badge::factory()->create([
            'type'=>3
        ]);
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Challenge::class,
            'item_id' => $challenge->id
        ]);
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_challenge_stages', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'stage_id' => $stage->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'item_id' => $stage->id,
            'points' => $stage->points,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'points_count' => $currentPoints + $stage->points,
        ]);
        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function user_complete_challenge_and_get_some_activity_badge()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $event = Event::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'type' => 1,
            'parent_id'=>0
        ]);
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 1,
            'parent_id'=>$stage->id
        ]);
        $badge = Badge::factory()->create([
            'type'=>2
        ]);
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Challenge::class,
            'count' => 1
        ]);
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Event::class,
            'count' => 1
        ]);

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/join');

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function user_complete_challenge_and_get_specific_activity_badge()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $event = Event::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'type' => 1,
            'parent_id'=>0
        ]);
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 2,
            'type' => 1,
            'action_type' => 2,
            'count' => 1,
            'parent_id'=>$stage->id
        ]);
        $badge = Badge::factory()->create([
            'type'=>3
        ]);
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Challenge::class,
            'item_id' => $challenge->id
        ]);
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Event::class,
            'item_id' => $event->id
        ]);

        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/join');
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function user_complete_challenge_and_get_points_badge()
    {
        $user = User::get()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $challenge = Challenge::factory()->create();
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'type' => 1,
            'parent_id'=>0
        ]);
        $stage = ChallengeStage::factory()->create([
            'challenge_id' => $challenge->id,
            'points' => 500,
            'type' => 1,
            'action_type' => 2,
            'count' => 1,
            'parent_id'=>$stage->id
        ]);
        $badge = Badge::factory()->create([
            'type'=>1,
            'points'=>500,
        ]);


        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/' . $challenge->id . '/join');

        $user = User::find($user->id);

        $response = $this->actingAs($user, 'sanctum')->post('/api/challenges/stages/' . $stage->id . '/complete');

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }
}

