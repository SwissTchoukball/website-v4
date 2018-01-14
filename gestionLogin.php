<?php
session_start();

require_once 'includes/services/user.service.php';
include_once 'includes/var.href.inc.php';

$afterLoginTarget = VAR_HREF_PAGE_ADMIN;
if (isset($_GET['redirect']) && $_GET['redirect'] != '') {
    $afterLoginTarget = urldecode($_GET['redirect']);
}

$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// If already logged in (logged in from another window and stayed on the login page in this window)
if ($_SESSION["__userLevel__"] < 100) {
    header("Location: http://$host$uri" . $afterLoginTarget, true);
}

// se faire passer pour la partie admin
$PHP_SELF = VAR_HREF_PAGE_ADMIN;

// les champs existent ?
if (isset($_POST["login"]) && isset($_POST["username"]) && isset($_POST["password"])) {
    try {
        $user = UserService::getUserByUsernameOrEmail($_POST['username']);
    }
    catch (Exception $exception) {
        // SQL error
        header("Location: http://$host$uri/login-fail-4", true);
        exit();
    }

    if (!$user) {
        // No matching user
        header("Location: http://$host$uri/login-fail-1", true);
        exit();
    }

    $isLoggedIn = UserService::login($user, md5($_POST["password"]), $_POST["autoConnect"] != "");

    if ($isLoggedIn) {
        header("Location: http://$host$uri" . $afterLoginTarget, true);
    } else {
        // Wrong password
        header("Location: http://$host$uri/login-fail-1", true);
    }
} else {
    // Missing fields
    header("Location: http://$host$uri/login-fail-3", true);
}
