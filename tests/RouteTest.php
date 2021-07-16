<?php

use Framework\Route;
use Framework\ServerRequestCreatorFactory;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function test()
    {
        // from string
        $route = new Route("/");
        $this->assertSame("/index/index", strval($route));

        $route = new Route("/hoge");
        $this->assertSame("/index/hoge", strval($route));

        $route = new Route("/hoge/fuga");
        $this->assertSame("/hoge/fuga", strval($route));

        $route = new Route("/hoge/fuga/piyo");
        $this->assertSame("/hoge/fuga", strval($route));

        // from array
        $route = new Route(["hoge", "fuga"]);
        $this->assertSame("/hoge/fuga", strval($route));

        // from request
        $request = ServerRequestCreatorFactory::create();
        $request = $request->withUri(new Uri("/hoge/fuga"));
        $route = new Route($request);
        $this->assertSame("/hoge/fuga", strval($route));

        // get path array
        $route = new Route(["hoge", "fuga"]);
        $this->assertSame("hoge", $route->getPath()[0]);
        $this->assertSame("fuga", $route->getPath()[1]);

        // get module name
        $route = new Route(["hoge", "fuga"]);
        $this->assertSame("hoge", $route->getModuleName());

        // get action name
        $route = new Route(["hoge", "fuga"]);
        $this->assertSame("fuga", $route->getActionName());

        // get action name
        $route = new Route(["hoge", "fuga.html"]);
        $this->assertSame("fuga.html", $route->getActionName());
    }
}
