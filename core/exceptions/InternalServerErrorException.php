<?php

namespace app\core\exceptions;

class InternalServerErrorException extends \Exception {

    protected $code = 500;
    protected $message = 'An unknown error has occurred. Please contact the site administrators if this error persists.';

}