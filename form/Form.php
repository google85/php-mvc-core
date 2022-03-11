<?php

namespace google85\phpmvc\form;

/**
 * Class Form
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc\form
 *
 */
class Form {
    
    public static function begin($action, $method) {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function end() {
        echo '</form>';
    }

    public function field($model, $attribute) {
        return new InputField($model, $attribute);
    }

}