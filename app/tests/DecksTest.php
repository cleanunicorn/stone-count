<?php

class DecksTest extends TestCase
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

		Deck::where('test', true)->forceDelete();
		User::where('test', true)->forceDelete();
	}

	/**
	 * Test deck creation
	 *
	 * @return void
	 */
	public function testDeckCreate()
	{
		$card_list = array(
			'DREAM_02'
			, 'CS2_042'
			, 'EX1_129'
			, 'CS2_108'
		);

		$request_data = array(
			'name' 			=> 'testUserCreate'
			, 'class' 		=> 'Warrior'
			, 'card_list' 	=> $card_list
			, 'test'		=> true
		);

		$response = $this->call(
			'POST'												// Method
			, '/decks' 											// Path
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

		// Check if the deck was created
		$this->assertEquals(201, $response->getStatuscode());

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
	}

	/**
	 * Test deck deletion
	 *
	 * @return void
	 */
	public function testDeckDelete()
	{
		$card_list = array(
			'DREAM_02'
			, 'CS2_042'
			, 'EX1_129'
			, 'CS2_108'
		);

		$request_data = array(
			'name' 			=> 'testUserCreate'
			, 'class' 		=> 'Warrior'
			, 'card_list' 	=> $card_list
			, 'test'		=> true
		);

		// Create a deck to delete
		$response = $this->call(
			'POST'												// Method
			, '/decks' 											// Path
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

		// Check if the deck was created
		$this->assertEquals(201, $response->getStatuscode());

		// Delete the deck that was just created
		$response = $this->call(
			'DELETE'
			, '/decks/'. $response_data['_id']
			, array()
			, array()
			, array(
				'HTTP_X-Authorization-Token' => $this->auth_token
			)
			, 'application/json'
		);

		// Check if the deck was created
		$this->assertEquals(204, $response->getStatuscode());
	}

}