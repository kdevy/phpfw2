<?php

use Framework\Action\Action;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

class IndexAction extends Action
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response_factory->setContexts($this->getContexts($request))->createResponse();
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    public function getContexts(ServerRequestInterface $request): array
    {
        $contexts = parent::getContexts($request);
        $contexts["TEST"] = date("Y-m-d H:i:s");

        return $contexts;
    }
}
