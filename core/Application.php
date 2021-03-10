<?php

namespace app\core;

class Application {

    public static string $ROOT_DIR;
    public static Application $app;

    public Database $db;

    public Controller $controller;

    public Request $request;
    public Response $response;
    public Router $router;

    public function __construct($rootPath, array $config) {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);
    }

    public function run() {
        echo $this->router->resolve();
    }

}
