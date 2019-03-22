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

class Plan extends Eloquent {

    protected $fillable = ["name"];
    
    public function workoutDays() {
        return $this->hasMany(\App\Models\WorkoutDay::class, 'plan_id');
    }
    
    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'users_plans', 'plan_id', 'user_id');
    }
}