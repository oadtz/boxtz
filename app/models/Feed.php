<?php

class Feed extends BaseModel {
	
	protected $collection = 'feed';
	public $fillable = ['event', 'data', 'user'];

}