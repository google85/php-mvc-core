<?php

namespace google85\phpmvc\exception;

/**
 * Class ForbiddenException
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc\exception
 *
 */
class ForbiddenException extends \Exception {

    protected $message = 'You don\'t have permission to access this page';
    protected $code = 403;

}