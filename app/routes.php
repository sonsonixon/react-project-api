<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

// Sample Group Routes
$app->group('/table', function () use ($app) {
	$app->get("", 			"App\Controllers\TableController:get");
	$app->post("", 			"App\Controllers\TableController:add");
	$app->post("/{id}", 	"App\Controllers\TableController:update");
	$app->delete("/{id}", 	"App\Controllers\TableController:delete");
});

// User Routes
$app->group('/users', function () use ($app) {
	$app->post("/fetch", 	"App\Controllers\UsersController:fetchUsers");
	$app->post("/create", 	"App\Controllers\UsersController:createUser");
	$app->post("/login", 	"App\Controllers\UsersController:login");
	/*
	$app->post("", 			"App\Controllers\TableController:add");
	$app->post("/{id}", 	"App\Controllers\TableController:update");
	$app->delete("/{id}", 	"App\Controllers\TableController:delete");
	*/
});

// Todos Routes
$app->group('/todos', function () use ($app) {
	$app->post("/fetch", 	"App\Controllers\TodosController:fetch");
	$app->post("/add", 		"App\Controllers\TodosController:add");
	/*
	$app->post("", 			"App\Controllers\TableController:add");
	$app->post("/{id}", 	"App\Controllers\TableController:update");
	$app->delete("/{id}", 	"App\Controllers\TableController:delete");
	*/
});