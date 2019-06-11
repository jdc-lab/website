<?php

require_once 'vendor/autoload.php';
require_once 'conf/Conf.php';

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

$app = new App();
$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));

        if ($request->getMethod() == 'GET') {
            return $response->withRedirect((string)$uri, 301);
        } else {
            return $next($request->withUri($uri), $response);
        }
    }

    return $next($request, $response);
});


$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write(render('index'));
    return $response;
});

$app->get('/{page}', function (Request $request, Response $response, array $args) {
    $page = $args['page'];
    $response->getBody()->write(render($page));
    return $response;
});

$app->run();

/**
 * @param string $page
 * @param array $data
 * @return string
 *@throws \Twig\Error\RuntimeError
 * @throws \Twig\Error\SyntaxError
 *
 * @throws \Twig\Error\LoaderError
 */
function render(string $page, array $data = [])
{
    $loader = new Twig\Loader\FilesystemLoader('tpl');
    $twig = new Twig\Environment($loader);

    $ext = '.twig';

    return $twig->render($page . $ext, $data);
}