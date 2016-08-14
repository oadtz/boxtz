<?php

class Index extends Company {

	protected $collection = 'index';
	protected $hidden = ['_id'];
	public $appends = ['id'];

}