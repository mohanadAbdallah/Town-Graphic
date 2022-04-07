<?php

namespace Tests\Feature\Api;

use App\Models\Badge\Badge;
use App\Models\Badge\BadgeTerm;
use App\Models\Challenge\Challenge;
use App\Models\Challenge\ChallengeStage;
use App\Models\Event\Event;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizQuestionChoice;
use App\Models\Quiz\UserQuiz;
use App\Models\Quiz\UserQuizAnswer;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuizControllerTest extends TestCase
{
    /**
     * @test
     */
    public function user_must_be_singed_in_to_get_events()
    {
        $user =  User::factory()->create();
        $response = $this->get('/api/quizzes');

        $response->assertStatus(401);// not authorized access
    }

    /**
     * @test
     */
    public function user_can_show_quizzes()
    {
        $user =  User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->get('/api/quizzes');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_can_show_specific_quiz()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->get('/api/quizzes/' . $quiz->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_join_quiz()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();
        $currentPoints = $user->userStatics->quizzes_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/' . $quiz->id . '/join');

        $this->assertDatabaseHas('user_quizzes', [
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'quizzes_count' => $currentPoints + 1,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function user_cant_join_quiz_that_not_found()
    {
        $user =  User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/0/join');

        $response->assertJson(['message' => 'Quiz not found'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_join_more_than_one_to_quiz()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();

        $joinResponse = $this->actingAs($user, 'sanctum')->post('/api/quizzes/' . $quiz->id . '/join');

        if ($joinResponse->assertStatus(200)) {

            $user = User::find($user->id);

            $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/' . $quiz->id . '/join');

            $response->assertJson(['message' => 'User already joined'])
                ->assertStatus(422);
        }
    }

    /**
     * @test
     */
    public function user_cant_answer_not_found_question_or_choice()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/0/choice/0');

        $response->assertJson(['message' => 'Question or Answer not found'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_answer_a_choice_that_not_from_question_choices()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id
        ]);
        $anotherQuestion = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id
        ]);
        $choice = QuizQuestionChoice::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $anotherQuestion->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);

        $response->assertJson(['message' => 'Answer not from question choices.'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_cant_answer_question_that_not_joined_its_quiz()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id
        ]);
        $choice = QuizQuestionChoice::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);

        $response->assertJson(['message' => 'User not joined this quiz yet.'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_can_answer_question()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id
        ]);
        $choice = QuizQuestionChoice::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
        ]);
        $userJoinQuiz = UserQuiz::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'status' => 0
        ]);
        $userAnswer = UserQuizAnswer::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'choice_id' => $choice->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cant_answer_question_that_completed_by_user()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create();
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id
        ]);
        $choice = QuizQuestionChoice::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
        ]);
        $userJoinQuiz = UserQuiz::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'status' => 1
        ]);
        $userAnswer = UserQuizAnswer::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'choice_id' => $choice->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);

        $response->assertJson(['message' => 'User is already completed this quiz.'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_can_answer_question_and_complete_it()
    {
        $user =  User::factory()->create();
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
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $anotherQuestion->id . '/choice/' . $anotherChoice->id);
        $user = User::find($user->id);
        $this->assertDatabaseHas('user_quizzes', [
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('user_statics', [
            'user_id' => $user->id,
            'points_count' => $currentPoints + $quiz->points,
        ]);
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'source' => 'App\Models\Quiz\Quiz',
            'item_id' => $quiz->id,
            'points' => $quiz->points,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_complete_question_and_get_badge()
    {
        $user =  User::factory()->create();
        $quiz = Quiz::factory()->create([
            'points' => 2
        ]);
        $badge = Badge::factory()->create();
        $badgeTerm = BadgeTerm::factory()->create([
            'badge_id' => $badge->id,
            'source' => Quiz::class,
            'item_id' => $quiz->id
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
        $currentPoints = $user->userStatics->points_count ?? 0;

        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $anotherQuestion->id . '/choice/' . $anotherChoice->id);
        $user = User::find($user->id);

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function user_complete_quiz_and_get_specific_activity_badge()
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


        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/join');
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/events/' . $event->id . '/checkin');
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $question->id . '/choice/' . $choice->id);
        $user = User::find($user->id);
        $response = $this->actingAs($user, 'sanctum')->post('/api/quizzes/question/' . $anotherQuestion->id . '/choice/' . $anotherChoice->id);
        $user = User::find($user->id);

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
        $response->assertStatus(200);
    }

}
