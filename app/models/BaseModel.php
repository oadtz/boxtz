<?php

class BaseModel extends Moloquent {

	public static $rules = [];

	public static function getQueryResult($query = null, $search = null, $order = null, $paging = null, $caseInsensitive = true, $cache = null)
	{
		$q = static::applyQuery($query, $caseInsensitive)->applySearch($search, $caseInsensitive)->remember($cache);

		$total = intval($q->count());

		if ($total > 0)
			$rows = $q->applyOrder($order)->applyPaging($paging)->get()->toArray();
		else
			$rows = [];


		return static::formatResult($rows, $total);
	}

	public static function formatResult($rows, $total = null)
	{
		return [
			'TotalRows'		=>	$total,
			'Rows'			=>	$rows
		];
	}

	public function scopeFindById($query, $id, $cache = null)
	{
		return $query->where('_id', new MongoId($id))->remember($cache);
	}
	
	public function scopeApplySearch($query, $q = null, $caseInsensitive = true)
	{
		// Query
		if ($q) {
			/*$query->where(function ($query) use($q, $caseInsensitive) {
				foreach((array)$q as $field=>$value) {
					if ($caseInsensitive)
						$query->orWhere($field, new MongoRegex('/' . $value . '/i'));
					else
						$query->orWhere($field, $value);
				}
			});*/
			foreach((array)$q as $field=>$value) {
				if ($caseInsensitive)
					$query->orWhere($field, new MongoRegex('/' . $value . '/i'));
				else
					$query->orWhere($field, $value);
			}
		}

		return $query;
	}
	
	public function scopeApplyQuery($query, $q = null, $caseInsensitive = true)
	{
		// Query
		if ($q) {
			foreach((array)$q as $field=>$value) {
				if (is_array($value)) {
					if ($caseInsensitive && is_string($value))
						$query->whereIn($field, array_map(function ($value) { return new MongoRegex('/^' . preg_quote($value) . '$/i'); }, $value));
					else
						$query->whereIn($field, $value);
				} else {
					if ($caseInsensitive && is_string($value))
						$query->where($field, new MongoRegex('/^' . preg_quote($value) . '$/i'));
					else
						$query->where($field, $value);
				}
			}
		}

		return $query;
	}

	public function scopeApplyOrder($query, $o = null)
	{
		// Order
		if ($o && array_key_exists('order_by', (array)$o)) {
			$query->orderBy($o['order_by'], isset($o['order_dir']) ? $o['order_dir'] : null);
		}

		return $query;
	}

	public function scopeApplyPaging($query, $p = null)
	{
		// Paging
		if ($p && array_key_exists('limit', (array)$p)) {
			$limit = isset($p['limit']) ? intval($p['limit']) : 0;
			$page = isset($p['page']) ? intval($p['page']) : 1;

			$query = $query->skip($limit * ($page - 1))->take($limit);
		}

		return $query;
	}


	public function getIdAttribute($value)
	{
		if (array_key_exists('_id', $this->attributes))
			return (string)$this->attributes['_id'];
	}

}