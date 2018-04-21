<?php

session_start();
include "includes/var.href.inc.php";

if (isset($_COOKIE["login"])) {
    // suppression du cookie
    setcookie("login[nom]", "", 0, "/");
    setcookie("login[prenom]", "", 0, "/");
    setcookie("login[password]", "", 0, "/");
}
session_destroy();

$protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: $protocol://$host$uri" . VAR_HREF_PAGE_PRINCIPALE, true);
