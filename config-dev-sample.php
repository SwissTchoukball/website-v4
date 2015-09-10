<?php

$sql['hote'] = 'localhost';
// Enter MySQL username here
$sql['user'] = '';
// Enter MySQL password here
$sql['password'] = '';
// Enter MySQL main database name here
$sql['base'] = '';
// Enter MySQL "technique" database here
$sql['basetechnique'] = ''; // TODO: remove the necessity for this database

@mysql_pconnect($sql['hote'], $sql['user'], $sql['password']);
@mysql_select_db($sql['base']);
mysql_set_charset('latin1');

setlocale(LC_MONETARY, 'fr_CH');

// Enter Flickr API key here
define("FLICKR_API_KEY", "");
