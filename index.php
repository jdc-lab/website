<?php

require_once 'vendor/autoload.php';

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Exception\NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

$app->get('/base', function (Request $request, Response $response, array $args) {
    throw new NotFoundException($request, $response);
});

$app->get('/{page}', function (Request $request, Response $response, array $args) {
    $page = $args['page'];
    $rendered = render($page);
    if ($rendered !== null) {
        $response->getBody()->write($rendered);
    } else {
        throw new NotFoundException($request, $response);
    }
    return $response;
});

$app->run();

/**
 * @param string $page
 * @param array $data
 * @return string|null
 * @throws LoaderError
 * @throws RuntimeError
 * @throws SyntaxError
 */
function render(string $page, array $data = []): ?string
{
    $loader = new Twig\Loader\FilesystemLoader('tpl');
    $filePath = $page . '.twig';
    if (!$loader->exists($filePath)) {
        return null;
    }

    $twig = new Twig\Environment($loader);

    return $twig->render($filePath, $data);
}