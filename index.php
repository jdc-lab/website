<?php

require_once 'vendor/autoload.php';

$router = new Klein\Klein();

$router->respond('/', function () {
    render('index');
});

$router->respond('/[:page]', function ($r) {
    render($r->page);
});

$router->dispatch();

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

    echo $twig->render($page . $ext, $data);
}
