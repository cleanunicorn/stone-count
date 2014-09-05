<?php

class DecksController extends \BaseController {

	/**
	 * Display a listing of decks
	 *
	 * @return Response
	 */
	public function index()
	{
		$user = User::auth_token_check();
		$decks = $user->decks()->get();

		$decks = Deck::all();

		return Response::json(
			$decks->toArray()
			, 200
		);

		return View::make('decks.index', compact('decks'));
	}

	/**
	 * Show the form for creating a new deck
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('decks.create');
	}

	/**
	 * Store a newly created deck in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Deck::$rules);

		if ($validator->fails())
		{
			return Response::json(
				array(
					$validator->messages()
				)
				, 400
			);
		}

		$user = User::auth_token_check();
		$deck = Deck::create($data);

		// Save each card inserted
		foreach($data['card_list'] as $card_id)
		{
			$card = Card::where('uid', $card_id)->get()->first();
			if ($card)
			{
				echo "Saving {$card_id}\n";
				$deck->cards()->save($card);
			}
		}
		$deck->save();

		// Associate this deck to the user
		$user->decks()->save($deck);

		return Response::json(
			$deck->toArray()
			, 201
		);
	}

	/**
	 * Display the specified deck.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$deck = Deck::findOrFail($id);

		return View::make('decks.show', compact('deck'));
	}

	/**
	 * Show the form for editing the specified deck.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$deck = Deck::find($id);

		return View::make('decks.edit', compact('deck'));
	}

	/**
	 * Update the specified deck in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$deck = Deck::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Deck::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$deck->update($data);

		return Redirect::route('decks.index');
	}

	/**
	 * Remove the specified deck from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$deleted = Deck::destroy($id);

		return Response::json(
			''
			, 204
		);
	}

}
