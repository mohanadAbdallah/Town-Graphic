<?php

namespace App\Http\Controllers;

use App\Models\Badge\Badge;
use App\Models\Badge\BadgeTerm;
use App\Models\Level\Level;
use App\Models\Level\UserLevel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /* Protected functions can be used in challenges, events and quizzes api controllers  */
    protected function grantBadge($model, $modelClass, int $challengeStatus, $userStatic)
    {
        if (!$challengeStatus) return false;

        $this->getPointBadge($userStatic);

        $this->getSomeActivitiesBadge($userStatic);

        $this->getSpecificActivitiesBadge($userStatic, $model, $modelClass);

        return null;
    }

    protected function checkAndGrantLevel($userStatic)
    {
        $userPoints = $userStatic->points_count ?? 0;
        $level = Level::where('from_points_count', '<=', $userPoints)->where('to_points_count', '>', $userPoints)->first();
        $isUserInLevel = auth('sanctum')->user()->userLevel->where('level_id', $level->id)->first();
        if (!$isUserInLevel) {

            if (auth('sanctum')->user()->userLevel->count() > 0) {
                $userLevelIds = auth('sanctum')->user()->userLevel->pluck('id');
                UserLevel::whereIn('id', $userLevelIds)->update(['status' => 0]);
            }

            $newUserLevel = new UserLevel();
            $newUserLevel->user_id = auth('sanctum')->user()->id;
            $newUserLevel->level_id = $level->id;
            $newUserLevel->status = 1;
            $newUserLevel->save();
        }
    }

    private function getPointBadge($userStatic)
    {
        $userPoints = $userStatic->points_count ?? 0;

        $userBadges = auth('sanctum')->user()->userBadge->pluck('badge_id');
        $badge = Badge::where('type', 1)->where('points', '<=', $userPoints)->whereNotIn('id', $userBadges)->orderBy('points', 'desc')->first();
        if (!$badge)
            return false;

        return auth('sanctum')->user()->userBadge()->create(['badge_id' => $badge->id, 'status' => 1]);

    }

    private function getSomeActivitiesBadge($userStatic)
    {
        $userChallengesCount = $userStatic->challenges_count ?? 0;
        $userEventsCount = $userStatic->events_count ?? 0;
        $userQuizzesCount = $userStatic->quizzes_count ?? 0;

        $userBadges = auth('sanctum')->user()->userBadge->pluck('badge_id');
        $badges = Badge::where('type', 2)->whereNotIn('id', $userBadges)->get();
        foreach ($badges as $badge) {
            $hasRightCountChallenges = false;
            $hasRightCountEvents = false;
            $hasRightCountQuizzes = false;
            $hasRightCountVariables = [];

            foreach ($badge->terms as $term) {

                if (Str::contains($term->source, 'Challenge')) {
                    array_push($hasRightCountVariables, 'hasRightCountChallenges');
                    if ($term->count <= $userChallengesCount)
                        $hasRightCountChallenges = true;
                }

                if (Str::contains($term->source, 'Event')) {
                    array_push($hasRightCountVariables, 'hasRightCountEvents');
                    if ($term->count <= $userEventsCount)
                        $hasRightCountEvents = true;
                }

                if (Str::contains($term->source, 'Quiz')) {
                    array_push($hasRightCountVariables, 'hasRightCountQuizzes');
                    if ($term->count <= $userQuizzesCount)
                        $hasRightCountQuizzes = true;
                }

            }
            foreach ($hasRightCountVariables as $hasRightCount) {
                if ($$hasRightCount) {
                    $flag = true;
                } else {
                    $flag = false;
                    break;
                }
            }
            if ($flag)
                auth('sanctum')->user()->userBadge()->create(['badge_id' => $badge->id, 'status' => 1]);
        }
    }

    private function getSpecificActivitiesBadge($userStatic, $model, $modelClass)
    {
        if ($badge = $this->isModelItemHasBadge($model, $modelClass)) {
            $badgeTerms = BadgeTerm::where('badge_id', $badge->badge_id)->get();

            $isUserCompletedChallenge = false;
            $isUserCompletedEvent = false;
            $isUserCompletedQuiz = false;
            $hasRightCountVariables = [];
            foreach ($badgeTerms as $badgeTerm) {
                if (Str::contains($badgeTerm->source, 'Challenge')) {
                    array_push($hasRightCountVariables, 'isUserCompletedChallenge');
                    if (auth('sanctum')->user()->userChallenges->where('challenge_id', $badgeTerm->item_id)->where('status', 1)->first())
                        $isUserCompletedChallenge = true;
                }

                if (Str::contains($badgeTerm->source, 'Event')) {
                    array_push($hasRightCountVariables, 'isUserCompletedEvent');
                    if (auth('sanctum')->user()->userEvents->where('event_id', $badgeTerm->item_id)->where('status', 1)->first())
                        $isUserCompletedEvent = true;
                }

                if (Str::contains($badgeTerm->source, 'Quiz')) {
                    array_push($hasRightCountVariables, 'isUserCompletedQuiz');
                    if (auth('sanctum')->user()->userQuizzes->where('quiz_id', $badgeTerm->item_id)->where('status', 1)->first())
                        $isUserCompletedQuiz = true;
                }
            }

            $flag = false;
            foreach ($hasRightCountVariables as $hasRightCount) {
                if ($$hasRightCount) {
                    $flag = true;
                } else {
                    $flag = false;
                    break;
                }


            }
//            if (Str::contains($modelClass, 'Quiz'))
//            dd($flag);
            if ($flag)
                auth('sanctum')->user()->userBadge()->create(['badge_id' => $badge->badge_id, 'status' => 1]);
        }
    }

    private function isModelItemHasBadge($model, $modelClass)
    {
        if (Str::contains($modelClass, 'Challenge'))
            $item_id = 'challenge_id';
        if (Str::contains($modelClass, 'Event'))
            $item_id = 'id';
        if (Str::contains($modelClass, 'Quiz'))
            $item_id = 'id';


        $badge = BadgeTerm::where('source', $modelClass)
            ->where('item_id', $model->$item_id)
            ->first();

        if ($badge) return $badge;

        return false;
    }
}
