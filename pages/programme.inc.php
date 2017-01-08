<?php
// programme championnat
statInsererPageSurf(__FILE__);
/*
if($ETAT_PROGRAMME_CHAMPIONNAT_COMMENCE){
    //$categorie=7; // ==> catergorie championnat
    include "pages/championnat.agenda.inc.php";
}
else{
    echo "<p class='titresectiontext' align='center'>";
    afficherAvecEncryptageEmail(VAR_LANG_CHAMPIONNAT_NON_COMMENCE);
    echo "</p>";
}*/

if (!$ETAT_PROGRAMME_CHAMPIONNAT_COMMENCE) {
    echo "<p class='titresectiontext' align='center'>";
    afficherAvecEncryptageEmail(VAR_LANG_CHAMPIONNAT_NON_COMMENCE);
    echo "</p>";
}

include "pages/championnat.agenda.inc.php";
?>
