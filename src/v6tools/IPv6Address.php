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

    /**
     * Create a new instance for the given IPv6 address.
     *
     * @param string $addr The IPv6 address
     */
    public function __construct($addr) {
        if (!filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            throw new \InvalidArgumentException('Not a valid IPv6 address');
        }

        $this->addr = $addr;
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
}

