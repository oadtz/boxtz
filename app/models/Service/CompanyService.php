<?php namespace Service;

use Config;
use Company;
use MongoId;
use MongoRegex;
use ServiceInterface\CompanyServiceInterface;

class CompanyService extends BaseService implements CompanyServiceInterface {
	
	public function query($query = null, $search = null, $orderBy = null, $paging = null, $caseInsensitive = true, $cache = null)
	{
		return Company::getQueryResult($query, $search, $orderBy, $paging, $caseInsensitive, $cache);
	}
	
	public function findById($id, $cache = null)
	{
		return Company::findById($id, $cache)->first();
	}

	public function findBySymbol($symbol, $cache = null)
	{
		return Company::findBySymbol($symbol, $cache)->first();
	}

	public function store($data)
	{
		$this->validate($data, Company::$rules);

		return Company::create($data);
	}

}