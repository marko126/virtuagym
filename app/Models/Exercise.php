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

class Exercise extends Eloquent {

    protected $fillable = ["name"];
        
    public function workoutDays() {
        return $this->belongsToMany(\App\Models\WorkoutDay::class, 'workout_days_exercises', 'exercise_id', 'workout_day_id');
    }
}