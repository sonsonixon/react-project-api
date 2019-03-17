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

// Login
$app->post("/login", 	"App\Controllers\UsersController:login");

// Users
$app->group('/users', function () use ($app) {
	// $app->get("/join", 			  "App\Controllers\UsersController:join");
	$app->get("/get/{uuid}",    		  "App\Controllers\UsersController:getCurrentUser");
	$app->get("/{uuid}",				  "App\Controllers\UsersController:fetchUser");

	$app->post("/fetch",				  "App\Controllers\UsersController:fetchUsers");
	$app->post("/create", 		 		  "App\Controllers\UsersController:createUser");	
	$app->post("/update/{uuid}", 		  "App\Controllers\UsersController:updateUser");
	$app->post("/update/password/{uuid}", "App\Controllers\UsersController:changePassword");
	/*
	$app->post("", 			"App\Controllers\TableController:add");
	$app->post("/{id}", 	"App\Controllers\TableController:update");
	$app->delete("/{id}", 	"App\Controllers\TableController:delete");
	*/
});

// departments
$app->group('/departments', function () use ($app) {
	// get
	$app->post("/getall", "App\Controllers\DepartmentsController:getall");
	$app->post("/add",    "App\Controllers\DepartmentsController:add");
	$app->get("/getoption", "App\Controllers\DepartmentsController:getoption");

	/*
	// post
	$app->post("", 			"App\Controllers\TableController:add");
	$app->post("/{id}", 	"App\Controllers\TableController:update");
	$app->delete("/{id}", 	"App\Controllers\TableController:delete");
	*/
});

// Todos
$app->group('/todos', function () use ($app) {
	$app->post("/fetch", 	"App\Controllers\TodosController:fetch");
	$app->post("/add", 		"App\Controllers\TodosController:add");
	/*
	$app->post("", 			"App\Controllers\TableController:add");
	$app->post("/{id}", 	"App\Controllers\TableController:update");
	$app->delete("/{id}", 	"App\Controllers\TableController:delete");
	*/
});