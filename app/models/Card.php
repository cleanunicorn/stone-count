<?php

class Card extends \Moloquent {
	protected $rules = [];

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
	];
}