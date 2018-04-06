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
        if (!is_numeric($preflen) || $preflen > 255) {
            throw new \InvalidArgumentException("Not a valid IPv6 preflen.");
        }

        if (!IPv6Address::validate($addr)) {
            throw new \InvalidArgumentException("Not a valid IPv6 address.");
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
        if (!IPv6Address::validate($ipv6addr)) {
            throw new \InvalidArgumentException("Not a valid IPv6 address.");
        }

        $bytes_addr = unpack("n*", inet_pton($this->addr));
        $bytes_test = unpack("n*", inet_pton($ipv6addr));
        for ($i = 1; $i <= ceil($this->preflen / 16); $i++) {
            $left = $this->preflen - 16 * ($i-1);
            $left = ($left <= 16) ? $left : 16;
            $mask = ~(0xffff >> $left) & 0xffff;
            if (($bytes_addr[$i] & $mask) != ($bytes_test[$i] & $mask)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Generates EUI64 address
     *
     * @param string Mac address
     */
    public function getEUI64Address($mac) {
        // Validate prefix length
        if ($this->preflen != 64) {
            throw new \InvalidArgumentException("Generating of IP addresses using EUI64 is only allowed in autoconfigured networks (/64 subnets). You must use a 64 bit prefix.");
        }

        // Validate mac address
        if (!filter_var($mac, FILTER_VALIDATE_MAC)) {
            throw new \InvalidArgumentException("Invalid mac address.");
        }

        // Expand MAC address and convert it to AAAA:AAAA:AAAA:AAAA format for simple merge with IPv6 address
        $mac = explode(':', str_replace(['.', '-', ':'], ':', $mac));
        $mac = sprintf('%04x', (hexdec($mac[0]) << 8 | hexdec($mac[1])) ^ 0x200) . ':' .
            sprintf('%04x', hexdec($mac[2]) << 8 | 0xff) . ':' .
            sprintf('%04x', hexdec($mac[3]) | 0xfe00) . ':' .
            sprintf('%02x', hexdec($mac[4])) .
            sprintf('%02x', hexdec($mac[5]));

        // Expand IP address
        $ip = new IPv6Address($this->addr);
        $ip = $ip->expand();

        for ($i = 1; $i <= strlen($mac); $i ++) {
            $ip[strlen($ip) - $i] = $mac[strlen($mac) - $i];
        }

        // Return result
        return new IPv6Address($ip);
    }

}
