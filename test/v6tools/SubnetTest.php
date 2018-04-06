<?php
class SubnetTest extends PHPUnit_Framework_TestCase {
    public function testIsInSubnet() {
        $c = new v6tools\Subnet('2a01:198:603:0::/65');
        $this->assertTrue($c->isInSubnet('2a01:198:603:0:396e:4789:8e99:890f'));
        $this->assertFalse($c->isInSubnet('2a00:198:603:0:396e:4789:8e99:890f'));

        $c = new v6tools\Subnet('2001::/16');
        $this->assertFalse($c->isInSubnet('2000::1'));
    }

    public function testEUI64() {
        $c = new v6tools\Subnet('2a01:198:603:0::/64');
        $this->assertEquals('2a01:0198:0603:0000:0225:90ff:fea8:04c3', $c->getEUI64Address('00:25:90:a8:04:c3'));
        $this->assertEquals('2a01:0198:0603:0000:5265:f3ff:fef0:b6f2', $c->getEUI64Address('50-65-F3-F0-B6-F2'));
    }

    public function testEUI64InvalidMask() {
        $this->setExpectedException(\InvalidArgumentException::class);
        $c = new v6tools\Subnet('2a01:198:603:0::/48');
        $c->getEUI64Address('00:25:90:a8:04:c3');
    }

    public function testEUI64InvalidMac() {
        $this->setExpectedException(\InvalidArgumentException::class);
        $c = new v6tools\Subnet('2a01:198:603:0::/64');
        $c->getEUI64Address('00:25:90:a8::c3');
    }
}
