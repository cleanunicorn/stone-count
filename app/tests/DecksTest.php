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

		Deck::where('test', true)->delete();
		User::where('test', true)->delete();
	}

	/**
	 * Test deck creation
	 *
	 * @return void
	 */
	public function testDeckCreate()
	{
		$response = $this->call(
			'POST'												// Method
			, '/decks' 											// Path
			, array( 											// Parameters
				'name' 			=> 'testUserCreate'
				, 'class' 		=> 'Warrior'
				, 'card_list' 	=> array(
					'CS2_042'
					, 'EX1_129'
					, 'CS2_108'
				)
				, 'test'		=> true
			)
			, array() 											// Files
			, array(
				'HTTP_X-Authorization-Token' => $this->auth_token
			)											// Server
			, 'application/json'								// Content
		);

		// Check if the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the user was created
		$this->assertEquals(201, $response->getStatuscode());

		print_r($response_data);
	}

}