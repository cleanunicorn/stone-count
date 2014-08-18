<?php

class UserRegisterTest extends TestCase
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

		User::where('test', true)->delete();
	}

	/**
	 * Test user creation
	 *
	 * @return void
	 */
	public function testUserCreate()
	{
		$response = $this->call(
			'POST'												// Method
			, '/users' 										// Path
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

		// Check the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the user was created
		$this->assertEquals(201, $response->getStatuscode());
	}

	/**
	 * Test user creation with incomplete data
	 *
	 * @return void
	 */
	public function testUserCreateWithIncompleteData()
	{
		$response = $this->call(
			'POST'												// Method
			, '/users' 										// Path
			, array( 											// Parameters
				'username' 		=> 'testUserCreateWithError'
				, 'password' 	=> 'testing'
				, 'test'		=> true
			)
			, array() 											// Files
			, array()											// Server
			, 'application/json'								// Content
		);

		// Check the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the user was created
		$this->assertEquals(400, $response->getStatuscode());
	}

	/**
	 * Test user creation with existing user
	 *
	 * @return void
	 */
	public function testUserCreateWithExistingUser()
	{
		// Try to create the first user
		$response = $this->call(
			'POST'												// Method
			, '/users' 										// Path
			, array( 											// Parameters
				'username' 		=> 'testUserCreateDuplicate'
				, 'password' 	=> 'testing'
				, 'email' 		=> 'lucadanielcostin2@gmail.com'
				, 'test'		=> true
			)
			, array() 											// Files
			, array()											// Server
			, 'application/json'								// Content
		);

		// Check the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the first user was created
		$this->assertEquals(201, $response->getStatuscode());

		// Try to create the second user again and this time we should have an error
		$response = $this->call(
			'POST'												// Method
			, '/users' 										// Path
			, array( 											// Parameters
				'username' 		=> 'testUserCreateDuplicate'
				, 'password' 	=> 'testing'
				, 'email' 		=> 'lucadanielcostin2@gmail.com'
				, 'test'		=> true
			)
			, array() 											// Files
			, array()											// Server
			, 'application/json'								// Content
		);

		// Check the response is an array
		$response_data = json_decode($response->getContent(), true);
		$this->assertInternalType('array', $response_data);

		// Check if the first user was created
		$this->assertEquals(400, $response->getStatuscode());

	}
}
