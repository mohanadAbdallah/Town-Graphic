<?php

namespace Tests\Feature\Api;

use App\Models\Challenge\Challenge;
use App\Models\User\User;
use App\Models\User\UserInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    /**
     * @test
     */
    public function user_must_be_singed_in_to_invite()
    {
        $response = $this->post('/api/user/invite');

        $response->assertStatus(401);// not authorized access
    }

    /**
     * @test
     */
    public function model_name_and_item_id_must_be_passed_to_invite_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->post('/api/user/invite');

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function model_name_and_item_id_must_be_found_to_invite_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->post('/api/user/invite', [
            'model_name' => 0,
            'item_id' => 0,
        ]);
        $response->assertStatus(422);

        $challenge = Challenge::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->post('/api/user/invite', [
            'model_name' => $challenge->model_name,
            'item_id' => $challenge->id,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_get_shares_count_plus_one_to_user_statics()
    {
        $user = User::factory()->create();

        $challenge = Challenge::factory()->create();

        $currentPoints = $user->userStatics->shares_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/user/invite', [
            'model_name' => $challenge->model_name,
            'item_id' => $challenge->id,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'shares_count' => $currentPoints + 1,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_must_be_singed_in_to_accept_invitation()
    {
        $user = User::factory()->create();
        $response = $this->post('/api/user/invite/accept');

        $response->assertStatus(401);// not authorized access
    }

    /**
     * @test
     */
    public function hash_must_be_passed_to_accept_invitation()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->post('/api/user/invite/accept');

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function hash_must_be_found_to_accept_invitation()
    {
        $userInviter = User::factory()->create();
        $userAccept = User::factory()->create();
        $response = $this->actingAs($userInviter, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => '0'
        ]);
        $response->assertStatus(422);

        $challenge = Challenge::factory()->create();
        $userInvitation = UserInvitation::factory()->create([
            'user_id' => $userInviter->id,
            'source' => $challenge->model_name,
            'item_id' => $challenge->id
        ]);
        $response = $this->actingAs($userAccept, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => $userInvitation->hash
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cant_accept_invitation_of_him()
    {
        $userInviter = User::factory()->create();
        $response = $this->actingAs($userInviter, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => '0'
        ]);
        $response->assertStatus(422);

        $challenge = Challenge::factory()->create();

        $userInvitation = UserInvitation::factory()->create([
            'user_id' => $userInviter->id,
            'source' => $challenge->model_name,
            'item_id' => $challenge->id
        ]);
        $response = $this->actingAs($userInviter, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => $userInvitation->hash
        ]);
        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_accept_invitation_more_than_one()
    {
        $userInviter = User::factory()->create();
        $userAccept = User::factory()->create();

        $response = $this->actingAs($userInviter, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => '0'
        ]);
        $response->assertStatus(422);

        $challenge = Challenge::factory()->create();

        $userInvitation = UserInvitation::factory()->create([
            'user_id' => $userInviter->id,
            'source' => $challenge->model_name,
            'item_id' => $challenge->id
        ]);
        $response = $this->actingAs($userAccept, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => $userInvitation->hash
        ]);
        $userAccept = User::find($userAccept->id);
        $response = $this->actingAs($userAccept, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => $userInvitation->hash
        ]);
        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_get_invites_count_plus_one_to_user_statics_when_invited_user_accept_invitation()
    {
        $userInviter = User::factory()->create();
        $userAccept = User::factory()->create();


        $challenge = Challenge::factory()->create();

        $userInvitation = UserInvitation::factory()->create([
            'user_id' => $userInviter->id,
            'source' => $challenge->model_name,
            'item_id' => $challenge->id
        ]);
        $currentPoints = $user->userStatics->invites_count ?? 0;
        $response = $this->actingAs($userAccept, 'sanctum')->post('/api/user/invite/accept', [
            'hash' => $userInvitation->hash
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $userInviter->id,
            'invites_count' => $currentPoints + 1,
        ]);
        $response->assertStatus(200);
    }
}
