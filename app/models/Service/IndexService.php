<?php namespace Service;

use Config;
use Index;
use MongoId;
use MongoRegex;
use ServiceInterface\IndexServiceInterface;

class IndexService extends BaseService implements IndexServiceInterface {
	
	public function query($query = null, $search = null, $orderBy = null, $paging = null, $caseInsensitive = true, $cache = null)
	{
		return Index::getQueryResult($query, $search, $orderBy, $paging, $caseInsensitive, $cache);
	}

}