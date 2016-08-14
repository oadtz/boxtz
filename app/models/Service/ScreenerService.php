<?php namespace Service;

use Config;
use Company;
use Screener;
use MongoId;
use MongoRegex;
use ServiceInterface\ScreenerServiceInterface;

class ScreenerService extends BaseService implements ScreenerServiceInterface {
	
	public function query($query = null, $search = null, $orderBy = null, $paging = null, $caseInsensitive = true, $cache = null)
	{
		return Screener::getQueryResult($query, $search, $orderBy, $paging, $caseInsensitive, $cache);
	}
	
	public function findById($id, $cache = null)
	{
		return Screener::findById($id, $cache)->first();
	}

	public function store($data)
	{
		$this->validate($data, Screener::$rules);

		return Screener::create($data);
	}

	public function update($id, $data)
	{
		$this->validate($data, Screener::$rules);

		$screener = Screener::findById($id)->first();

		$screener->fill($data);

		$screener->save();

		return $screener;
	}

	public function destroy($id)
	{
		$screener = Screener::findById($id)->first();

		if ($screener->delete())
			return $screener;

		return false;
	}

	public function getStocks($id)
	{
		$screener = Screener::findById($id)->first();

		$q = Company::getByFilters($screener->filters, 1);

		$total = intval($q->count());

		if ($total > 0)
			$rows = $q->get()->toArray();
		else
			$rows = [];

		return [
			'Rows'			=>	$rows,
			'TotalRows'		=>	$total
		];
	}

}