<?php

namespace google85\phpmvc;

/**
 * Class View
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 * 
 * - for title purposes
 * - moved all methods here
 *
 */
class View {

    public $title = '';

    public function renderView($view, $params = []) {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent($viewContent) {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent() {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;    
        }
        
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params) {
        // attaching variables to params
        foreach ($params as $key => $value) {
            $$key = $value ;       // note the 2 $$ for name variable !
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}