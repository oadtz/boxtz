<?php  namespace Exception;

use Exception;
 
class ValidationException extends Exception {
 
  protected $validator;
 
  /**
   * We are adjusting this constructor to receive an instance
   * of the validator as opposed to a string to save us some typing
   * @param Validator $validator failed validator object
   */
  public function __construct($validator)
  {
    $this->validator = $validator;

    parent::__construct($this->validator->messages(), 400);
  }
 
  public function getValidator()
  {
    return $this->validator;
  }

  public function getMessages()
  {
    return $this->validator->messages();
  }

  public function getData()
  {
    return $this->validator->getData();
  }

  public function getRules()
  {
    return $this->validator->getRules();
  }
 
}