<?php 

class HaruException extends Exception
{
    public function __construct($message, $code, $previous) {}
    public function getMessage() {}
    public function getCode() {}
    public function getFile() {}
    public function getLine() {}
    public function getTrace() {}
    public function getPrevious() {}
    public function getTraceAsString() {}
    public function __toString() {}
}
