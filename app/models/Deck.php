<?php

class Deck extends \Moloquent
{
	/**
	 * The rules
	 *
	 * @var array
	 */
	static $rules = array(
		'name' => 'required'
		, 'class' => 'in:Warrior,Paladin,Mage,Rogue,Shaman,Druid,Warlock,Hunter,Priest'
	);

	// Don't forget to fill this array
	protected $fillable = [
		'name'
		, 'class'
		, 'test'
	];
}