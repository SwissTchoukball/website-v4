<?php

$devWebsite = $_SERVER["SERVER_NAME"] == 'localhost';

if ($devWebsite) {
    // API
    define('API_HOST', '');

    $sql['hote'] = '';
    $sql['user'] = '';
    $sql['password'] = '';
    $sql['base'] = '';

    $sql['basetechnique'] = ''; // TODO: remove the necessity for this database
} else {
    // API
    define('API_HOST', '');

    $sql['hote'] = '';
    $sql['user'] = '';
    $sql['password'] = '';
    $sql['base'] = '';

    $sql['basetechnique'] = ''; // TODO: remove the necessity for this database
}

mysql_pconnect($sql['hote'], $sql['user'], $sql['password']);
mysql_select_db($sql['base']);
mysql_set_charset('latin1');

setlocale(LC_MONETARY, 'fr_CH');
date_default_timezone_set('Europe/Zurich');

// Enter Flickr API key here
define("FLICKR_API_KEY", "");
