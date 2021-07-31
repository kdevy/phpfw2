<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

use Framework\App;
use Framework\Log;
use DI\ContainerBuilder;
use Framework\CallableResolver;
use Framework\CallableResolverInterface;

require_once "../vendor/autoload.php";

// get container from setting file.
$builder = new ContainerBuilder();
$builder->addDefinitions('../app/config.php');
$container = $builder->build();

// set logging configures.
if ($container->has("logging")) {
    Log::configure($container->get("logging"));
}

// get middlewares, default empty.
$middlewares = [];
if ($container->has("middlewares")) {
    $middlewares = $container->get("middlewares");
}

// get callable_resolver, default built-in class.
$callable_resolver = new CallableResolver();
if ($container->has(CallableResolverInterface::class)) {
    $callable_resolver = $container->get(CallableResolverInterface::class);
}

// start application.
$app = new App($callable_resolver, $middlewares);
$app->run();
