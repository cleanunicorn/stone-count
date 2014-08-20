<?php

class DecksTest extends TestCase
{

	/**
	 * Prepare the database for the tests that follow
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
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
	}

	/**
	 * Test deck creation
	 *
	 * @return void
	 */
	public function testDeckCreate()
	{
		/*
		$response = $this->call(
			'POST'												// Method
			, '/decks' 											// Path
			, array( 											// Parameters
				'username' 		=> 'testUserCreate'
				, 'email' 		=> 'lucadanielcostin@gmail.com'
				, 'password' 	=> 'testing'
				, 'test'		=> true
			)
			, array() 											// Files
			, array()											// Server
			, 'application/json'								// Content
		);

		// Check if the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the user was created
		$this->assertEquals(201, $response->getStatuscode());
		*/
	}

}