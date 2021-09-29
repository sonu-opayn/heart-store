<?php
/**
 *	@author Pankaj Singh
 *	@version 0.1.0
 */

namespace App\HeartStore\Exceptions;

use Exception;

class ValidationException extends Exception 
{
    private $validator;                           // Validator

    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    public function __construct($validator)
    {
        parent::__construct($this->message, $this->code);
        $this->validator = $validator;
    }   

    public function setValidator() 
    {
        return $this->validator;
    }

    public function getValidator() 
    {
        return $this->validator;
    }

    public function getErrors() 
    {
        return $this->validator->errors()->all();
    }
}