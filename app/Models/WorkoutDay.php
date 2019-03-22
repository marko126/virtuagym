<?php

namespace App\Models;
/**
 * @uses Eloquent
 * @see http://laravel.com/docs/eloquent
 */
use Illuminate\Database\Eloquent\Model as Eloquent;


/**
 * Pretty basic user model. Does not do nothing at all
 */

class WorkoutDay extends Eloquent {

    protected $fillable = ["name", "plan_id"];
    
    public function exercises() {
        return $this->belongsToMany(\App\Models\Exercise::class, 'workout_days_exercises', 'workout_day_id', 'exercise_id');
    }
    
    public function plan() {
        return $this->belongsTo(\App\Models\Plan::class, 'plan_id');
    }
}