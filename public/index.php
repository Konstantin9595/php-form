<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use function DI\create;
use function DI\get;
use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
//use Zend\Diactoros\Response\SapiEmitter;
use function FastRoute\simpleDispatcher;
use \App\App;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    App::class => create(App::class)->constructor(get('Foo'), get('Response')),
    'Foo' => 'Konstantin',
    'Response' => function() {
        return new Response();
    }
]);

$container = $containerBuilder->build();

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', App::class);
});

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

echo $response->getBody();