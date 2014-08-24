<?php

class Turn extends \Moloquent {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function gameSession()
	{
		return $this->belongsTo('GameSession');
	}

}