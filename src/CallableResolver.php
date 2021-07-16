<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

use Framework\Action\ActionInterface;
use Framework\Exception\CreateActionError;
use Psr\Http\Message\ServerRequestInterface;

class CallableResolver implements CallableResolverInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return callable|bool
     */
    public function resolve(ServerRequestInterface $request)
    {
        $action = self::createAction($request);

        return function () use ($request, $action) {
            $action->initialize($request);
            $response = $action->dispatch($request);

            return $response;
        };
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    static public function getActionInfo(ServerRequestInterface $request): array
    {
        $result = [];

        $route = $request->getAttribute("route");
        $result[0] = self::camelize($route->getActionName()) . "Action";
        $result[1] = $result[0] . ".php";
        $result[2] = dirname(__DIR__) . "/module/" . $route->getModuleName() . "/actions/" . $result[1];

        return $result;
    }

    /**
     * @param string $str
     * @return string
     */
    static public function camelize(string $str): string
    {
        return str_replace(['-', '_'], '', ucwords($str, ' -_'));
    }

    /**
     * @param ServerRequestInterface $request
     * @return ActionInterface|bool
     */
    static public function createAction(ServerRequestInterface $request)
    {
        list($class_name, $file_name, $filepath) = self::getActionInfo($request);

        if (!file_exists($filepath)) {
            throw new CreateActionError("Action file does not exist ({$filepath}).");
        }

        if (!is_readable($filepath)) {
            throw new CreateActionError("Action file can not be read ({$filepath}).");
        }

        require_once($filepath);

        if (!class_exists($class_name)) {
            throw new CreateActionError("Action class does not exist ({$filepath}).");
        }

        $action = new $class_name($request);

        return $action;
    }
}
