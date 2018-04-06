<?php
use PHPUnit\Framework\TestCase;

class IPv6AddressTest extends TestCase {

    public function testValidatorValidAddress() {
        $this->assertTrue(v6tools\IPv6Address::validate('2a01:198:603:0:89d8:32f6:cd7e:9172'));
    }
    
    public function testValidatorInvalidAddress() {
        $this->assertFalse(v6tools\IPv6Address::validate('2a01:ggg:xxx:0:89d8:32f6:::cd7e:9172'));
    }
    

    public function testExpand() {
        $addr = new v6tools\IPv6Address('2001::1');
        $this->assertEquals('2001:0000:0000:0000:0000:0000:0000:0001',
            $addr->expand());
    }

    public function testCompact() {
        $addr = new v6tools\IPv6Address('2001:0000:0000:0000:0000:0000:0000:0001');
        $this->assertEquals('2001:0000:0000:0000:0000:0000:0000:0001',
            (string) $addr);
        $this->assertEquals('2001::1', $addr->compact());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAddress() {
        $addr = new v6tools\IPv6Address('foobar');
    }

    public function testIsGlobal() {
        $addr = new v6tools\IPv6Address('2a01:198:603:0:89d8:32f6:cd7e:9172');
        $this->assertTrue($addr->isGlobal());

        $addr = new v6tools\IPv6Address('fe80::224:d7ff:fe18:618c');
        $this->assertFalse($addr->isGlobal());

        $addr = new v6tools\IPv6Address('ff02::1:ff18:618c');
        $this->assertTrue($addr->isGlobal());

        $addr = new v6tools\IPv6Address('0::0:0:0:0');
        $this->assertFalse($addr->isGlobal());
    }

    public function testIsLinkLocal() {
        $addr = new v6tools\IPv6Address('2a01:198:603:0:89d8:32f6:cd7e:9172');
        $this->assertFalse($addr->isLinkLocal());

        $addr = new v6tools\IPv6Address('fe80::224:d7ff:fe18:618c');
        $this->assertTrue($addr->isLinkLocal());

        $addr = new v6tools\IPv6Address('ff02::1:ff18:618c');
        $this->assertFalse($addr->isLinkLocal());
    }

    public function testIsUnicast() {
        $addr = new v6tools\IPv6Address('2a01:198:603:0:89d8:32f6:cd7e:9172');
        $this->assertTrue($addr->isUnicast());

        $addr = new v6tools\IPv6Address('fe80::224:d7ff:fe18:618c');
        $this->assertTrue($addr->isUnicast());

        $addr = new v6tools\IPv6Address('ff02::1:ff18:618c');
        $this->assertFalse($addr->isUnicast());
    }

    public function testIsMulticast() {
        $addr = new v6tools\IPv6Address('2a01:198:603:0:89d8:32f6:cd7e:9172');
        $this->assertFalse($addr->isMulticast());

        $addr = new v6tools\IPv6Address('fe80::224:d7ff:fe18:618c');
        $this->assertFalse($addr->isMulticast());

        $addr = new v6tools\IPv6Address('ff02::1:ff18:618c');
        $this->assertTrue($addr->isMulticast());
    }
}
