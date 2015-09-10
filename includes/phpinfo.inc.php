<?php
session_start();

include "access_control_matrix.inc.php";

if (isAdmin()) {
	phpinfo();
}
?>