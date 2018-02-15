<?php
session_start();
include "debug.inc.php";
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}

header('Content-Type: text/html; charset=ISO-8859-1');

if (!isset($admin)) {
    $admin = false;
}

// DB connexion and other settings
require($_SERVER["DOCUMENT_ROOT"] . '/config.php');

// tout utilisateur qui se connect a un niveau 100 pour commencer
if (!isset($_SESSION["__userLevel__"])) {
    $_SESSION["__userLevel__"] = 100;
    // Attention, si qque se connect via un lien sur newsletter, ne pas afficher de confirmation d'inscription
    $actionNewsLetter = "";
    $_GET["actionNewsLetter"] = "";
}

// variables "globales"
include "var.inc.php";

// variable de lien fixe
include "var.href.inc.php";

// Markdown
include "markdown.php";

// pour encode une adresse email en javascript
include "email.encode.inc.php";

// DB services
include_once 'services/services.php';

// reprise des infos du cookie s'il existe pour changer le niveau d'utilisateur et reprendre les infos
if ($_SESSION["__userLevel__"] == 100 && isset($_COOKIE["login"])) {
    $cookie = $_COOKIE["login"];

    try {
        $user = UserService::getUserByUsernameOrEmail($_POST['username']);
    }
    catch (Exception $exception) {
        exit('SQL error on login');
    }

    if ($user) {
        UserService::login($user, $cookie['password'], true);
    }
}

// Access Control Matrix (requires user to be logged in)
include "access_control_matrix.inc.php";

// fonctions (requires services and user to be logged in)
include "fonctions.inc.php";

// gestion des langues (requires services and user to be logged in)
include "langue/langue.inc.php";

// donnes acces aux fonctions de stats
include "statistique.inc.php";

require_once("phpFlickr.php");

require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/Navigation.class.php";

///////////////////////
// !Maintenance TOTALE
///////////////////////
if ($ETAT_EN_MAINTENANCE_TOTALE) {
    if ($_SESSION["__userLevel__"] > 0) {
        include $_SERVER["DOCUMENT_ROOT"] . "/maintenance.php";
    }
}
///////////////////////

// Redirecting to login if trying to access admin while being disconnected
if ($admin && $_SESSION["__userLevel__"] >= 100) {
    $host = $_SERVER['HTTP_HOST'];
    $redirectUrl = urlencode($_SERVER['REQUEST_URI']);
    header("Location: http://$host/index.php?login&redirect=$redirectUrl");
}

// Menu normal ou menu admin
if ($_SESSION["__userLevel__"] < 100 && $admin) {
    $navigation = new Navigation('admin');

} else {
    $navigation = new Navigation('main');
}

// regarder si les numéros des menus et/ou sous menus sont correct
try {
    if (isset($_GET[VAR_HREF_LIEN_MENU])) {
        $navigation->setCurrentMenuItemById($_GET[VAR_HREF_LIEN_MENU]);
    } elseif (isset($_GET["menuselection"]) && isset($_GET["smenuselection"])) {
        // We keep this for backward compatibility, but no link from the website should use these query strings anymore.
        $navigation->setCurrentMenuItemByParentIdAndOrder($_GET["menuselection"], $_GET["smenuselection"]);
    }
} catch (Exception $exception) {
    header("HTTP/1.0 500 Internal Server Error", true, 500);
}

// !Paramétrage du contenu de <head>
$titre = "";
$prefixDev = "";
if ($devWebsite) {
    $prefixDev = "DEVELOPMENT ~ ";
}

if (isset($_GET["login"])) {
    $titre .= " | Login";
} else {
    $titre .= " | " . strip_tags($navigation->getCurrentMenuItem()['name']);
}
$description = "Site de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ". Le tchoukball est un sport pour tous. Accessible, intense, tactique et fair-play, il est le sport de demain.";
$facebook_type = "website";
$tailleDescription = 300;

if (isset($newsIdSelection) && $newsIdSelection != "") {
    //Page NEWS
    $news = mysql_fetch_assoc(mysql_query("SELECT id, titre" . $_SESSION["__langue__"] . " AS titre, corps" . $_SESSION["__langue__"] . " AS corps FROM `News` WHERE `Id`='" . $newsIdSelection . "'"));
    $titre .= " : " . strip_tags($news['titre']);
    $description = truncateHtml(markdown($news['corps']), $tailleDescription, " ...");
    $description = str_replace("’", "'", $description);
    $description = strip_tags($description);
    $facebook_type = "article";
}
$title = $prefixDev . VAR_LANG_ASSOCIATION_NAME . $titre;


// !Facebook Javascript SDK
// Locale code
// The basic format is ll_CC, where ll is a two-letter language code, and CC is a two-letter country code.
// See https://developers.facebook.com/docs/internationalization/#locales
$locale_code_ISOlanguage = strtolower($_SESSION["__langue__"]);
if ($locale_code_ISOlanguage == "en") {
    $locale_code = $locale_code_ISOlanguage . "_US";
} else {
    $locale_code = $locale_code_ISOlanguage . "_" . strtoupper($locale_code_ISOlanguage);
}