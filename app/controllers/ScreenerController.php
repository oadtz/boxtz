<?php

class ScreenerController extends BaseController {
	
	public function create()
	{
		$this->layout = View::make('_layouts.blank');
		$this->layout->content = View::make('screener._form');
	}

}