<?php

use Framework\CallableResolver;
use Framework\Route;
use Framework\ServerRequestCreatorFactory;
use Framework\TemplateResponseFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class CallableResolverTest extends TestCase
{
    public function test()
    {
        $request = ServerRequestCreatorFactory::create();
        $request = $request->withUri(new Uri("/hoge/fuga"));
        $request = $request->withAttribute("route", new Route($request));

        $this->assertSame("Hoge", CallableResolver::camelize("hoge"));
        $this->assertSame("HogeFugaPiyo", CallableResolver::camelize("hoge-fuga-piyo"));
        $this->assertSame("HogeFugaPiyo", CallableResolver::camelize("hoge_fuga_piyo"));
        $this->assertSame("HogeFugaPiyo", CallableResolver::camelize("HogeFugaPiyo"));

        $action_info = CallableResolver::getActionInfo($request);
        $this->assertSame("FugaAction", $action_info[0]);
        $this->assertSame("FugaAction.php", $action_info[1]);
        $this->assertSame("/home/kdevy-phpfw2/module/hoge/actions/FugaAction.php", $action_info[2]);
    }
}
