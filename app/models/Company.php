<?php

class Company extends BaseModel {

	protected $collection = 'company';
	public $fillable = [
					'name',
					'ref_id',
					'symbol',
					'sector',
					'industry',
					'sub_industry',
						
					// Price
					'price_last_close',
					'price_prev_quote',
					'price_last_quote',
					'price_eps',
					'price_change_today',
					'price_high_52week',
					'price_low_52week',
					'price_avg_50day',
					'price_avg_150day',
					'price_avg_200day',
					'price_change_13week',
					'price_change_26week',
					'price_change_52week',

					// Valuation
					'value_market_cap',
					'value_pe',
					'value_pe_1year',

					// Volume
					'volume_volume',
					'volume_avg_volume',

					// Dividend
					'dividend_recent_quarter',
					'dividend_next_quarter',
					'dividend_per_share',
					'dividend_next_year',
					'dividend_per_share_trailing_12months',
					'dividend_yield',
					'dividend_recent_year',
					'dividend_xd_date',

					// Financial
					'finance_book_value_per_share_year',
					'finance_price_to_book',
					'finance_cash_per_share_year',
					'finance_current_assets_to_liabilities_ratio_year',
					'finance_longterm_debt_to_assets_year',
					'finance_longterm_debt_to_assets_quarter',
					'finance_total_debt_to_assets_year',
					'finance_total_debt_to_assets_quarter',
					'finance_longterm_debt_to_equity_year',
					'finance_longterm_debt_to_equity_quarter',
					'finance_total_debt_to_equity_year',
					'finance_total_debt_to_equity_quarter',

					// Growth
                    'growth_net_income_5year',
                    'growth_revenue_5year',
                    'growth_revenue_10year',
                    'growth_eps_5year',
                    'growth_eps_10year',
			];

	protected $hidden = ['_id'];
	public $appends = ['id', 'dividend'];

	public static function boot()
	{
		parent::boot();

		static::updating(function ($obj) 
		{
			$prevPrice = $obj->getOriginal('price');
			$lastPrice = $obj->price;

			$obj->is_new_flag = false;

			if (is_array($prevPrice) && floatval($prevPrice['last_quote']) != floatval($lastPrice['last_quote'])) {
				//WebSocket::fire('default', 'company:price_changed', $obj->getAttributes());
				$obj->price_changed_flag = true;
			} else {
				$obj->price_changed_flag = false;
			}

			$obj->price_prev_quote = floatval($prevPrice['last_quote']);
		});

		/*static::updated(function ($obj) {
			if ($obj->price['prev_quote'] != $obj->price['last_quote']) {
				$feed = new Feed([
					'event'		=>	$obj->price['prev_quote'] < $obj->price['last_quote'] ? 'company:price_up' : 'company:price_down',
					'data'		=>	$obj->toArray()
				]);

				$feed->save();
			}
		});*/

		static::creating(function ($obj) 
		{
			//WebSocket::fire('default', 'company:added', $obj->getAttributes());
			$obj->is_new_flag = true;
		});

		/*static::created(function ($obj)
		{
			$feed = new Feed([
				'event'			=>	'company:added',
				'data'			=>	$obj->toArray()
			]);

			$feed->save();
		});*/

		static::saving(function ($obj) 
		{
			if ($obj->price['change_today'] == 0)
				$obj->price_last_close = $obj->price['last_quote'];
		});
	}

	public function scopeFindByIds($query, $ids, $cache = null)
	{
		$ids = array_map(function ($id) { return new MongoId($id); }, $ids);

		return $query->whereIn('_id', $ids)->remember($cache);
	}

	public function scopeFindBySymbol($query, $symbol, $cache = null)
	{
		return $query->where('symbol', new MongoRegex('/^' . $symbol . '$/i'))->remember($cache);
	}

	public function scopeGetChangedStocks($query, $cache = null)
	{
		return $query->where('price_changed_flag', true)->remember($cache);
	}

	public function scopeGetNewStocks($query, $cache = null)
	{
		return $query->where('is_new_flag', true)->remember($cache);
	}

	public function scopeGetByFilters($query, $filters, $cache = null)
	{
		foreach ((array)$filters as $filter)
		{
			if (isset($filter['min']))
				$query = $query->where($filter['name'], '>=', floatval($filter['min']));


			if (isset($filter['max']))
				$query = $query->where($filter['name'], '<=', floatval($filter['max']));
		}

		return $query;
	}

	// Field: price_last_close
	public function setPriceLastCloseAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$this->attributes['price']['last_close'] = floatval($value);
	}

	// Field: price_prev_quote
	public function setPricePrevQuoteAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$this->attributes['price']['prev_quote'] = floatval($value);
	}

	public function setPriceLastQuoteAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['last_quote'] = floatval($value);
	}

	// Field: price_eps
	public function setPriceEpsAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$this->attributes['price']['eps'] = floatval($value);
	}

	// Field: price_change_today
	public function setPriceChangeTodayAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['change_today'] = floatval($value);
	}

	// Field: price_high_52week
	public function setPriceHigh52WeekAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['high_52week'] = floatval($value);
	}

	// Field: price_low_52week
	public function setPriceLow52WeekAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['low_52week'] = floatval($value);
	}

	// Field: price_avg_50day
	public function setPriceAvg50DayAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['avg_50day'] = floatval($value);
	}

	// Field: price_avg_150day
	public function setPriceAvg150DayAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['avg_150day'] = floatval($value);
	}

	// Field: price_avg_200day
	public function setPriceAvg200DayAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['avg_200day'] = floatval($value);
	}

	// Field: price_change_13week
	public function setPriceChange13WeekAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['change_13week'] = floatval($value);
	}

	// Field: price_change_26week
	public function setPriceChange26WeekAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];

		$value = str_replace(',', '', $value);

		$this->attributes['price']['change_26week'] = floatval($value);
	}

	// Field: price_change_52week
	public function setPriceChange52WeekAttribute($value)
	{
		if (!array_key_exists('price', $this->attributes))
			$this->attributes['price']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['price']['change_52week'] = floatval($value);
	}

	// Field: volume_volume
	public function setVolumeVolumeAttribute($value)
	{
		if (!array_key_exists('volume', $this->attributes))
			$this->attributes['volume']	= [];

		$value = trim($value);

		if (strtolower(substr($value, -1)) == 'm')
			$value = floatval($value) * 1000000;
		else if (strtolower(substr($value, -1)) == 'b')
			$value = floatval($value) * 1000000000;
		else
			$value = intval($value);

		$this->attributes['volume']['volume'] = $value;
	}

	// Field: volume_avg_volume
	public function setVolumeAvgVolumeAttribute($value)
	{
		if (!array_key_exists('volume', $this->attributes))
			$this->attributes['volume']	= [];

		$value = trim($value);

		if (strtolower(substr($value, -1)) == 'm')
			$value = floatval($value) * 1000000;
		else if (strtolower(substr($value, -1)) == 'b')
			$value = floatval($value) * 1000000000;
		else
			$value = intval($value);

		$this->attributes['volume']['avg_volume'] = $value;
	}

	// Field: value_market_cap
	public function setValueMarketCapAttribute($value)
	{
		if (!array_key_exists('value', $this->attributes))
			$this->attributes['value']	= [];

		$value = trim($value);

		if (strtolower(substr($value, -1)) == 'm')
			$value = floatval($value) * 1000000;
		else if (strtolower(substr($value, -1)) == 'b')
			$value = floatval($value) * 1000000000;
		else
			$value = intval($value);

		$this->attributes['value']['market_cap'] = $value;
	}

	// Field: value_pe
	public function setValuePEAttribute($value)
	{
		if (!array_key_exists('value', $this->attributes))
			$this->attributes['value']	= [];

		$this->attributes['value']['pe'] = floatval($value);
	}

	// Field: value_pe_1year
	public function setValuePE1YearAttribute($value)
	{
		if (!array_key_exists('value', $this->attributes))
			$this->attributes['value']	= [];

		$this->attributes['value']['pe_1year'] = floatval($value);
	}

	// Field: dividend_recent_quarter
	public function setDividendRecentQuarterAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		$this->attributes['dividend']['recent_quarter'] = floatval($value);
	}

	// Field: dividend_next_quarter
	public function setDividendNextQuarterAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		$this->attributes['dividend']['next_quarter'] = floatval($value);
	}

	// Field: dividend_per_share
	public function setDividendPerShareAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		$this->attributes['dividend']['per_share'] = floatval($value);
	}

	// Field: dividend_next_year
	public function setDividendNextYearAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		$this->attributes['dividend']['next_year'] = floatval($value);
	}

	// Field: dividend_per_share_trailing_12months
	public function setDividendPerShareTrailing12MonthsAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		$this->attributes['dividend']['per_share_12month'] = floatval($value);
	}

	// Field: dividend_yield
	public function setDividendYieldAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		$this->attributes['dividend']['yield'] = floatval($value);
	}

	// Field: dividend_recent_year
	public function setDividendRecentYearAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		$this->attributes['dividend']['recent_year'] = floatval($value);
	}

	// Field: dividend_xd_date
	public function setDividendXDDateAttribute($value)
	{
		if (!array_key_exists('dividend', $this->attributes))
			$this->attributes['dividend']	= [];

		if (!empty($value) && $value = strtotime($value)) {
			$now = new Datetime();
			$xd = new DateTime(date('Y-m-d', $value));


			$this->attributes['dividend']['xd_date'] = new MongoDate($value);
			$this->attributes['dividend']['days_to_xd'] = $now <= $xd ? intval($now->diff($xd)->days) : 0;
		} else {
			$this->attributes['dividend']['xd_date'] = null;
			$this->attributes['dividend']['days_to_xd'] = null;
		}
	}

	// Field: finance_book_value_per_share_year
	public function setFinanceBookValuePerShareYearAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['bv'] = floatval($value);
	}

	// Field: finance_price_to_book
	public function setFinancePriceToBookAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['pbv'] = floatval($value);
	}

	// Field: finance_cash_per_share_year
	public function setFinanceCashPerShareYearAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['cash_per_share_year'] = floatval($value);
	}

	// Field: finance_current_assets_to_liabilities_ratio_year
	public function setFinanceCurrentAssetsToLiabilitiesRatioYearAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['current_assets_to_liabilities_ratio_year'] = floatval($value);
	}

	// Field: finance_longterm_debt_to_assets_year
	public function setFinanceLongtermDebtToAssetsYearAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['longterm_debt_to_assets_year'] = floatval($value);
	}

	// Field: finance_longterm_debt_to_assets_quarter
	public function setFinanceLongtermDebtToAssetsQuarterAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['longterm_debt_to_assets_quarter'] = floatval($value);
	}

	// Field: finance_total_debt_to_assets_year
	public function setFinanceTotalDebtToAssetsYearAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['total_debt_to_assets_year'] = floatval($value);
	}

	// Field: finance_total_debt_to_assets_quarter
	public function setFinanceTotalDebtToAssetsQuarterAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['total_debt_to_assets_quarter'] = floatval($value);
	}

	// Field: finance_longterm_debt_to_equity_year
	public function setFinanceLongtermDebtToEquityYearAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['longterm_debt_to_equity_year'] = floatval($value);
	}

	// Field: finance_longterm_debt_to_equity_quarter
	public function setFinanceLongtermDebtToEquityQuarterAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['longterm_debt_to_equity_quarter'] = floatval($value);
	}

	// Field: finance_total_debt_to_equity_year
	public function setFinanceTotalDebtToEquityYearAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['total_debt_to_equity_year'] = floatval($value);
	}

	// Field: finance_total_debt_to_equity_quarter
	public function setFinanceTotalDebtToEquityQuarterAttribute($value)
	{
		if (!array_key_exists('finance', $this->attributes))
			$this->attributes['finance']	= [];
		
		$value = str_replace(',', '', $value);

		$this->attributes['finance']['total_debt_to_equity_quarter'] = floatval($value);
	}

	// Field: growth_net_income_5year
	public function setGrowthNetIncome5YearAttribute($value)
	{
		if (!array_key_exists('growth', $this->attributes))
			$this->attributes['growth']	= [];

		$this->attributes['growth']['net_income_5year'] = floatval($value);
	}

	// Field: growth_revenue_5year
	public function setGrowthRevenue5YearAttribute($value)
	{
		if (!array_key_exists('growth', $this->attributes))
			$this->attributes['growth']	= [];

		$this->attributes['growth']['revenue_5year'] = floatval($value);
	}

	// Field: growth_revenue_10year
	public function setGrowthRevenue10YearAttribute($value)
	{
		if (!array_key_exists('growth', $this->attributes))
			$this->attributes['growth']	= [];

		$this->attributes['growth']['revenue_10year'] = floatval($value);
	}

	// Field: growth_eps_5year
	public function setGrowthEps5YearAttribute($value)
	{
		if (!array_key_exists('growth', $this->attributes))
			$this->attributes['growth']	= [];

		$this->attributes['growth']['eps_5year'] = floatval($value);
	}

	// Field: growth_eps_10year
	public function setGrowthEps10YearAttribute($value)
	{
		if (!array_key_exists('growth', $this->attributes))
			$this->attributes['growth']	= [];

		$this->attributes['growth']['eps_10year'] = floatval($value);
	}

	public function getDividendAttribute($value)
	{
		if (array_key_exists('dividend', $this->attributes) && array_key_exists('xd_date', (array)$this->attributes['dividend']) && is_object($this->attributes['dividend']['xd_date'])) {
			return array_merge(
				$this->attributes['dividend'],
				[
					'xd_date'		=>	date('Y-m-d', $this->attributes['dividend']['xd_date']->sec)
				]
			);
		}
	}

}