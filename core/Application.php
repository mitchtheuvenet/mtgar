<?php

namespace app\core;

use app\models\DbUser;

class Application {

    public static string $ROOT_DIR;
    public static Application $app;

    public Database $db;

    public Controller $controller;

    public Request $request;
    public Response $response;
    public Session $session;
    public Router $router;

    public ?DbUser $user;

    public function __construct($rootPath, array $config) {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);

        $userPkVal = $this->session->get('user');

        if (!empty($userPkVal)) {
            $userPk = DbUser::primaryKey();

            $this->user = DbUser::findObject([$userPk => $userPkVal]);
        } else {
            $this->user = null;
        }
    }

    public function run() {
        echo $this->router->resolve();
    }

    public function logIn(DbModel $user) {
        $this->user = $user;

        $primaryKey = $this->user->primaryKey();
        $primaryVal = $this->user->{$primaryKey};

        $this->session->set('user', $primaryVal);

        return true;
    }

    public function logOut() {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest() {
        return is_null(self::$app->user);
    }

}
