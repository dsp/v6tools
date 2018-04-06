<?php
use PHPUnit\Framework\TestCase;

class EUI64 extends TestCase {
    public function testMightBeEUI64() {
        $c = new v6tools\EUI64('2a01:198:603:0:224:d7ff:fe18:618c');
        $this->assertTrue($c->isValid());

        $c = new v6tools\EUI64('2a01:198:603:0:5416:473:fac9:f59c');
        $this->assertFalse($c->isValid());
    }

    public function testGetMacAddress() {
        $c = new v6tools\EUI64('2a01:198:603:0:224:d7ff:fe18:618c');
        $this->assertEquals('00:24:d7:18:61:8c', $c->getMacAddress());
    }
}
