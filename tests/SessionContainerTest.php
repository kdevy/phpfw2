<?php

use PHPUnit\Framework\TestCase;
use Framework\SessionContainer;

class SessionContainerTest extends TestCase
{
    public function test()
    {
        $session = new SessionContainer([
            "hoge" => "ほげ"
        ]);

        $this->assertSame("ほげ", $session["hoge"]);
        $this->assertSame("ほげ", $session->hoge);
        $this->assertSame(1, count($session));

        $session->set("fuga", "ふが");
        $this->assertSame("ふが", $session->get("fuga"));
        $this->assertTrue($session->has("fuga"));
        $this->assertFalse($session->has("piyo"));
        $session->unset("fuga");
        $this->assertFalse($session->has("fuga"));
    }
}
