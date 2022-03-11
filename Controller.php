<?php

namespace google85\phpmvc;
use google85\phpmvc\middlewares\BaseMiddleware;

/**
 * Class Controller
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 * 
 * - base controller class, used for purpose of extension
 *
 */
class Controller {

    public $layout = 'main';                //public string $layout = 'main';       //PHP >=7.4 - type property
    public $action = '';                    //public string $action = '';

    /**
     * @var \google85\phpmvc\middlewares\BaseMiddleware[]
     */
    protected array $middlewares = [];

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function render($view, $params = []) {
        return Application::$app->router->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware) {
        $this->middlewares[] = $middleware;

    }

    public function getMiddlewares(): array {
        return $this->middlewares;
    }

}