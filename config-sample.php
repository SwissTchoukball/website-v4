<?php

$devWebsite = $_SERVER["SERVER_NAME"] == 'localhost';

if ($devWebsite) {
    // API
    define('API_HOST', '');

    // MySQL DB
    $sql['hote'] = '';
    $sql['user'] = '';
    $sql['password'] = '';
    $sql['base'] = '';
} else {
    // API
    define('API_HOST', '');

    // MySQL DB
    $sql['hote'] = '';
    $sql['user'] = '';
    $sql['password'] = '';
    $sql['base'] = '';
}

mysql_pconnect($sql['hote'], $sql['user'], $sql['password']);
mysql_select_db($sql['base']);
mysql_set_charset('latin1');

setlocale(LC_MONETARY, 'fr_CH');
date_default_timezone_set('Europe/Zurich');

// Enter API keys here
define("FLICKR_API_KEY", "");
define("GOOGLE_API_KEY", "");
