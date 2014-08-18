<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Moloquent implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait;

	/**
	 * The database connection that should be used
	 *
	 * @var string
	 */
	protected $connection  = 'mongodb';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/**
	 * The attributes that can be mass filled
	 *
	 * @var array
	 */
	protected $fillable = array(
		'username'
		, 'email'
		, 'test'
	);

	/**
	 * The rules
	 *
	 * @var array
	 */
	static $rules = array(
		'username' => 'required|unique:users'
		, 'email' => 'required|email|unique:users'
	);

	/**
	 * The
	 *
	 *
	 *
	 */

}
