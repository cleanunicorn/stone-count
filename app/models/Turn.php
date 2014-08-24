<?php

class Turn extends \Moloquent {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [
		'order'
	];

	public function gameSession()
	{
		return $this->belongsTo('GameSession');
	}

	public function turnActions()
	{
		return $this->hasMany('TurnAction');
	}

	/**
	 * Define events
	 *
	 * @var void
	 */
	public static function boot()
	{
		parent::boot();

		Turn::deleted(function($turn)
		{
			$turn->turn_actions()->delete();
		});
	}

}