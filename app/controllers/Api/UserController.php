<?php namespace Api;

use Auth;
use Input;
use Response;
use Service\UserService;

class UserController extends BaseController {
	
	public function __construct(UserService $userService)
	{
		parent::__construct();

		$this->userService = $userService;

		$this->beforeFilter('auth');
	}

	public function postAlert($user)
	{
		if ($user->id != Auth::user()->id)
			throw Exception\PermissionException('Permission Denied.');

		return Response::json($this->userService->saveAlert(Input::all()));
	}

	public function postFavorite($user)
	{
		if ($user->id != Auth::user()->id)
			throw Exception\PermissionException('Permission Denied.');

		if (is_array(Input::get('symbol')))
			return Response::json($this->userService->addFavorites(Input::get('symbol')));

		return Response::json($this->userService->addFavorite(Input::get('symbol')));
	}

	public function postDeleteFavorite($user)
	{
		if ($user->id != Auth::user()->id)
			throw Exception\PermissionException('Permission Denied.');

		if (is_array(Input::get('symbol')))
			return Response::json($this->userService->deleteFavorites(Input::get('symbol')));

		return Response::json($this->userService->deleteFavorite(Input::get('symbol')));
	}

}