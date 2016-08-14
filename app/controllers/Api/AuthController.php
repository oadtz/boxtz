<?php namespace Api;

use Input;
use Response;
use Service\UserService;

class AuthController extends BaseController {

	public function __construct(UserService $userService)
	{
		parent::__construct();

		$this->userService = $userService;

		$this->beforeFilter('auth', [
				'only'	=>	['getFavorites', 'getScreeners']
		]);
	}

	public function postSignin()
	{
		return Response::json($this->userService->signin(Input::all()));
	}
	
	public function postSignup()
	{
		return Response::json($this->userService->signup(Input::all()));
	}

	public function getSignout ()
	{
		return Response::json($this->userService->signout());
	}

	public function getUser()
	{
		return Response::json($this->userService->getCurrentUser());
	}

	public function getFavorites()
	{
		return Response::json($this->userService->getFavorites());
	}

	public function getScreeners()
	{
		return Response::json($this->userService->getScreeners());
	}

}