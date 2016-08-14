<?php

class UserController extends BaseController {

	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('auth');
	}
	

	public function getFavorites($user)
	{
		if ($user->Id != Auth::user()->Id)
			throw Exception\PermissionException('Permission Denied.');

		$this->layout->content = View::make('user.favorites');
	}

	public function getScreeners($user)
	{
		if ($user->Id != Auth::user()->Id)
			throw Exception\PermissionException('Permission Denied.');

		$this->layout->content = View::make('user.screeners');
	}

}