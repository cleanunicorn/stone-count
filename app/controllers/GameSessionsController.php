<?php

class GameSessionsController extends \BaseController {

	/**
	 * Display a listing of GameSessions
	 *
	 * @return Response
	 */
	public function index()
	{
		$GameSessions = GameSession::all();

		return View::make('GameSessions.index', compact('GameSessions'));
	}

	/**
	 * Show the form for creating a new GameSession
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('GameSessions.create');
	}

	/**
	 * Store a newly created GameSession in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), GameSession::$rules);

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
		$gamesession = GameSession::create($data);

		// Save each turn
		// ...

		return Response::json(
			$gamesession->toArray()
			, 201
		);
	}

	/**
	 * Display the specified GameSession.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$GameSession = GameSession::findOrFail($id);

		return View::make('GameSessions.show', compact('GameSession'));
	}

	/**
	 * Show the form for editing the specified GameSession.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$GameSession = GameSession::find($id);

		return View::make('GameSessions.edit', compact('GameSession'));
	}

	/**
	 * Update the specified GameSession in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$GameSession = GameSession::findOrFail($id);

		$validator = Validator::make($data = Input::all(), GameSession::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$GameSession->update($data);

		return Redirect::route('GameSessions.index');
	}

	/**
	 * Remove the specified GameSession from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		GameSession::destroy($id);

		return Redirect::route('GameSessions.index');
	}

}
