<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class StockImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'stock:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import stock data from Google Finance.';

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
		$options = $this->option();
		//
		if ($companies = Stock::getAllCompanies())
		{
			//$this->info('Started import stock data ' . ($options['full'] ? '(full import)' : ''));
			$i = 0;
			foreach ($companies as $company)
			{
				//Queue::push (function ($job) use($company, $options) {
					if ($record = Company::findBySymbol($company['symbol'])->first()) {

						$record->fill($company);

						//Log::info("Updated " . $company['symbol']);
					} else {

						$record = new Company($company);

						//Log::info("Created ". $company['symbol']);
					}

					if (array_key_exists('full', $options) && $options['full']) {
						$extend = Stock::getMores($company['symbol']);

						$record->fill($extend);
					}

					$record->save();
					$i++;
				//	$job->delete();
				//});
			}

			if ($indexes = Stock::getAllIndexes())
			{
				foreach ($indexes as $index) {
					if ($record = Index::findBySymbol($index['symbol'])->first()) {

						$record->fill($index);
					} else {
						$record = new Index($index);
					}

					$record->save();
				}
			}

			WebSocket::fire('default', 'stock:updated', true);
			//$this->info("Done import $i stocks");

			foreach(Company::getChangedStocks()->get() as $stock) {
				if ($stock->price['prev_quote'] != $stock->price['last_quote']) {
					WebSocket::fire('default', 'company:price_updated', $stock->toArray());

					foreach(User::getUsersByFavorite($stock->id)->get() as $user) {
						if (isset($user->alert) && is_array($user->alert)) {
							$ceiling = floatval($user->alert['ceiling']);
							$floor = floatval($user->alert['floor']);
						} else {
							$ceiling = 0;
							$floor = 0;
						}

						if ($ceiling > 0 && $stock->price['change_today'] >= $ceiling) {
							Mail::send('emails.stock_alert', $stock->toArray(), function($message) use($stock, $user, $ceiling)
							{
							    $message->to($user->email, $user->username)->subject('Alert - ' . $stock->symbol . ' price is up to ' . $stock->price['change_today'] . '%.');
							});
						} 

						if ($floor > 0 && $stock->price['change_today']*-1 >= $floor) {
							Mail::send('emails.stock_alert', $stock->toArray(), function($message) use($stock, $user, $floor)
							{
							    $message->to($user->email, $user->username)->subject('Alert - ' . $stock->symbol . ' price is drop to ' . ($stock->price['change_today']*-1) . '%.');
							});
						}
					}
				}
			}
			/*foreach(Company::getNewStocks()->get() as $stock) {
				WebSocket::fire('default', 'company:added', $stock->toArray());
			}*/
		} else {
			//$this->error('Cannot import data right now!');
			$this->error('Failed to import stock data');
		}
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
			array('full', null, InputOption::VALUE_NONE, 'Full daily import?'),
		);
	}

}
