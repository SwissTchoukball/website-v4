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
        $user = UserService::getByUsernameOrEmail($_POST['username']);
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

    if (md5($_POST["password"]) == $user['password']) {
        $_SESSION["__nom__"] = $user['nom'];
        $_SESSION["__prenom__"] = $user['prenom'];
        $_SESSION["__idUser__"] = $user['id'];
        $_SESSION["__username__"] = $user['username'];
        $_SESSION["__userLevel__"] = $user['userLevel'];
        $_SESSION['__authdata__'] = base64_encode($user['username'] . ':' . $user['password']);
        $_SESSION["__idClub__"] = $user['idClub'];
        $_SESSION["__nbIdClub__"] = $user['nbIdClub'];
        $_SESSION["__gestionMembresClub__"] = $user['gestionMembresClub'];

        // gestion de l'autoconnexion par cookie
        if ($_POST["autoConnect"] != "") {
            // creation du cookie
            $troisMois = time() + (3600 * 24 * 30) * 3;

            setcookie("login[username]", $_SESSION["__username__"], $troisMois, "/");
            setcookie("login[password]", $user["password"], $troisMois, "/");
        }

        // Insertion du login dans l'historique des logins
        UserService::logLogin($user['username']);

        header("Location: http://$host$uri" . $afterLoginTarget, true);
    } else {
        // Wrong password
        header("Location: http://$host$uri/login-fail-1", true);
    }
} else {
    // Missing fields
    header("Location: http://$host$uri/login-fail-3", true);
}
