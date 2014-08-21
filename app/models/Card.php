<?php

class Card extends \Moloquent
{
	/**
	 * The rules
	 *
	 * @var array
	 */
	protected $rules = [];

	// Don't forget to fill this array
	protected $fillable = [
		'name'
		, 'cost'
		, 'type'
		, 'rarity'
		, 'faction'
		, 'race'
		, 'playerClass'
		, 'text'
		, 'inPlayText'
		, 'mechanics'
		, 'flavor'
		, 'artist'
		, 'attack'
		, 'health'
		, 'durability'
		, 'id'
		, 'collectible'
		, 'elite'
		, 'howToGet'
		, 'howToGetGold'
		, 'uid'
	];

	/**
	 * Define the decks relationship
	 *
	 * @var array
	 */
	public function decks()
	{
		return $this->hasMany('Deck');
	}
}