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
use FaaPz\PDO\Database;
use \App\User;
use FormManager\Factory as Form;
use Zend\Diactoros\Response\RedirectResponse;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    User::class => create(User::class)->constructor(get('Foo'), get('Response'), get('Database')),
    'Foo' => 'Konstantin',
    'Response' => function() {
        return new Response();
    },
    'Database' => function() {
        $dsn = "mysql:host=172.18.0.3;dbname=entry;charset=utf8";
        $usr = "root";
        $pwd = "example";

        $pdo = new \FaaPz\PDO\Database($dsn, $usr, $pwd);

        return $pdo;
    }
]);

$container = $containerBuilder->build();

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', function() {
        $link = "<a href='/entry'>Оставить заявку</a>";
        echo $link;
    });
    
    $r->get('/entry', ["App\User", "entry" ]);
    $r->post('/send-entry', ["App\User", 'sendEntry']);

    $r->get('/thanks', function($request) {
        $name = $request->getQueryParams()['name'];
        echo "Спасибо {$name}";
    });

});

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

echo $response->getBody();