<?php

namespace Mel\AppBundle\Exception;

use Exception;

class CategorieNotFoundException extends Exception {

  protected $message;
  protected $code;
  protected $prevException;

  public function __construct($message='Categories were not found', $code=404, $prevException=null)
  {
    $this->message = $message;
    $this->code = $code;
    $this->prevException = $prevException;
  }

}