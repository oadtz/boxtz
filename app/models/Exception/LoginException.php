<?php namespace Exception;

use Exception;
 
class LoginException extends Exception {
 
  public function __construct($message = null, $code = 401)
  {
    parent::__construct($message ?: 'Log In Failed', $code);
  }
 
}