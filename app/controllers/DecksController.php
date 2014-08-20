<?php

class DecksController extends \BaseController {

	/**
	 * Display a listing of decks
	 *
	 * @return Response
	 */
	public function index()
	{
		$decks = Deck::all();

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
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Deck::create($data);

		return Redirect::route('decks.index');
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
		Deck::destroy($id);

		return Redirect::route('decks.index');
	}

}
