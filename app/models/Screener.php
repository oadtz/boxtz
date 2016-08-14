<?php

class Screener extends BaseModel {
	
	protected $collection = 'screener';
	public $fillable = ['name', 'filters', 'sort_by', 'sort_dir', 'limit'];
	public static $rules = [
		'name'		=>	'required',
		'filters'	=>	'required|min:1'
	];
	public $hidden = ['_id'];
	public $appends = ['id', 'filters'];

	public static function boot()
	{
		parent::boot();

		static::saving(function ($obj) 
		{
			$obj->owner = [
				'_id'		=>	new MongoId(Auth::user()->id),
				'username'	=>	Auth::user()->username
			];
		});
	}

	public function scopeFindByOwner($query, $ownerId, $cache = null)
	{
		return $query->where('owner._id', new MongoId($ownerId))->remember($cache);
	}

	public function getFiltersAttribute($value)
	{
		if (array_key_exists('filters', $this->attributes)) {
			return array_filter(array_values((array)$this->attributes['filters']));
		}
	}

	public function setFiltersAttribute($value)
	{
		if (is_array($value)) {
			$value = array_values($value);

			$value = array_filter(array_map(function ($filter) {
							if (trim($filter['name']) == '' || 
								((!array_key_exists('min', $filter) || trim($filter['min']) == '') && 
																(!array_key_exists('max', $filter) || trim($filter['max']) == '')))
								return null;

							return array(
								'name'		=>	trim($filter['name']),
								'min'		=>	array_key_exists('min', $filter) && trim($filter['min']) != '' ? floatval($filter['min']) : null,
								'max'		=>	array_key_exists('max', $filter) && trim($filter['max']) != '' ? floatval($filter['max']) : null
							);
						}, $value));

			$this->attributes['filters'] = $value;
		}
	}

	public function getOwnerAttribute($value)
	{
		if (array_key_exists('owner', $this->attributes))
			return [
				'id'		=>	(string)$this->attributes['owner']['_id'],
				'username'	=>	$this->attributes['owner']['username']
			];
	}

}