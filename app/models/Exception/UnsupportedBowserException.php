<?php namespace Exception;

use Exception;
 
class UnsupportedBrowserException extends Exception {
 
  public function __construct($message = null, $code = 403)
  {
    parent::__construct($message ?: 'Unsupported Browser Detected', $code);
  }
 
}