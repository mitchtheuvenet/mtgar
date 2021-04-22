<?php

namespace app\core\exceptions;

class UnauthorizedException extends \Exception {

    protected $code = 401;
    protected $message = 'You must be <a href="/login" class="text-decoration-none">logged in</a> to view this page.';

}