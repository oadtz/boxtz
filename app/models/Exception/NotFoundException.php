<?php namespace Exception;

use Exception;
 
class NotFoundException extends Exception {
 
  public function __construct($message = null, $code = 404)
  {
    parent::__construct($message ?: 'Resource Not Found', $code);
  }
 
}