<?php

namespace google85\phpmvc;
use google85\phpmvc\db\Database;

/**
 * Class Application
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 * 
 * WARNING: type property - requires PHP >= 7.4
 *
 */
class Application {

    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    protected $eventListeners = [];         //protected array $eventListeners = [];

    public static $app;                     //public static Application $app;       //PHP >=7.4 - type property

    public static $ROOT_DIR;                //public static string $ROOT_DIR;       //PHP >=7.4 - type property

    public $layout = 'main';                //public string $layout = 'main';
    public $userClass;                      //public string $userClass;
    public $router;                         //public Router $router;                //PHP >=7.4 - type property
    public $request;                        //public Request $request;              //PHP >=7.4 - type property
    public $response;                       //public Response $response;            //PHP >=7.4 - type property
    public $session;                        //public Session $session;
    public $db;                             //public Database $db;
    // for guests can be null, so ? in front in PHP >=7.4
    public $user;                           //public ?UserModel $user;
    public $view;                           //public View $view;

    public $controller = null;              //public ?Controller $controller;        //PHP >=7.4 - type property

    public function __construct($rootPath, $config) {

        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);  //findOne e al lui User:: si in core ar trebui folosite doar chestii de baza, deci a fost instantiat prin index ca userClass aceasta clasa User si se pot folosi metodele ei
        } else {
            $this->user = null;
        }
    }

    public static function isGuest() {
        return !self::$app->user;
    }

    /**
     * @return \google85\phpmvc\Controller
     */
    public function getController(): \google85\phpmvc\Controller
    {
        return $this->controller;
    }

    /**
     * @param \google85\phpmvc\Controller $controller
     */
    public function setController($controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @param UserModel $user
     */
    public function login($user) {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove('user');
    }

    public function run() {

        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->router->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function triggerEvent($eventName) {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public function on($eventName, $callback) {
        $this->eventListeners[$eventName][] = $callback;
    }
}