<?php

class SiteController extends BaseController {

	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('auth', [	
										'except'	=> ['getSignin', 'getSignup']
									]);
	}
	
	public function getIndex()
	{
		$this->layout->content = View::make('site.index');
	}

	public function getSignin()
	{
		return View::make('site.signin');
	}

	public function getSignup()
	{
		return View::make('site.signup');
	}

	public function getFavorites()
	{
		return Redirect::to('user/' . Auth::user()->username . '/favorites');
	}

	public function getScreeners()
	{
		return Redirect::to('user/' . Auth::user()->username . '/screeners');
	}

}