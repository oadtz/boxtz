<?php namespace Service;

use Auth;
use Company;
use User;
use Screener;
use WebSocket;
use MongoId;
use MongoRegex;
use Exception\PermissionException;
use ServiceInterface\UserServiceInterface;

class UserService extends BaseService implements UserServiceInterface {

	
	public function findById($id, $cache = null)
	{
		return  User::findById($id, $cache)->first();
	}

	public function findByUsername($username, $cache = null)
	{
		return User::findByUsername($username, $cache)->first();
	}
	
	public function signin($data)
	{
		$this->validate($data, [
			'login'		=>	'required|user_login_exists',
			'password'	=>	'required'
		]);

		if (!Auth::attempt(['username' => new MongoRegex('/^' . $data['login'] . '$/i'), 'password' => $data['password']], array_key_exists('remember_flag', $data) ? $data['remember_flag'] : false) 
			&& !Auth::attempt(['email' => new MongoRegex('/^' . $data['login'] . '$/i'), 'password' => $data['password']], array_key_exists('remember_flag', $data) ? $data['remember_flag'] : false))  
			throw new PermissionException('Login Failed.');	

		//$user = $this->findById(Auth::user()->_id);

		//return $user;
		return true;
	}

	public function signup($data)
	{
		$this->validate($data, array_merge(
									User::$rules,
									[
										'email'		=> 'required|email|user_email_unique',
										'username'	=> 'required|regex:/^[\w._-]+$/i|user_username_unique',
										'password2'	=> 'required|same:password'
									]
								));

		return User::create($data);
	}

	public function signout()
	{
		return Auth::logout();
	}

	public function store($data)
	{
		$this->validate($data, User::$rules);

		return User::create($data);
	}

	public function getCurrentUser()
	{
		if (Auth::check())
			return Auth::user();

		return false;
	}

	public function getFavorites()
	{
		$favorites = [];

		if ($user = User::findById(Auth::user()->id)->first())
			$favorites = $user->favorites;

		//var_dump ($favorites);

		$total = intval(Company::findByIds($favorites, 1)->count());

		if ($total > 0)
			$rows = Company::findByIds($favorites, 1)->orderBy('symbol', 'asc')->get()->toArray();
		else
			$rows = [];

		return Company::formatResult($rows, $total);
	}

	public function getScreeners()
	{
		$total = intval(Screener::findByOwner(Auth::user()->id)->count());

		if ($total > 0)
			$rows = Screener::findByOwner(Auth::user()->id)->orderBy('name', 'asc')->get()->toArray();
		else
			$rows = 0;

		return Screener::formatResult($rows, $total);
	}

	public function saveAlert($alert)
	{
		$user = Auth::user();

		if (is_array($alert)) {
			$user->alert = $alert;

			$user->save();

			return $user->alert;
		}

		return null;
	}

	public function addFavorite($companySymbol)
	{			
		$user = Auth::user();

		if ($company = Company::findBySymbol($companySymbol, 1)->first()) {

			if (!in_array($company->_id, (array)Auth::user()->favorites)) {
				$user->addFavorite($company->id);

				if ($user->save())
					WebSocket::fire('user@' . $user->id, 'favorite:added', $user->favorites);
			}
		}

		return $user->favorites;
	}

	public function addFavorites($companySymbols)
	{
		$user = Auth::user();

		foreach ($companySymbols as $symbol) {	
			if ($company = Company::findBySymbol($symbol, 1)->first()) {

				if (!in_array($company->_id, (array)Auth::user()->favorites)) {
					$user->addFavorite($company->id);
				}
			}
		}

		if ($user->save())
			WebSocket::fire('user@' . $user->id, 'favorite:added', $user->favorites);

		return $user->favorites;
	}

	public function deleteFavorite($companySymbol)
	{			
		$user = Auth::user();

		if ($company = Company::findBySymbol($companySymbol, 1)->first()) {
			if (in_array($company->_id, (array)Auth::user()->favorites)) {
				$user->deleteFavorite($company->id);

				if ($user->save())
					WebSocket::fire('user@' . $user->id, 'favorite:removed', $user->favorites);
			}
		}

		return $user->favorites;
	}

	public function deleteFavorites($companySymbols)
	{
		$user = Auth::user();

		foreach ($companySymbols as $symbol) {
			if ($company = Company::findBySymbol($symbol, 1)->first()) {
				if (in_array($company->_id, (array)Auth::user()->favorites)) {
					$user->deleteFavorite($company->id);
				}
			}
		}

		if ($user->save())
			WebSocket::fire('user@' . $user->id, 'favorite:removed', $user->favorites);

		return $user->favorites;
	}

}