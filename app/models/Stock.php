<?php

class Stock {

	public static function getIndexes()
	{
		$query = array_merge(
			array(
				'currency == "'.Config::get('stock.currency').'"', 
				'exchange == "INDEXBKK"'
			),
			array(
				// Price
				'last_price >= 0',
				'change_today_percent > -99999999',
				'high_52week > -99999999',
				'low_52week > -99999999',
				'average_50day_price >= 0',
				'average_150day_price >= 0',
				'average_200day_price >= 0',
				'price_change_13week > -99999999',
				'price_change_26week > -99999999',
				'price_change_52week > -99999999',

				// Volume
				'volume >= 0',
				'average_volume >= 0',
			)
		);

		$url = 'https://www.google.com/finance?output=json&start=0&num=10000&noIL=1&q=[' . urlencode(implode(' & ', $query)) . ']&restype=company&ei=SE8xU-jiNYi7sQeCEw&rand=' . md5(time());

		$result = API::invokeRemote($url, 'GET');

		$result = preg_replace_callback('/\\\\x([[:xdigit:]]{2})/ism', function($matches)
	    {
	        return htmlspecialchars(chr(hexdec($matches[1])));
	    },
	    $result);
		
		return json_decode($result, true);
	}

	public static function getCompanies($section = 1)
	{
		$query = array_merge(
			array(
				'currency == "'.Config::get('stock.currency').'"', 
				'exchange == "'.Config::get('stock.market').'"'
			),
			Config::get('stock.query' . $section)
		);

		$url = 'https://www.google.com/finance?output=json&start=0&num=10000&noIL=1&q=[' . urlencode(implode(' & ', $query)) . ']&restype=company&ei=SE8xU-jiNYi7sQeCEw&rand=' . md5(time());

		$result = API::invokeRemote($url, 'GET');
		
		//Log::info($result);

		$result = preg_replace_callback('/\\\\x([[:xdigit:]]{2})/ism', function($matches)
	    {
	        return htmlspecialchars(chr(hexdec($matches[1])));
	    },
	    $result);
		
		return json_decode($result, true);
	}

	public static function getMores($symbol)
	{
		$url = 'http://www.bloomberg.com/quote/'.$symbol.':' .Config::get('stock.market_abbr');
		$properties = [];

		if (!$result = API::invokeRemote($url, 'GET')){
			$properties['industry'] = $properties['sector'] = $properties['dividend_xd_date'] = null;

			return false;
		}

		$exchangeType = htmlqp($result, 'div.exchange_type');

		$properties['sector'] = trim(htmlqp($exchangeType, 'span:contains("Sector:")')
								->next('span')->text());
		$properties['industry'] = trim(strtok(htmlqp($exchangeType, 'span:contains("Industry:")')
								->next('span')->text(), "\n"));
		$properties['sub_industry'] = trim(htmlqp($exchangeType, 'span:contains("Sub-Industry:")')
								->next('span')->text());
		$properties['dividend_xd_date'] = trim(htmlqp($result, 'table.key_stat_data th:contains("Dividend Ex-Date")')
										->next('td.company_stat')->text());

		return $properties;
	}

	public static function getAllCompanies()
	{	
		$result1 = self::getCompanies(1);

		if (!array_key_exists('searchresults', $result1))
			return false;

		$result2 = self::getCompanies(2);

		if (!array_key_exists('searchresults', $result2))
			return false;

		return array_merge_recursive(static::mapResult($result1['searchresults']), static::mapResult($result2['searchresults']));

	}

	public static function mapResult($source)
	{

        return array_map(function ($company)
                        {

                                return array_filter([
                                                                        'name'                          =>      $company['title'],
                                                                        'ref_id'                        =>      $company['id'],
                                                                        'symbol'                        =>      $company['ticker'],
                                
                                                                        // Price
                                                                        'price_last_quote'      =>      self::getColumnValue($company['columns'], 'QuoteLast'),
                                                                        'price_eps'             => self::getColumnValue($company['columns'], 'EPS'),
                                                                        'price_change_today' => self::getColumnValue($company['columns'], 'QuotePercChange'),
                                                                        'price_high_52week' => self::getColumnValue($company['columns'], 'High52Week'),
                                                                        'price_low_52week' => self::getColumnValue($company['columns'], 'Low52Week'),
                                                                        'price_avg_50day' => self::getColumnValue($company['columns'], 'Price50DayAverage'),
                                                                        'price_avg_150day' => self::getColumnValue($company['columns'], 'Price150DayAverage'),
                                                                        'price_avg_200day' => self::getColumnValue($company['columns'], 'Price200DayAverage'),
                                                                        'price_change_13week' => self::getColumnValue($company['columns'], 'Price13WeekPercChange'),
                                                                        'price_change_26week' => self::getColumnValue($company['columns'], 'Price26WeekPercChange'),
                                                                        'price_change_52week' => self::getColumnValue($company['columns'], 'Price52WeekPercChange'),
                                
                                                                        // Volume
                                                                        'volume_volume'         =>      self::getColumnValue($company['columns'], 'Volume'),
                                                                        'volume_avg_volume'     =>      self::getColumnValue($company['columns'], 'Avg Volume'),
                                
                                                                        // Valuation
                                                                        'value_market_cap'              =>      self::getColumnValue($company['columns'], 'MarketCap'),
                                                                        'value_pe'                              =>      self::getColumnValue($company['columns'], 'PE'),
                                                                        'value_pe_1year'                =>      self::getColumnValue($company['columns'], 'ForwardPE1Year'),
                                
                                                                        // Dividend
                                                                        'dividend_recent_quarter'       =>      self::getColumnValue($company['columns'], 'DividendRecentQuarter'),
                                                                        'dividend_next_quarter'         =>      self::getColumnValue($company['columns'], 'DividendNextQuarter'),
                                                                        'dividend_per_share'            =>      self::getColumnValue($company['columns'], 'DPSRecentYear'),
                                                                        'dividend_next_year'            =>      self::getColumnValue($company['columns'], 'IAD'),
                                                                        'dividend_per_share_trailing_12months'          =>      self::getColumnValue($company['columns'], 'DividendPerShare'),
                                                                        'dividend_yield'                =>      self::getColumnValue($company['columns'], 'DividendYield'),
                                                                        'dividend_recent_year'          =>      self::getColumnValue($company['columns'], 'Dividend'),
                                
                                                                        // Financial
                                                                        'finance_book_value_per_share_year'             =>      self::getColumnValue($company['columns'], 'BookValuePerShareYear'),
                                                                        'finance_price_to_book'         =>      self::getColumnValue($company['columns'], 'PriceToBook'),
                                										'finance_cash_per_share_year'         =>      self::getColumnValue($company['columns'], 'CashPerShareYear'),
                                										'finance_current_assets_to_liabilities_ratio_year'         =>      self::getColumnValue($company['columns'], 'CurrentRatioYear'),
                                										'finance_longterm_debt_to_assets_year'         =>      self::getColumnValue($company['columns'], 'LTDebtToAssetsYear'),
                                										'finance_longterm_debt_to_assets_quarter'         =>      self::getColumnValue($company['columns'], 'LTDebtToAssetsQuarter'),
                                										'finance_total_debt_to_assets_year'         =>      self::getColumnValue($company['columns'], 'TotalDebtToAssetsYear'),
                                										'finance_total_debt_to_assets_quarter'         =>      self::getColumnValue($company['columns'], 'TotalDebtToAssetsQuarter'),
                                										'finance_longterm_debt_to_equity_year'         =>      self::getColumnValue($company['columns'], 'LTDebtToEquityYear'),
                                										'finance_longterm_debt_to_equity_quarter'        =>      self::getColumnValue($company['columns'], 'LTDebtToEquityQuarter'),
                                										'finance_total_debt_to_equity_year'        =>      self::getColumnValue($company['columns'], 'TotalDebtToEquityYear'),
                                										'finance_total_debt_to_equity_quarter'        =>      self::getColumnValue($company['columns'], 'TotalDebtToEquityQuarter'),
                                
                                                                        // Growth
                                                                        'growth_net_income_5year'		=>      self::getColumnValue($company['columns'], 'NetIncomeGrowthRate5Years'),
                                                                        'growth_revenue_5year'			=>      self::getColumnValue($company['columns'], 'RevenueGrowthRate5Years'),
                                                                        'growth_revenue_10year'			=>      self::getColumnValue($company['columns'], 'RevenueGrowthRate10Years'),
                                                                        'growth_eps_5year'				=>      self::getColumnValue($company['columns'], 'EPSGrowthRate5Years'),
                                                                        'growth_eps_10year'				=>      self::getColumnValue($company['columns'], 'EPSGrowthRate10Years'),
                                                                ]);
                                                        }, $source);
	}

	public static function getAllIndexes()
	{	
		$result = self::getIndexes();

		if (!array_key_exists('searchresults', $result))
			return false;

		return array_values(array_filter(array_map(function ($index) 
														{
															return [
																	'name'				=>	$index['title'],
																	'ref_id'			=>	$index['id'],
																	'symbol'			=>	$index['ticker'],
																	
																	// Price
																	'price_last_quote'	=>	self::getColumnValue($index['columns'], 'QuoteLast'),
																	'price_change_today' => self::getColumnValue($index['columns'], 'QuotePercChange'),
																	'price_high_52week' => self::getColumnValue($index['columns'], 'High52Week'),
																	'price_low_52week' => self::getColumnValue($index['columns'], 'Low52Week'),
																	'price_avg_50day' => self::getColumnValue($index['columns'], 'Price50DayAverage'),
																	'price_avg_150day' => self::getColumnValue($index['columns'], 'Price150DayAverage'),
																	'price_avg_200day' => self::getColumnValue($index['columns'], 'Price200DayAverage'),
																	'price_change_13week' => self::getColumnValue($index['columns'], 'Price13WeekPercChange'),
																	'price_change_26week' => self::getColumnValue($index['columns'], 'Price26WeekPercChange'),
																	'price_change_52week' => self::getColumnValue($index['columns'], 'Price52WeekPercChange'),
																	
																	// Volume
																	'volume_volume'		=>	self::getColumnValue($index['columns'], 'Volume'),
																	'volume_avg_volume'	=>	self::getColumnValue($index['columns'], 'Avg Volume'),
																];
															}, $result['searchresults']),
									function ($index) {
										return in_array($index['symbol'], ['SET','SET100','SET50','SETHD']);
									}));
	}

	public static function getColumnValue($columns, $field)
	{
		foreach ($columns as $column) {
			if ($field == $column['field'])
				return $column['value'];
		}

		return null;
	}

}