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

class User extends Eloquent {
	public $name = "Alex";

	protected $fillable = ["email", "first_name", "last_name", "status"];
}