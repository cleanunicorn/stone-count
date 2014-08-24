<?php

class GameSessionsTest extends TestCase
{
	private $auth_token = '';

	/**
	 * Prepare the database for the tests that follow
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		// Create a user and login
		$response = $this->call(
			'POST'												// Method
			, '/users' 										// Path
			, array( 											// Parameters
				'username' 		=> 'testUserLogin'
				, 'password' 	=> 'testing'
				, 'email' 		=> 'testUserLogin3@gmail.com'
				, 'test'		=> true
			)
			, array() 											// Files
			, array()											// Server
			, 'application/json'								// Content
		);

		// Try to login with this newly created user
		$response = $this->call(
			'POST'
			, '/login'
			, array(
				'username' => 'testUserLogin'
				, 'password' => 'testing'
			)
			, array()
			, array()
			, 'application/json'
		);

		$response_data = json_decode($response->getContent(), true);
		$this->auth_token = $response_data[0]['auth_token'];
	}

	/**
	 * Remove the test data that was inserted in the database
	 *
	 * @return void
	 */
	public function tearDown()
	{
		parent::tearDown();

		//Deck::where('test', true)->delete();
		//User::where('test', true)->delete();
		//GameSession::where('test', true)->delete();
	}

	/**
	 * Test gamesession creation
	 *
	 * @return void
	 */
	public function testGameSessionWithoutDeckCreate()
	{
		$start_at = new DateTime('-10 minute');
		$end_at = new DateTime('-2 minute');

		$request_data = array(
			'opponent_class' => 'Paladin'
			, 'coin' => true
			, 'mode' => 'Ranked'
			, 'result' => 'Win'
			, 'turns' => '12'
			, 'start_at' => $start_at
			, 'end_at' => $end_at
			, 'note' => ''

			, 'test' => true
		);

		$response = $this->call(
			'POST'												// Method
			, '/game-sessions' 											// Path
			, $request_data
			, array() 											// Files
			, array(
				'HTTP_X-Authorization-Token' => $this->auth_token
			)											// Server
			, 'application/json'								// Content
		);

		// Check if the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the gamesession was created
		$this->assertEquals(201, $response->getStatuscode());

		// Test returned data matches with what we sent
		$this->assertEquals($request_data['opponent_class'], $response_data['opponent_class']);
		$this->assertEquals($request_data['coin'], $response_data['coin']);
		$this->assertEquals($request_data['mode'], $response_data['mode']);
		$this->assertEquals($request_data['result'], $response_data['result']);
		$this->assertEquals($request_data['turns'], $response_data['turns']);
		$this->assertEquals($request_data['note'], $response_data['note']);

		/*
		// Get all the users' decks and check he has the one with this card list
		$response = $this->call(
			'GET'
			, '/decks'
			, array()
			, array()
			, array(
				'HTTP_X-Authorization-Token' => $this->auth_token
			)
			, 'application/json'
		);

		// Check if the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the deck was created
		$this->assertEquals(200, $response->getStatuscode());

		// Compute the cards that were added and cards that were not there
		$cards_not_added = array_diff($card_list, $response_data[0]['card_list']);
		$cards_added_not_there = array_diff($response_data[0]['card_list'], $card_list);
		// Check all the cards were added and nothing more
		$this->assertEquals(0, count($cards_not_added));
		$this->assertEquals(0, count($cards_added_not_there));
		*/
	}

}