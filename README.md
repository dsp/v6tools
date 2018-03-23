PHP IPv6 Tools
==============

[![Build Status](https://secure.travis-ci.org/dsp/v6tools.png?branch=master)](http://travis-ci.org/dsp/v6tools)

PHP IPv6 Tools (v6tools) is a small library that provides validation of
IPv6 addresses, subnets and EUI64.

    <?php
    require('v6tools/autoload.php');
    
    if (!v6tools\Runtime::isIPv6Supported()) {
        fprintf(STDERR, "No ipv6 support");
        exit(-1);
    }

    $ip = new v6tools\EUI64('2a01:198:603:0:224:d6ff:fe18:618c');
    echo $ip->getMacAddress();
    // echos 00:24:d6:18:61:8c
    
    $ip = new v6tools\Subnet('2001::/16');
    $ip->isInSubnet('2001::1');
    // returns true
    $ip->isInSubnet('2000::1');
    // return false

License
-------
Licensed under the terms of the MIT License with additional Beerware clause.
If you like v6tools feel free to buy me beer.
