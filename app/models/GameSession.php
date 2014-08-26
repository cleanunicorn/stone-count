<?php

class GameSession extends \Moloquent {

	// Add your validation rules here
	public static $rules = [
		'opponent_class' => 'required|in:Warrior,Paladin,Mage,Rogue,Shaman,Druid,Warlock,Hunter,Priest'
		, 'coin' => 'required|boolean'
		, 'mode' => 'required|in:Ranked,Casual,Arena,Friendly,Practice,None'
		, 'result' => 'required|in:None,Win,Loss'
		, 'start_at' => 'required|date'
		, 'end_at' => 'required|date'
		, 'note' => ''
	];

	// Don't forget to fill this array
	protected $fillable = [
		'opponent_class'
		, 'coin'
		, 'mode'
		, 'result'
		, 'start_at'
		, 'end_at'
		, 'note'

		, 'test'
	];

	/**
	 * Add additional fields to be treated as dates
	 *
	 * @var array
	 */
	protected $dates = array(
		'start_at'
		, 'end_at'
	);

	/**
	 * Define the turns relationship
	 *
	 * @var object
	 */
	public function turns()
	{
		return $this->hasMany('Turn');
	}

	/**
	 * Define the deck relationship
	 *
	 * @var object
	 */
	public function deck()
	{
		return $this->belongsTo('Deck');
	}

	/**
	 * Define events
	 *
	 * @var void
	 */
	public static function boot()
	{
		parent::boot();

		GameSession::deleted(function($gamesession)
		{
			$gamesession->turns()->delete();
		});
	}

	/**
	 * Define user relationship
	 *
	 * @var object
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}
}