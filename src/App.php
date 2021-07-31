<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

use Framework\Exception\CreateActionError;
use Framework\Exception\FrameworkException;
use Framework\Exception\HttpNotFound;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Framework\ServerRequestCreatorFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Uri;

class App implements RequestHandlerInterface
{
    /**
     * @var CallableResolverInterface
     */
    private CallableResolverInterface $callable_resolver;

    /**
     * @var string[]
     */
    private array $middlewares = [];

    /**
     * @param string[] $middlewares
     */
    public function __construct(CallableResolverInterface $callable_resolver, array $middlewares = [])
    {
        $this->callable_resolver = $callable_resolver;
        $this->middlewares = $middlewares;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = current($this->middlewares);
        next($this->middlewares);

        if (!$middleware) {
            try {
                $callable = $this->callable_resolver->resolve($request);
                return $callable();
            } catch (CreateActionError $e) {
                \Framework\Log::debug(null, $e);
                throw new HttpNotFound();
            }
        }

        $middleware = new $middleware();

        if (!$middleware instanceof MiddlewareInterface) {
            throw new FrameworkException("Invalid middleware was passed.");
        }

        return $middleware->process($request, $this);
    }

    /**
     * @param ServerRequestInterface|null $request
     * @param boolean $is_silent
     * @return ResponseInterface
     */
    public function run(?ServerRequestInterface $request = null, bool $is_silent = false): ResponseInterface
    {
        \Framework\Log::info(null, "-->Start running the application.");
        $stime = microtime(true);

        if (!$request) {
            $request = ServerRequestCreatorFactory::create();
        }
        $request = $request->withAttribute("route", new Route($request));

        try {
            $response = $this->handle($request);
        } catch (HttpNotFound $e) {
            $request = $request->withUri(new Uri("/notfound404"));
            $request = $request->withAttribute("route", new Route($request));

            try {
                $callable = $this->callable_resolver->resolve($request);
                $response = $callable();
            } catch (CreateActionError $e) {
                \Framework\Log::debug(null, $e);

                $error_html = "<h2>Not Found 404</h2>
                <p><a href=\"/\">TOP</a></p>";
                $psr17_factory = new Psr17Factory();
                $response = $psr17_factory->createResponse(404);
                $response = $response->withBody($psr17_factory->createStream($error_html));
            }
        }

        if (!$is_silent) {
            $emitter = new SapiEmitter();
            $emitter->emit($response);
        }

        \Framework\Log::info(null, sprintf(
            "<-- Quit the application, MU = %s Kb, MPU = %s Kb, LAP = %.5f ms.",
            floor(memory_get_usage(true) / (1000)),
            floor(memory_get_peak_usage(true) / (1000)),
            microtime(true) - $stime,
        ));

        return $response;
    }
}
