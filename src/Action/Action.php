<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework\Action;

use Framework\Action\ActionInterface;
use Framework\TemplateResponseFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Action implements ActionInterface
{
    /**
     * @var TemplateResponseFactory
     */
    public TemplateResponseFactory $template_response_factory;

    /**
     * @var ServerRequestInterface
     */
    public ServerRequestInterface $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->template_response_factory = new TemplateResponseFactory($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    public function initialize(ServerRequestInterface $request): void
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    abstract public function dispatch(ServerRequestInterface $request): ResponseInterface;

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    public function getContexts(ServerRequestInterface $request): array
    {
        $route = $request->getAttribute("route");
        return [
            "ROUTE_MODULE" => $route->getModuleName(),
            "ROUTE_ACTION" => $route->getActionName(),
            "ROUTE_PATH" => $route->getPathName(),
        ];
    }
}
