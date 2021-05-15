<?php

namespace Gac\Controller;

abstract class AbstractController
{
    protected $viewPath = "";

    public function __construct()
    {
    }

    protected function viewRender(string $viewPath, array $params = [])
    {
        if (!file_exists($viewPath)) {
            throw new \Exception('Filepath "' . $viewPath . '" invalid', 1);
        }

        ob_start();
        extract($params);
        include $viewPath;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
