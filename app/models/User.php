<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

	protected $collection = 'user';
	protected $fillable = ['username', 'password', 'email'];
	protected $hidden = ['password', '_id'];
	public static $rules = [
		'email'		=> 'required|email',
		'username'	=> 'required|regex:/^[\w._-]+$/i',
		'password'	=> 'required|min:6'
	];
	public $appends = ['id', 'favorites'];

	public function scopeFindById($query, $id, $cache = null)
	{
		return $query->where('_id', new MongoId($id))->remember($cache);
	}

	public function scopeFindByUsername($query, $username, $cache = null)
	{
		return $query->where('username', new MongoRegex('/^' . $username . '$/i'))->remember($cache);
	}

	public function scopeGetUsersByFavorite($query, $companyId, $cache = null)
	{
		return $query->where('favorites', new MongoId($companyId))->remember($cache);
	}

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getReminderEmail()
	{
		return $this->email;
	}

        public function getRememberToken()
        {
                return true;
        }


        public function setRememberToken($value)
        {
                return true;
        }


        /**
         * Get the column name for the "remember me" token.
         *
         * @return string
         */
        public function getRememberTokenName()
        {
                return true;
        }


	public function addFavorite($companyId)
	{
		if (!array_key_exists('favorites', $this->attributes) || !is_array($this->attributes['favorites']))
			$this->attributes['favorites'] = [];

		$this->attributes['favorites'][] = new MongoId($companyId);

		$this->attributes['favorites'] = array_unique($this->attributes['favorites']);
	}

	public function deleteFavorite($companyId)
	{
		if (array_key_exists('favorites', $this->attributes) && is_array($this->attributes['favorites'])) {
			if (($i = array_search(new MongoId($companyId), $this->attributes['favorites'])) !== false)
				unset($this->attributes['favorites'][$i]);
		}

		$this->attributes['favorites'] = array_values(array_unique($this->attributes['favorites']));
	}

	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
	}

	public function getFavoritesAttribute($value)
	{
		if (array_key_exists('favorites', $this->attributes))
			return array_map(function ($_id) { return (string)$_id; }, (array)$this->attributes['favorites']);
		
		return [];
	}

	public function setAlertAttribute($value)
	{
		if (!array_key_exists('alert', $this->attributes))
			$this->attributes['alert']	= [];

		
		$this->attributes['alert'] = [
			'ceiling'		=>	array_key_exists('ceiling', $value) &&  floatval($value['ceiling']) > 0 ? floatval($value['ceiling']) : null,
			'floor'			=>	array_key_exists('floor', $value) &&  floatval($value['floor']) > 0 ? floatval($value['floor']) : null
		];
	}

}
