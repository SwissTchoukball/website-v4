<?php
include('../config.php');

date_default_timezone_set('Europe/Zurich');

$query = "UPDATE Download SET visible=1 WHERE idType=8 AND date='" . date('Y-m-d') . "'";
mysql_query($query);

mysql_close();
