<?php

class BaseController extends Controller {

	protected $layout = '_layouts.default';

	public function __construct()
	{
		//parent::__construct();
	}

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function missingMethod($parameters = array())
	{
		throw new Exception\NotFoundException('Resource Not Found.', 404);
	}

}