<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use function DI\create;
use \App\App;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    App::class => create(App::class)
]);

$container = $containerBuilder->build();
$app = $container->get(App::class);

$app->index();