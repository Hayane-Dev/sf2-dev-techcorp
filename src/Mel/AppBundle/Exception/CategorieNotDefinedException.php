<?php

namespace Mel\AppBundle\Exception;

use Exception;

class CategorieNotDefinedException extends Exception {

  protected $message;
  protected $code;
  protected $prevException;

  public function __construct($message='Categories were not defined', $code=404, $prevException=null)
  {
    $this->message = $message;
    $this->code = $code;
    $this->prevException = $prevException;
  }

}