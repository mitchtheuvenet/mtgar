<?php

namespace app\core;

class View {

    private const LAYOUT_DEFAULT = 'main';

    private string $title = '';
    private string $script = '';

    public function renderView($view, $params) {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent($viewContent) {
        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent() {
        if (!empty(Application::$app->controller)) {
            $layout = Application::$app->controller->getLayout();
        } else {
            $layout = self::LAYOUT_DEFAULT;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/{$layout}.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params) {
        foreach ($params as $key => $val) {
            $$key = $val;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/{$view}.php";
        return ob_get_clean();
    }

    protected function getFlashMessages() {
        return Application::$app->session->get(Session::FLASH_KEY);
    }

    protected function path() {
        return Application::$app->request->path();
    }

}