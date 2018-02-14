<?php
// attribut la langue fr par defaut si aucune n'est selectionnee.
// selection du fichier de variables dans la bonne langue.

if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}

include "tab.langue.inc.php";

// si si le premier parametre appartient au tableau des langues.
function langueValide($langue, $tableauDesLangues)
{
    for ($i = 0; $i < count($tableauDesLangues); $i++) {
        if ($langue == $tableauDesLangues[$i][0]) {
            return false;
        }
    }
    return true;
}

$langchange = '';
if (isset($_GET['langchange'])) {
    $langchange = $_GET['langchange'];
}

if ($langchange != "" && $langchange != $_SESSION["__langue__"]) {

    // validité du changement de langue, si erreur => premiere langue
    if ($langchange == "" || langueValide($langchange, $VAR_TABLEAU_DES_LANGUES)) {
        $_SESSION["__langue__"] = $VAR_TABLEAU_DES_LANGUES[0][0];
    } else {
        $_SESSION["__langue__"] = $langchange;
    }
}

// pas de langue au depart, donc surf en francais
if ($_SESSION["__langue__"] == "") {
    $_SESSION["__langue__"] = $VAR_TABLEAU_DES_LANGUES[0][0];
}

include $_SERVER["DOCUMENT_ROOT"] . "/includes/langue/var." . $_SESSION["__langue__"] . ".inc.php";
