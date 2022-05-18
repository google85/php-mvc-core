<?php

namespace google85\phpmvc;
use google85\phpmvc\exception\NotFoundException;
//use google85\phpmvc\Controller;

/**
 * Class Router
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 *
 */
class Router {

    public $request;                        //public Request $request;              //PHP >=7.4 - type property
    public $response;                       //public Response $response;            //PHP >=7.4 - type property
    protected $routes = [];                 //protected array $routes = [];         //PHP >=7.4

    public function __construct($request, $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve() {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            $this->response->setStatusCode(404);
            throw new NotFoundException();
            //return $this->renderView("_404");   //it also worked this way
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        
        if (is_array($callback)) {
            /**
             ** @var \google85\phpmvc\Controller $controller
             * - for PHPStorm to see the type in order to use the autocomplete functionality :)
             */
            $controller = new $callback[0]();      //instantiate the class
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            //$callback[0] = new $callback[0]();      //instantiate the class

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($view, $params = []) {
        return Application::$app->view->renderView($view, $params);
    }

    public function renderContent($viewContent) {
        return Application::$app->view->renderContent($viewContent);
    }

    protected function layoutContent() {
        return Application::$app->view->layoutContent();
    }

    protected function renderOnlyView($view, $params) {
        return Application::$app->view->renderOnlyView($view, $params);
    }
    
}