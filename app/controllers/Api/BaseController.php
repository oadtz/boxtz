<?php namespace Api;

use Controller;
USE Input;

class BaseController extends Controller {
	
	public function __construct()
	{
		
	}

	// Utility
	protected function getQuery($data = array())
	{
		return array_merge((array)Input::get('q'), $data);
	}

	protected function getSearch($data = array())
	{
		return array_merge((array)Input::get('s'), $data);
	}

	protected function getOrder($data = array())
	{
		return array_merge((array)Input::get('o'), $data);
	}

	protected function getPaging($data = array())
	{
		return array_merge((array)Input::get('p'), $data);
	}


}