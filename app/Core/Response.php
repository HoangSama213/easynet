<?php

namespace App\Core;

class Response
{
    public static function view(string $view, array $data = [], string $layout = 'layouts.app'): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $view) . '.php';
        $layoutFile = $layout !== '' ? dirname(__DIR__) . '/Views/' . str_replace('.', '/', $layout) . '.php' : '';

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layoutFile === '') {
            echo $content;
            return;
        }

        require $layoutFile;
    }
}
