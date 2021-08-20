<?php

use Framework\Route;
use Framework\ServerRequestCreatorFactory;
use Framework\TemplateResponseFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class TemplateResponseFactoryTest extends TestCase
{
    public function test()
    {
        $request = ServerRequestCreatorFactory::create();
        $request = $request->withUri(new Uri("/hoge/fuga"));
        $request = $request->withAttribute("route", new Route($request));
        $factory = new TemplateResponseFactory($request, ["DEFAULT" => "hoge"]);
        $factory->setContexts(["NAME" => "kdevy"]);

        $this->assertSame("/home/kdevy-phpfw2/template/hoge/fuga.html", $factory->getTemplateFilePath());

        $this->assertSame("hoge fuga", TemplateResponseFactory::assignContexts("___HOGE___ ___FUGA___", ["HOGE" => "hoge", "FUGA" => "fuga"]));

        // $this->assertSame("<p>My name is kdevy, Default: hoge</p>", $factory->createContents());

        $response = $factory->createResponse();
    }
}
