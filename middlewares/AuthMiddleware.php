<?php

namespace google85\phpmvc\middlewares;
use google85\phpmvc\Application;
use google85\phpmvc\exception\ForbiddenException;

/**
 * Class AuthMiddleware
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc\middlewares
 *
 */
class AuthMiddleware extends BaseMiddleware {

    protected array $actions = [];

    /**
     * AuthMiddleware constructor.
     * 
     * @param array $actions
     */
    public function __construct($actions = []) {
        $this->actions = $actions;
    }


    public function execute() {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions) ) {
                throw new ForbiddenException();
            }
        }
    }


}