<?php

namespace Tonic;

class MethodNotAllowedException extends Exception
{
    protected $code = 405;
    protected $message = 'The HTTP method specified in the Request-Line is not allowed for the resources identified by the Request-URI';
}
