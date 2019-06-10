<?php

require_once 'vendor/autoload.php';

$router = new Klein\Klein();

$router->respond('/[:page]', function ($r) {
    render($r->page);
});

/**
 * @param string $page
 * @param array $data
 * @throws \Twig\Error\LoaderError
 * @throws \Twig\Error\RuntimeError
 * @throws \Twig\Error\SyntaxError
 */
function render(string $page, array $data = [])
{
    $loader = new Twig\Loader\FilesystemLoader('tpl');
    $twig = new Twig\Environment($loader);

    $ext = '.twig';

    $twig->render($page . $ext, $data);
}
