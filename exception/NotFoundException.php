<?php

namespace google85\phpmvc\exception;

/**
 * Class NotFoundException
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc\exception
 *
 */
class NotFoundException extends \Exception {

    protected $message = 'Page not found';
    protected $code = 404;

}