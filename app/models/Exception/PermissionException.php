<?php  namespace Exception;

use Exception;
 
class PermissionException extends Exception {
 
  public function __construct($message = null, $code = 403)
  {
    parent::__construct($message ?: 'Action not allowed', $code);
  }
 
}