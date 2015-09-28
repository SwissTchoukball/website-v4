<?php
// regarde si les valeurs des menus sont corrects

if ($_SESSION["debug_tracage"]) {
    echo __FILE__."<BR>";
}

if (isset($_GET["menuselection"]) && is_numeric($_GET["menuselection"])) {
    $menuselection = $_GET["menuselection"];
    if (isset($_GET["smenuselection"]) && is_numeric($_GET["smenuselection"])) {
        $smenuselection = $_GET["smenuselection"];
    } else {
        $smenuselection = 0;
    }
} elseif (isset($_GET[VAR_HREF_LIEN_MENU]) && is_numeric($_GET[VAR_HREF_LIEN_MENU])) {
    // lien interne au site. pour garder une validite de menu
    $requetelien = mysql_query("SELECT id, sousMenuDeId, ordre FROM ".$typemenu." WHERE id = " . $_GET[VAR_HREF_LIEN_MENU] . "");
    if ($donneeslien = mysql_fetch_array($requetelien)) {
        $menuselection = $donneeslien['sousMenuDeId'];
        $smenuselection = $donneeslien['ordre'];
        if ($menuselection == -1) {
            $menuselection = $donneeslien['id'];
            $smenuselection = 0;
        }
    } else {
        $menuselection = 1;
        $smenuselection = 0;
    }
} else {
    $menuselection = 1;
    $smenuselection = 0;
}
$requete = "SELECT * FROM ".$typemenu." WHERE sousMenuDeId=".$menuselection." && ordre=".$smenuselection."";
$retour = mysql_query("SELECT * FROM ".$typemenu." WHERE sousMenuDeId=".$menuselection." && ordre=".$smenuselection."");
$donnees = mysql_fetch_array($retour);


$requeteIssetMenu = mysql_query("SELECT COUNT(*) AS nbreMenu FROM ".$typemenu." WHERE sousMenuDeId=".$menuselection."");
$donneesIssetMenu = mysql_fetch_array($requeteIssetMenu);

if ($typemenu=="MenuAdmin") {
    $userLevel = $donnees['userLevel'];
} elseif ($typemenu=="Menu") {
    $userLevel = 100;
} else {
    // ne devrait pas arriver.
    echo "Le type de menu n'est pas spécifié";
}

// regarde la validite du menu et les droits d'acces
if (!is_numeric($menuselection) || $menuselection <0 || ($_SESSION["__userLevel__"] > $userLevel && $donneesIssetMenu['nbreMenu'] > 0)) {
    // premier menu ou l'utilisateur a les droits... attention, si l'utilisateur a acces à un menu, c'est qu'il peut
    // acceder a au moins UN sous-menu !! très important, sinon risque de non sécurité...
    $menuselection=1;
    $smenuselection=0;
    $menuTrouve=true;
    /*
    $menuTrouve=false;
    for($i=0;$i < count($VAR_TAB_MENU) && !$menuTrouve;$i++){
        if ($_SESSION["__userLevel__"] <= $VAR_TAB_MENU[$i][0]->getUserLevel()){
            $menuselection=$i;
            $menuTrouve=true;
        }
    }
    */

    // ne devrait pas arriver...
    if (!$menuTrouve && !is_numeric($menuselection)) {
        exit("Erreur 1a, vous n'avez aucun droit sur cette partie");
    } elseif (!$menuTrouve && $menuselection <0) {
        exit("Erreur 1b, vous n'avez aucun droit sur cette partie");
    } elseif (!$menuTrouve && $_SESSION["__userLevel__"] > $userLevel && $donneesIssetMenu['nbreMenu']>0) {
        exit("Erreur 1c, vous n'avez aucun droit sur cette partie");
    }
}
$requeteIssetSsMenu = mysql_query("SELECT COUNT(*) AS nbreSsMenu FROM ".$typemenu." WHERE sousMenuDeId=".$menuselection."");
$donneesIssetSsMenu = mysql_fetch_array($requeteIssetSsMenu);

// regarde la validite du sous-menu.
if (($smenuselection=="" && $donneesIssetSsMenu['nbreSsMenu']>0) || !is_numeric($smenuselection) ||
     $smenuselection <0 || $_SESSION["__userLevel__"] > $userLevel) {
        $smenuselection=0;
        $sMenuTrouve=true;

    /*
        if ($donnees['nbreSsMenu']==0){
        $sMenuTrouve=true;
        $smenuselection=0;
    }
    else{
        $sMenuTrouve=false;
        for($i=0;$i < count($VAR_TAB_MENU[$menuselection][1]) && !$sMenuTrouve;$i++){
            if ($_SESSION["__userLevel__"] <= $VAR_TAB_MENU[$menuselection][1][$i]->getUserLevel()){
                $smenuselection=$i;
                $sMenuTrouve=true;
            }
        }
    }
        */
    if (!$sMenuTrouve) {
        exit("Erreur 2, vous n'avez aucun droit sur cette partie");
    }
}
