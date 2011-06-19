<?php

namespace v6tools;

/**
 * Represents an IPv6 EUI64
 *
 * @author David Soria Parra <dsp at php dot net>
 * @package v6tools
 * @version 1.0
 */
class EUI64 {
    private $addr;

    /**
     * Create a new instance for the given IPv6 ddress
     *
     * @param string $addr The IPv6 address
     */
    public function __construct($addr) {
        if (!IPv6Address::validate($addr)) {
            throw new \InvalidArgumentException("Not a valid IPv6 address.");
        }

        $this->addr = $addr;
    }

    /**
     * Checks if the IP address might have a valid EUI64.
     *
     * @return boolean
     */
    public function isValid() {
        return preg_match('@ff:fe[0-9a-f]{2}:[0-9a-f]{4}$@', $this->addr) > 0;
    }

    /**
     * Calculate the mac address from the EUI64 part of the address.
     *
     * @return string
     */
    public function getMacAddress() {
        if (!$this->isValid()) {
            return false;
        }

        $a = array_slice(explode(':', str_replace('ff:fe', '', $this->addr)), 4);
        $s = array_map(
                function ($e) {
                    return hexdec($e);
                }, $a);
        $s[0] ^= 0x200;

        return implode(':', array_map(
            function ($e) {
                return sprintf("%02x:%02x", $e >> 8, $e & 0xff);
            }, $s));
    }
}
