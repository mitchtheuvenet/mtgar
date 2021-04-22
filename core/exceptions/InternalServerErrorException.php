<?php

namespace app\core\exceptions;

class InternalServerErrorException extends \Exception {

    protected $code = 500;
    protected $message = 'An unknown error has occurred. Please <a href="/contact" class="text-decoration-none">contact the site administrators</a> if this error persists.';

}