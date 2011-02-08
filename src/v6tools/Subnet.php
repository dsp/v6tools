<?php

namespace v6tools;

/**
 * Represents an IPv6 subnet
 *
 * @author David Soria Parra <dsp at php dot net>
 * @package v6tools
 * @version 1.0
 */
class Subnet {
    private $canonial;
    private $preflen;
    private $addr;

    /**
     * Create a new instance for the given IPv6 subnet.
     *
     * A subnet is given as ADDR/PREFIX. For example 200a:a32a:/64
     *
     * @param string $addr The IPv6 subnet
     */
    public function __construct($subnet) {
        if (false === strpos($subnet, '/')) {
            throw new \InvalidArgumentException("Not a valid IPv6 subnet.");
        }

        list($addr, $preflen) = explode('/', $subnet);
        if (!is_numeric($preflen)) {
            throw new \InvalidArgumentException("Not a valid IPv6 preflen.");
        }

        if (!filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            throw new \InvalidArgumentException("Not a valid IPv6 subnet.");
        }

        $this->addr = $addr;
        $this->preflen = (int) $preflen;
        $this->canonial = $subnet;
    }

    /**
     * Checks if the give IPv6 Address is part of the subnet.
     *
     * @param string The IPv6 address to check
     */
    public function isInSubnet($ipv6addr) {
        if (!filter_var($ipv6addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            throw new \InvalidArgumentException("Not a valid IPv6 address.");
        }

        $bytes_addr = unpack("n*", inet_pton($this->addr));
        $bytes_test = unpack("n*", inet_pton($ipv6addr));

        for ($i = 1; $i <= ceil($this->preflen / 16); $i++) {
            $left = $this->preflen - 16 * ($i-1);
            $left = ($left <= 16) ?: 16;
            $mask = ~(0xffff >> $left) & 0xffff;
            if (($bytes_addr[$i] & $mask) != ($bytes_test[$i] & $mask)) {
                return false;
            }
        }
        return true;
    }
}
