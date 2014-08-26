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

		Deck::where('test', true)->delete();
		User::where('test', true)->delete();
		GameSession::where('test', true)->delete();
	}

	/**
	 * Test gamesession creation with no deck specified
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
			, 'start_at' => $start_at
			, 'end_at' => $end_at
			, 'note' => ''
			, 'deck_id' => ''
			, 'turns' => array(
				array(
					'turn_actions' => array(
						array(
							'card' => 'EX1_007'
							, 'action' => 'PlayerDraw'
						)
						, array(
							'card' => 'FP1_002'
							, 'action' => 'PlayerDraw'
						)
						, array(
							'card' => 'CS2_147'
							, 'action' => 'PlayerDraw'
						)
					)
				)
				, array(
					'turn_actions' => array(
						array(
							'card' => 'GAME_005'
							, 'action' => 'PlayerDraw'
						)
						, array(
							'card' => 'CS2_013'
							, 'action' => 'OpponentPlay'
						)
						, array(
							'card' => 'CS2_147'
							, 'action' => 'OpponentPlay'
						)
						, array(
							'card' => ''
							, 'action' => 'OpponentDraw'
						)
					)
				)
			)

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
		$this->assertEquals($request_data['note'], $response_data['note']);

		// Testing turns one by one
		foreach($response_data['turns'] as $turn_key => $turn)
		{
			foreach($turn['turn_actions'] as $turn_action_key => $turn_action)
			{
				$this->assertEquals(
					$request_data['turns'][$turn_key]['turn_actions'][$turn_action_key]['card']
					, $turn_action['card']['uid']
				);

				$this->assertEquals(
					$request_data['turns'][$turn_key]['turn_actions'][$turn_action_key]['action']
					, $turn_action['action']
				);
			}
		}
	}


	/**
	 * Test gamesession creation with deck specified
	 *
	 * @return void
	 */
	public function testGameSessionWithDeckCreate()
	{
		// Create a deck
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

		// ---------------
		// Submit a gamesession with the previous deck created

		$start_at = new DateTime('-10 minute');
		$end_at = new DateTime('-2 minute');

		$request_data = array(
			'opponent_class' => 'Rogue'
			, 'coin' => true
			, 'mode' => 'Ranked'
			, 'result' => 'Loss'
			, 'start_at' => $start_at
			, 'end_at' => $end_at
			, 'note' => ''
			, 'deck_id' => $response_data['_id']
			, 'turns' => array(
				array(
					'turn_actions' => array(
						array(
							'card' => 'EX1_007'
							, 'action' => 'PlayerDraw'
						)
						, array(
							'card' => 'FP1_002'
							, 'action' => 'PlayerDraw'
						)
						, array(
							'card' => 'CS2_147'
							, 'action' => 'PlayerDraw'
						)
					)
				)
				, array(
					'turn_actions' => array(
						array(
							'card' => 'GAME_005'
							, 'action' => 'PlayerDraw'
						)
						, array(
							'card' => 'CS2_013'
							, 'action' => 'OpponentPlay'
						)
						, array(
							'card' => 'CS2_147'
							, 'action' => 'OpponentPlay'
						)
						, array(
							'card' => ''
							, 'action' => 'OpponentDraw'
						)
					)
				)
			)

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
		$this->assertEquals($request_data['note'], $response_data['note']);

		// Testing turns one by one
		foreach($response_data['turns'] as $turn_key => $turn)
		{
			foreach($turn['turn_actions'] as $turn_action_key => $turn_action)
			{
				$this->assertEquals(
					$request_data['turns'][$turn_key]['turn_actions'][$turn_action_key]['card']
					, $turn_action['card']['uid']
				);

				$this->assertEquals(
					$request_data['turns'][$turn_key]['turn_actions'][$turn_action_key]['action']
					, $turn_action['action']
				);
			}
		}
	}

}