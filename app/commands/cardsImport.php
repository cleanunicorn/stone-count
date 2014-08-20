<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class cardsImport extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cards:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import cards based on the remote json database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
		$client = new GuzzleHttp\Client();
		$decks_request = $client->get('http://hearthstonejson.com/json/AllSets.json');

		$decks_data = $decks_request->json();

		// Remove all cards
		Card::truncate();

		// Import basic cards from each collection
		$cards_imported = 0;
		foreach($decks_data as $collection_list)
		{
			foreach($collection_list as $card)
			{
				$cards_imported++;
				$card['uid'] = $card['id'];
				unset($card['id']);

				Card::create($card);
			}
		}

		$this->info("Imported cards {$cards_imported}");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
