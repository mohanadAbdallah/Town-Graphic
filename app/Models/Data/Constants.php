<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
    protected $appends = ['challenge_types', 'stage_types', 'stage_action_types', 'event_types', 'models_types', 'badge_types'];
    protected $casts = ['challenge_types' => 'json'];

    public function getChallengeTypesAttribute()
    {
        return [
            ['id' => 1, 'name' => 'chaining'],
            ['id' => 2, 'name' => 'repeating']
        ];
    }

    public function getStageTypesAttribute()
    {
        return [
            ['id' => 1, 'name' => 'Read article'],
            ['id' => 2, 'name' => 'Take action'],
            ['id' => 3, 'name' => 'Goal']
        ];
    }

    public function getStageActionTypesAttribute()
    {
        return [
            ['id' => 1, 'name' => 'One action'],
            ['id' => 2, 'name' => 'Multi action'],
            ['id' => 3, 'name' => 'Count action'],
        ];
    }

    public function getEventTypesAttribute()
    {
        return [
            ['id' => 1, 'name' => 'On ground'],
            ['id' => 2, 'name' => 'Conference'],
            ['id' => 3, 'name' => 'Challenge start']
        ];
    }

    public function getModelsTypesAttribute()
    {
        return [
            ['id' => 'App\Models\Challenge\Challenge', 'name' => 'Challenge'],
            ['id' => 'App\Models\Event\Event', 'name' => 'Event'],
            ['id' => 'App\Models\Quiz\Quiz', 'name' => 'Quiz']
        ];
    }

    public function getBadgeTypesAttribute()
    {
        return [
            ['id' => 1, 'name' => 'Win by reach points'],
            ['id' => 2, 'name' => 'Win by complete some activities'],
            ['id' => 3, 'name' => 'Win by complete specific activities']
        ];
    }
}
