<?php

namespace v6tools;

/**
 * Represents an IPv6 address
 *
 * @author David Soria Parra <dsp at php dot net>
 * @package v6tools
 * @version 1.0
 */
class IPv6Address {
    private $addr;

    const UNSPECIFIED = 0x0;
    const MULTICAST = 0xff00;
    const GLOBAL_UNICAST = 0x2000;
    const LINK_LOCAL = 0xfe80;
    const UNIQUE_LOCAL = 0xfc00;
    const SITE_LOCAL = 0xfec0; /* decprecated */

    /**
     * Create a new instance for the given IPv6 address.
     *
     * @param string $addr The IPv6 address
     */
    public function __construct($addr) {
        if (!self::validate($addr)) {
            throw new \InvalidArgumentException('Not a valid IPv6 address');
        }

        $this->addr = $addr;
    }
    
    /**
    * Test if the given address is a valid IPv6 address
    *
    * @param string $addr The address to be validated as IPv6
    * @return boolean
    */
    public static function validate ($addr) {
        if (!filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return false;
        }
        
        return true;
    }

    /**
     * Returns a fully expanded representation of the IPv6 address.
     *
     * This will not compact the address and return exactly 8 x 2byte integers
     * in a hexdecimal representation separated by :.
     *
     * 2001::1 becomes 2001:0000:0000:0000:0000:0000:0000:0001
     *
     * @return string
     */
    public function expand() {
        $bytes = unpack('n*', inet_pton($this->addr));
        return implode(':', array_map(function ($b) {
            return sprintf("%04x", $b);
        }, $bytes));
    }

    /**
     * Returns a a compact representation of the IPv6 address.
     *
     * For further information about compact IPv6 addresses, please read
     * RFC 3513.
     *
     * 2001:0000:0000:0000:0000:0000:0000:0001 becomes 2001::1
     *
     * @return string
     */
    public function compact() {
        return inet_ntop(inet_pton($this->addr));
    }

    /**
     * Check if the given address is global routed according to current
     * IANA assignment.
     *
     * @return boolean
     */
    public function isGlobal() {
        $prefix = $this->getRoutingPrefix();
        return ($prefix & 0xe000) === self::GLOBAL_UNICAST
            || $this->isMulticast();
    }

    /**
     * Check if the given address is a uncode address according to current
     * IANA assignment.
     *
     * @return boolean
     */
    public function isUnicast() {
        return !$this->isMulticast();
    }

    /**
     * Check if the given address is link local and will not be routed
     * by a router.
     *
     * @return boolean
     */
    public function isLinkLocal() {
        return ($this->getRoutingPrefix() & 0xffc0) === self::LINK_LOCAL;
    }

    /**
     * Check if the given address is a multicast address.
     *
     * @return boolean
     */
    public function isMulticast() {
        return ($this->getRoutingPrefix() & 0xff00) === self::MULTICAST;
    }

    /**
     * Returns the given address.
     *
     * The address is not expanded or compcated. It is returned
     * exactly how it was provided.
     *
     * @return string
     */
    public function __toString() {
        return $this->addr;
    }

    protected function getRoutingPrefix() {
        $bytes = unpack('n*', inet_pton($this->addr));

        if (count($bytes) < 1) {
            return self::UNSPECIFIED;
        }

        return $bytes[1] & 0xffff;
    }
}

