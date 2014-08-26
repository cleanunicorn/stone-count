<?php

class Deck extends \Moloquent
{
	/**
	 * Define the custom attributes
	 */
	protected $appends = array('card_list');

	/**
	 * The rules
	 *
	 * @var array
	 */
	static $rules = array(
		'name' => 'required'
		, 'class' => 'in:Warrior,Paladin,Mage,Rogue,Shaman,Druid,Warlock,Hunter,Priest'
		, 'tags' => 'array'
		, 'url' => 'url'
	);

	/**
	 * Fillable fields
	 *
	 * @var array
	 */
	protected $fillable = [
		'name'
		, 'class'
		, 'note'
		, 'tags'
		, 'url'
		, 'test'
	];

	/**
	 * Soft deletion of decks
	 *
	 * @var boolean
	 */
	use Jenssegers\Mongodb\Eloquent\SoftDeletingTrait;

	protected $dates = ['deleted_at'];

	/**
	 * Hide some of the attributes
	 *
	 * @var array
	 */
	protected $hidden = array('card_ids');

	/**
	 * Define the cards relationship
	 *
	 * @var object
	 */
	public function cards()
	{
		return $this->belongsToMany('Card');
	}

	/**
	 * Define the user relationship
	 *
	 * @var object
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

	/**
	 * Define a custom attribute that returns the Hearthstone ids based on the cards the user has
	 *
	 * @var array
	 */
	public function getCardListAttribute()
	{
		$cards = $this->cards()->get();

		$card_list = array();
		foreach($cards as $card)
		{
			$card_list[] = $card->uid;
		}

		return $card_list;
	}
}