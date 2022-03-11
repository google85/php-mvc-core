<?php

namespace google85\phpmvc;

/**
 * Class Response
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 *
 */
class Response {

    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function redirect($url) {
        header('Location: ' . $url);
    }

}