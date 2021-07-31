<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

use Psr\Container\ContainerInterface;

return [
    "is_development" => true,
    "middlewares" => function (ContainerInterface $container) {
        return [
            Framework\Middleware\PhpSettingsMiddleware::class,
        ];
    },
    "session_save_path" => function (ContainerInterface $container) {
        return basename(__DIR__) . DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . "session";
    },
    "logging" => function (ContainerInterface $container) {
        $logging = [
            "level" => Framework\Log::INFO,
        ];

        if ($container->get("is_development") === true) {
            $logging["level"] = Framework\Log::DEBUG;
        }
        return $logging;
    }
];
