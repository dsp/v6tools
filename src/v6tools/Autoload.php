<?php

namespace v6tools;

class Autoload {
    public static function load($name) {
        if (0 !== strpos($name, 'v6tools')) {
            return false;
        }

        $filename = realpath(__DIR__ . DIRECTORY_SEPARATOR .
            str_replace('v6tools', '', 
                str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php'));
        require_once ($filename);
        return true;
    }
}

spl_autoload_register(__NAMESPACE__ . '\Autoload::load');
