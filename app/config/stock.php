<?php

return [
	'market'		=>	'BKK',
	'market_abbr'	=>	'TB',
	'currency'		=>	'THB',
	'query1'		=>	[
				// Price
				'last_price >= 0',
				'earnings_per_share > -99',
				'change_today_percent > -99',
				'high_52week >= 0',
				'low_52week >= 0',
				'average_50day_price >= 0',
				'average_150day_price >= 0',
				'average_200day_price >= 0',
				'price_change_13week > -99',
				'price_change_26week > -99',
				'price_change_52week > -99',

				// Volumn
				'volume >= 0',
				'average_volume >= 0',

				// Valuation
				'market_cap >= 0', 
				'pe_ratio >= 0', 
				'forward_pe_1year >= 0', 

				// Dividen
				'dividend_recent_quarter >= 0',
				'dividend_next_quarter >= 0',
				'dividend_per_share >= 0',
				'dividend_next_year >= 0',
				'dividend_per_share_trailing_12months >= 0',
				'dividend_yield >= 0',
				'dividend_recent_year >= 0',
	],
	'query2'		=>	[
				// Financial
				'book_value_per_share_year > -99',
				'price_to_book >= 0',
				'cash_per_share_year >= 0',
				'current_assets_to_liabilities_ratio_year >= 0',
				'longterm_debt_to_assets_year >= 0',
				'longterm_debt_to_assets_quarter >= 0',
				'total_debt_to_assets_year >= 0',
				'total_debt_to_assets_quarter >= 0',
				'longterm_debt_to_equity_year >= 0',
				'longterm_debt_to_equity_quarter >= 0',
				'total_debt_to_equity_year >= 0',
				'total_debt_to_equity_quarter >= 0',

				// Growth
				'net_income_growth_rate_5years > -99',
				'revenue_growth_rate_5years > -99',
				'revenue_growth_rate_10years > -99',
				'eps_growth_rate_5years > -99',
				'eps_growth_rate_10years > -99'
	],
	'query3'		=>	[
				// Financial
				'book_value_per_share_year > -99',
				'price_to_book >= 0',
				'cash_per_share_year >= 0',
				'current_assets_to_liabilities_ratio_year >= 0',
				'longterm_debt_to_assets_year >= 0',
				'longterm_debt_to_assets_quarter >= 0',
				'total_debt_to_assets_year >= 0',
				'total_debt_to_assets_quarter >= 0',
				'longterm_debt_to_equity_year >= 0',
				'longterm_debt_to_equity_quarter >= 0',
				'total_debt_to_equity_year >= 0',
				'total_debt_to_equity_quarter >= 0',

				// Growth
				'net_income_growth_rate_5years > -99',
				'revenue_growth_rate_5years > -99',
				'revenue_growth_rate_10years > -99',
				'eps_growth_rate_5years > -99',
				'eps_growth_rate_10years > -99'
	],
];