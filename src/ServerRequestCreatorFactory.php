<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestCreatorFactory
{
    /**
     * @return ServerRequestInterface
     */
    public static function create(): ServerRequestInterface
    {
        $psr17_factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17_factory,
            $psr17_factory,
            $psr17_factory,
            $psr17_factory
        );
        return $creator->fromGlobals();
    }
}
