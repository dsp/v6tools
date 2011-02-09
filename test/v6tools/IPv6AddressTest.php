<?php
class IPv6AddressTest extends PHPUnit_Framework_TestCase {
    public function testExpand() {
        $addr = new v6tools\IPv6Address('2001::1');
        $this->assertEquals('2001:0000:0000:0000:0000:0000:0000:0001',
            $addr->expand());
    }

    public function testCompact() {
        $addr = new v6tools\IPv6Address('2001:0000:0000:0000:0000:0000:0000:0001');
        $this->assertEquals('2001:0000:0000:0000:0000:0000:0000:0001',
            $addr->__toString());
        $this->assertEquals('2001::1', $addr->compact());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAddress() {
        $addr = new v6tools\IPv6Address('foobar');
    }
}
