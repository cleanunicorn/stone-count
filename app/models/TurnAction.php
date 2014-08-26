<?php

class TurnAction extends \Moloquent
{

	public static function boot()
    {
    	self::$rules = array(
    		'action' => 'required|in:'. implode(",", self::$rules)
		);
    }

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [
		'action'
	];

	public static $action = array(
		'PlayerPlay',
		'PlayerDraw',
		'PlayerGet',
		'PlayerMulligan',
		'PlayerHandDiscard',
		'PlayerDeckDiscard',
		'PlayerBackToHand',
		'PlayerHeroPower',
		'OpponentPlay',
		'OpponentDraw',
		'OpponentGet',
		'OpponentMulligan',
		'OpponentHandDiscard',
		'OpponentDeckDiscard',
		'OpponentBackToHand',
		'OpponentSecretTriggered',
		'OpponentHeroPower'
	);

	public function turn()
	{
		return $this->belongsTo('Turn');
	}

	public function card()
	{
		return $this->belongsTo('Card');
	}

}