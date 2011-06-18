<?php
class SubnetTest extends PHPUnit_Framework_TestCase {
    public function testIsInSubnet() {
        $c = new v6tools\Subnet('2a01:198:603:0::/65');
        $this->assertTrue($c->isInSubnet('2a01:198:603:0:396e:4789:8e99:890f'));
        $this->assertFalse($c->isInSubnet('2a00:198:603:0:396e:4789:8e99:890f'));

        $c = new v6tools\Subnet('2001::/16');
        $this->assertFalse($c->isInSubnet('2000::1'));
    }
}
