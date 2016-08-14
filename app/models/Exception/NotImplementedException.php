<?php  namespace Exception;

use Exception;
 
class NotImplementedException extends Exception {
 
  public function __construct($message = null, $code = 403)
  {
    parent::__construct($message ?: 'Method not implemented', $code);
  }
 
}