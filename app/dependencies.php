<?php
// DIC configuration

$container = $app->getContainer();

use Illuminate\Database\Capsule\Manager as Capsule;  
$capsule = new Capsule; 
$capsule->addConnection($container->get('settings')['database']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// var_dump($capsule->table('table')->find(1));exit;


$container['App\Controllers\TableController'] = function ($c) use ($app){
    return new App\Controllers\TableController();
};


// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
