<?php
session_start();
include "debug.inc.php";
if ($_SESSION["debug_tracage"]) {
    echo __FILE__."<BR>";
}

// connexion a la bd
if ($_SERVER["HTTP_HOST"] == 'localhost') {
    require('config-dev.php');
    $devWebsite = true;
} else {
    require('config.php');
    $devWebsite = false;
}

// tout utilisateur qui se connect a un niveau 100 pour commencer
if (!isset($_SESSION["__userLevel__"])) {
    $_SESSION["__userLevel__"]=100;
    // Attention, si qque se connect via un lien sur newsletter, ne pas afficher de confirmation d'inscription
    $actionNewsLetter = "";
    $_GET["actionNewsLetter"] = "";
}

// !Cookie
// reprise des infos du cookie s'il existe pour changer le niveau d'utilisateur et reprendre les infos
if ($_SESSION["__userLevel__"] == 100 && isset($_COOKIE["login"])) {
    $cookie=$_COOKIE["login"];

    $requeteSQL = "SELECT p.id, nom, prenom, username, userLevel, password, idClub, gestionMembresClub, c.nbIdClub
                   FROM `Personne` p, `ClubsFstb` c
                   WHERE p.`username`='".$cookie["username"]."' AND p.`idClub`=c.`id`";
    $recordset = mysql_query($requeteSQL);
    $record = mysql_fetch_array($recordset);
    // validite du resultat
    if ($record !== false && $record["password"] == $cookie["password"]) {
        $_SESSION["__nom__"]=$record['nom'];
        $_SESSION["__prenom__"]=$record['prenom'];
        $_SESSION["__idUser__"]=$record['id'];
        $_SESSION["__username__"]=$record['username'];
        $_SESSION["__userLevel__"]=$record['userLevel'];
        $_SESSION["__idClub__"]=$record['idClub'];
        $_SESSION["__nbIdClub__"]=$record['nbIdClub'];
        $_SESSION["__gestionMembresClub__"]=$record['gestionMembresClub'];

        // prolongation du cookie
        $troisMois = time()+(3600*24*30)*3;
        setcookie("login[username]", $_SESSION["__username__"], $troisMois, "/");
        setcookie("login[password]", $record["password"], $troisMois, "/");
        //setcookie("login[idUser]",$_SESSION["__idUser__"],$troisMois,"/");

        // Insertion du login dans l'historique des logins
        $maintenant = getdate();
        $requeteSQL = "INSERT INTO `HistoriqueLogin` ( `username` , `date` , `heure` ) VALUES ('".$record["username"]."', '".$maintenant["year"]."-".$maintenant["mon"]."-".$maintenant["mday"]."', '".$maintenant["hours"].":".$maintenant["minutes"].":".$maintenant["seconds"]."')";
        mysql_query($requeteSQL);
    } else {
        $_SESSION["__userLevel__"]=100;
    }
}

// variables "globales"
include "var.inc.php";

// variable de lien fixe
include "var.href.inc.php";

// Access Control Matrix
include "access_control_matrix.inc.php";

// fonctions
include "fonctions.inc.php";

// variables du look
include "var.look.inc.php";

// gestion des langues
include "langue/langue.inc.php";

// pour encode une adresse email en javascript
include "email.encode.inc.php";

// donnes acces aux fonctions de stats
include "statistique.inc.php";

// Markdown
include "markdown.php";

require_once("phpFlickr.php");

///////////////////////
// !Maintenance TOTALE
///////////////////////
if ($ETAT_EN_MAINTENANCE_TOTALE) {
    if ($_SESSION["__userLevel__"]>0) {
        include "maintenance.php";
    }
}
///////////////////////

// Menu normal ou menu admin
if ($_SESSION["__userLevel__"] < 100 && $admin) {
    $typemenu="MenuAdmin";
} else {
    $typemenu="Menu";
}

// choisir le bon tableau des menus
if (stristr($PHP_SELF, VAR_HREF_PAGE_ADMIN) !== false) {
    $VAR_TAB_MENU = $VAR_TAB_MENU_ADMIN;
} else {
    $VAR_TAB_MENU = $VAR_TAB_MENU_WEB;
}

// toujours valider les menus, sauf pour le login
if (!(stristr($PHP_SELF, VAR_HREF_PAGE_ADMIN) !== false && $_SESSION["__userLevel__"]==100)) {
    // regarder si les numéros des menus et/ou sous menus sont correct
    include "validite.menu.inc.php";
}

// creation de graphe avant le header via la variable actionSpeciale
if (stristr($PHP_SELF, VAR_HREF_PAGE_ADMIN)!==false && $actionSpeciale=="creerGraphe") {
    include "creationGraphique.inc.php";
}


// !Paramétrage du contenu de <head>
$titre="";
$prefixDev="";
if ($devWebsite) {
    $prefixDev= "DEVELOPMENT ~ ";
}

if (isset($_GET["login"])) {
    $titre.= " | Login";
} else {
    $retour = mysql_query("SELECT nom".$_SESSION["__langue__"]." FROM ".$typemenu." WHERE sousMenuDeId='".$menuselection."' AND ordre='".$smenuselection."'");
    while ($donnees = mysql_fetch_array($retour)) {
        if ($menuselection != 1 || $smenuselection != 0 || $admin) {
            //Pas la page d'accueil
            $titre.= " | " . strip_tags($donnees["nom".$_SESSION["__langue__"]]);
        }
    }
}
$description = "Site de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ". Le tchoukball est un sport pour tous. Accessible, intense, tactique et fair-play, il est le sport de demain.";
$facebook_type = "website";
$tailleDescription = 300;
/*if($menuselection==1 && $smenuselection==0 && !$admin){//Page Accueil
    $titreNews = mysql_fetch_assoc(mysql_query("SELECT titre".$_SESSION["__langue__"]." AS titre FROM News ORDER by premiereNews DESC, Date DESC LIMIT 0,1"));
    $titre.= " : ".strip_tags($titreNews['titre']);
}
elseif(isset($newsIdSelection) && $newsIdSelection!=""){ //Page NEWS
    $news = mysql_fetch_assoc(mysql_query("SELECT id, titre".$_SESSION["__langue__"]." AS titre, corps".$_SESSION["__langue__"]." AS corps FROM `News` WHERE `Id`='".$newsIdSelection."'"));
    $titre.= " : ".strip_tags($news['titre']);
    $description = strip_tags(sizeNewsManager($news['corps'],$tailleDescription,$news['id']));
    $facebook_type = "article";
}*/
if (isset($newsIdSelection) && $newsIdSelection != "") {
    //Page NEWS
    $news = mysql_fetch_assoc(mysql_query("SELECT id, titre".$_SESSION["__langue__"]." AS titre, corps".$_SESSION["__langue__"]." AS corps FROM `News` WHERE `Id`='".$newsIdSelection."'"));
    $titre.= " : ".strip_tags($news['titre']);
    //$description = strip_tags(sizeNewsManager($news['corps'],$tailleDescription,$news['id']));
    $description = truncateHtml(markdown($news['corps']), $tailleDescription, " ...");
    $description = str_replace("’", "'", $description);
    $description = strip_tags($description);
    $facebook_type = "article";
}
$title = $prefixDev.VAR_LANG_ASSOCIATION_NAME.$titre;


// !Facebook Javascript SDK
// Locale code
// The basic format is ll_CC, where ll is a two-letter language code, and CC is a two-letter country code.
// See https://developers.facebook.com/docs/internationalization/#locales
$locale_code_ISOlanguage = strtolower($_SESSION["__langue__"]);
if ($locale_code_ISOlanguage == "en") {
    $locale_code = $locale_code_ISOlanguage."_US";
} else {
    $locale_code = $locale_code_ISOlanguage."_".strtoupper($locale_code_ISOlanguage);
}



// !Login des admins
if (stristr($PHP_SELF, VAR_HREF_PAGE_ADMIN) !== false &&
    $_SESSION["__adminModeUtilise__"] !== true &&
    ($_SESSION["__nom__"]!="" || $_SESSION["__prenom__"]!="")) {
    $_SESSION["__adminModeUtilise__"]=true;
    // Insertion du login dans l'historique des logins
    $requeteSQL = "SELECT * FROM `Personne` WHERE `Personne`.`nom`='".addslashes($_SESSION["__nom__"])."' AND `Personne`.`prenom`='".addslashes($_SESSION["__prenom__"])."'";
    $recordset = mysql_query($requeteSQL);
    $record = mysql_fetch_array($recordset);

    $maintenant = getdate();
    $requeteSQL = "INSERT INTO `HistoriqueLoginAdmin` (`idPersonne` , `date` , `heure`) VALUES ('".$record["id"]."', '".$maintenant["year"]."-".$maintenant["mon"]."-".$maintenant["mday"]."', '".$maintenant["hours"].":".$maintenant["minutes"].":".$maintenant["seconds"]."')";
    mysql_query($requeteSQL);
}
