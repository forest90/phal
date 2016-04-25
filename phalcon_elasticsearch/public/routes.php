<?php
use Phalcon\Mvc\Router;

$router = new Router();

// Define a route
$router->add(
    "/companies/showAll",
    array(
        "controller" => "companies",
        "action"     => "index"
    )
);

// Another route
$router->add(
    "/companies/show",
    array(
        "controller" => "companies",
        "action"     => "show"
    )
);

$router->handle();