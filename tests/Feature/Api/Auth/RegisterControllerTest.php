<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
//    use DatabaseMigrations;
    /**
     * @test
     */
    public function send_user_otp_code_for_login_using_email()
    {
        $user = User::all();
        $user =  User::factory()->create();
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'login',
                'value' => $user->email,
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);
    }
    /**
     * @test
     */
    public function send_user_otp_code_for_register_using_email()
    {
        $faker = Factory::create();
        $email = $faker->email;
        $name = $faker->firstName .' '.$faker->lastName;
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'register',
                'value' => $email,
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);

        $this->assertDatabaseHas('users',
            [
                'email' => $email
            ]);
    }
    /**
     * @test
     */
    public function verify_user_otp_code_sent_after_login_using_email()
    {
        $user =  User::factory()->create();
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'login',
                'value' => $user->email,
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);

        $otpCode = $response->json('otpCode');

        $response = $this->json('POST', 'api/user/verify/otp',
            [
                'otp_code' => $otpCode,
                'value' => $user->email
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token', 'status']);

    }
    /**
     * @test
     */
    public function verify_user_otp_code_sent_after_register_using_email()
    {
        $faker = Factory::create();
        $email = $faker->email;
        $name = $faker->firstName .' '.$faker->lastName;
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'register',
                'value' => $email,
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);

        $this->assertDatabaseHas('users',
            [
                'email' => $email
            ]);

        $otpCode = $response->json('otpCode');

        $response = $this->json('POST', 'api/user/verify/otp',
            [
                'otp_code' => $otpCode,
                'value' => $email
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token', 'status']);

    }
    /**
     * @test
     */
    public function send_user_otp_code_for_login_using_mobile()
    {
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'login',
                'value' => '0599542463',
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);
    }

    /**
     * @test
     */
    public function send_user_otp_code_for_register_using_mobile()
    {
        $faker = Factory::create();
        $randomNumber = mt_rand(100000, 999999);
        $phone = '0599'.$randomNumber;
        $name = $faker->firstName .' '.$faker->lastName;
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'register',
                'value' => $phone,
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);

        $this->assertDatabaseHas('users',
            [
                'mobile' => $phone
            ]);
    }
    /**
     * @test
     */
    public function verify_user_otp_code_after_login_using_mobile()
    {
        $mobile = '0599542463';
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'login',
                'value' => $mobile,
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);

        $otpCode = $response->json('otpCode');

        $response = $this->json('POST', 'api/user/verify/otp',
            [
                'otp_code' => $otpCode,
                'value' => $mobile
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token', 'status']);

    }
    /**
     * @test
     */
    public function verify_user_otp_code_after_register_using_mobile()
    {
        $faker = Factory::create();
        $randomNumber = mt_rand(100000, 999999);
        $phone = '0599'.$randomNumber;
        $name = $faker->firstName .' '.$faker->lastName;
        $response = $this->json('POST', 'api/user/send/otp',
            [
                'for' => 'register',
                'value' => $phone,
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['otpCode', 'status']);

        $this->assertDatabaseHas('users',
            [
                'mobile' => $phone
            ]);

        $otpCode = $response->json('otpCode');

        $response = $this->json('POST', 'api/user/verify/otp',
            [
                'otp_code' => $otpCode,
                'value' => $phone
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token', 'status']);

    }
}
