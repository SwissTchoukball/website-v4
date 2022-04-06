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

// !Cookie
// reprise des infos du cookie s'il existe pour changer le niveau d'utilisateur et reprendre les infos
if ($_SESSION["__userLevel__"] == 100 && isset($_COOKIE["login"])) {
    $cookie = $_COOKIE["login"];

    $requeteSQL = "SELECT p.id, nom, prenom, username, userLevel, password, idClub, gestionMembresClub, c.nbIdClub
                   FROM `Personne` p, `clubs` c
                   WHERE p.`username`='" . $cookie["username"] . "' AND p.`idClub`=c.`id`";
    $recordset = mysql_query($requeteSQL);
    $record = mysql_fetch_array($recordset);
    // validite du resultat
    if ($record !== false && $record['password'] == $cookie['password']) {
        $_SESSION["__nom__"] = $record['nom'];
        $_SESSION["__prenom__"] = $record['prenom'];
        $_SESSION["__idUser__"] = $record['id'];
        $_SESSION["__username__"] = $record['username'];
        $_SESSION["__userLevel__"] = $record['userLevel'];
        $_SESSION['__authdata__'] = base64_encode($record['username'] . ':' . $record['password']);
        $_SESSION["__idClub__"] = $record['idClub'];
        $_SESSION["__nbIdClub__"] = $record['nbIdClub'];
        $_SESSION["__gestionMembresClub__"] = $record['gestionMembresClub'];

        // prolongation du cookie
        $threeMonths = time() + (3600 * 24 * 30) * 3;
        setcookie("login[username]", $_SESSION["__username__"], $threeMonths, "/");
        setcookie("login[password]", $record['password'], $threeMonths, "/");
        //setcookie("login[idUser]",$_SESSION["__idUser__"],$threeMonths,"/");

        // Insertion du login dans l'historique des logins
        $maintenant = getdate();
        $requeteSQL = "INSERT INTO `HistoriqueLogin` ( `username` , `date` , `heure` ) VALUES ('" . $record["username"] . "', '" . $maintenant["year"] . "-" . $maintenant["mon"] . "-" . $maintenant["mday"] . "', '" . $maintenant["hours"] . ":" . $maintenant["minutes"] . ":" . $maintenant["seconds"] . "')";
        mysql_query($requeteSQL);
    } else {
        $_SESSION["__userLevel__"] = 100;
    }
}

// variables "globales"
include "var.inc.php";

// variable de lien fixe
include "var.href.inc.php";

// Access Control Matrix
include "access_control_matrix.inc.php";

// Markdown
include "markdown.php";

// fonctions
include "fonctions.inc.php";

// gestion des langues
include "langue/langue.inc.php";

// pour encode une adresse email en javascript
include "email.encode.inc.php";

// donnes acces aux fonctions de stats
include "statistique.inc.php";

require_once("phpFlickr.php");

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
    $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $redirectUrl = urlencode($_SERVER['REQUEST_URI']);
    header("Location: $protocol://$host/index.php?login&redirect=$redirectUrl");
}

// Menu normal ou menu admin
if ($_SESSION["__userLevel__"] < 100 && $admin) {
    $typemenu = "MenuAdmin";
} else {
    $typemenu = "Menu";
}

// toujours valider les menus, sauf pour le login
if (!(isset($PHP_SELF) && stristr($PHP_SELF, VAR_HREF_PAGE_ADMIN) !== false && $_SESSION["__userLevel__"] == 100)) {
    // regarder si les numéros des menus et/ou sous menus sont correct
    include "validite.menu.inc.php";
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
    $retour = mysql_query("SELECT nom" . $_SESSION["__langue__"] . " FROM " . $typemenu . " WHERE sousMenuDeId='" . $menuselection . "' AND ordre='" . $smenuselection . "'");
    while ($donnees = mysql_fetch_array($retour)) {
        if ($menuselection != 1 || $smenuselection != 0 || $admin) {
            //Pas la page d'accueil
            $titre .= " | " . strip_tags($donnees["nom" . $_SESSION["__langue__"]]);
        }
    }
}
$description = "Site de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ". Le tchoukball est un sport pour tous. Accessible, intense, tactique et fair-play, il est le sport de demain.";
$open_graph_type = "website";
$tailleDescription = 300;
/*if($menuselection==1 && $smenuselection==0 && !$admin){//Page Accueil
    $titreNews = mysql_fetch_assoc(mysql_query("SELECT titre".$_SESSION["__langue__"]." AS titre FROM News ORDER by premiereNews DESC, Date DESC LIMIT 0,1"));
    $titre.= " : ".strip_tags($titreNews['titre']);
}
elseif(isset($newsIdSelection) && $newsIdSelection!=""){ //Page NEWS
    $news = mysql_fetch_assoc(mysql_query("SELECT id, titre".$_SESSION["__langue__"]." AS titre, corps".$_SESSION["__langue__"]." AS corps FROM `News` WHERE `Id`='".$newsIdSelection."'"));
    $titre.= " : ".strip_tags($news['titre']);
    $description = strip_tags(sizeNewsManager($news['corps'],$tailleDescription,$news['id']));
    $open_graph_type = "article";
}*/
if (isset($newsIdSelection) && $newsIdSelection != "") {
    //Page NEWS
    $news = mysql_fetch_assoc(mysql_query("SELECT id, titre" . $_SESSION["__langue__"] . " AS titre, corps" . $_SESSION["__langue__"] . " AS corps FROM `News` WHERE `Id`='" . $newsIdSelection . "'"));
    $titre .= " : " . strip_tags($news['titre']);
    //$description = strip_tags(sizeNewsManager($news['corps'],$tailleDescription,$news['id']));
    $description = truncateHtml(markdown($news['corps']), $tailleDescription, " ...");
    $description = str_replace("’", "'", $description);
    $description = strip_tags($description);
    $open_graph_type = "article";
}
$title = $prefixDev . VAR_LANG_ASSOCIATION_NAME . $titre;


// Locale code
$locale_code_ISOlanguage = strtolower($_SESSION["__langue__"]);
if ($locale_code_ISOlanguage == "en") {
    $locale_code = $locale_code_ISOlanguage . "_US";
} else {
    $locale_code = $locale_code_ISOlanguage . "_" . strtoupper($locale_code_ISOlanguage);
}


// !Login des admins
if (isset($PHP_SELF) && stristr($PHP_SELF, VAR_HREF_PAGE_ADMIN) !== false &&
    $_SESSION["__adminModeUtilise__"] !== true &&
    ($_SESSION["__nom__"] != "" || $_SESSION["__prenom__"] != "")
) {
    $_SESSION["__adminModeUtilise__"] = true;
    // Insertion du login dans l'historique des logins
    $requeteSQL = "SELECT * FROM `Personne` WHERE `Personne`.`nom`='" . addslashes($_SESSION["__nom__"]) . "' AND `Personne`.`prenom`='" . addslashes($_SESSION["__prenom__"]) . "'";
    $recordset = mysql_query($requeteSQL);
    $record = mysql_fetch_array($recordset);

    $maintenant = getdate();
    $requeteSQL = "INSERT INTO `HistoriqueLoginAdmin` (`idPersonne` , `date` , `heure`) VALUES ('" . $record["id"] . "', '" . $maintenant["year"] . "-" . $maintenant["mon"] . "-" . $maintenant["mday"] . "', '" . $maintenant["hours"] . ":" . $maintenant["minutes"] . ":" . $maintenant["seconds"] . "')";
    mysql_query($requeteSQL);
}
