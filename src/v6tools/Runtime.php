<?php

namespace v6tools;

final class Runtime {
    /**
     * Returns true if PHP is build with IPv6 supported.
     *
     * If the socket extension is not available we are trying to call inet_pton
     * with an IPv6 address. This can issue a warning if the @-operator is
     * disabled.
     *
     * @return boolean
     */
    public static function isIPv6Supported() {
        return (extension_loaded('sockets') && defined('AF_INET6'))
            || @inet_pton('::1');
    }
}
