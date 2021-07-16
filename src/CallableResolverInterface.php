<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

use Psr\Http\Message\ServerRequestInterface;

interface CallableResolverInterface
{
    /**
     * Undocumented function
     *
     * @param ServerRequestInterface $request
     * @return callable|bool
     */
    public function resolve(ServerRequestInterface $request);
}
