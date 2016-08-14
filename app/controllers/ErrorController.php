<?php

class ErrorController extends BaseController {
	
	protected $layout = '_layouts.error';

	public function getNotFound()
	{
		$this->layout->content = View::make('errors.404');
	}

	public function getUnsupportedBrowser()
	{
		$this->layout->content = View::make('errors.unsupported_browser');
	}

}