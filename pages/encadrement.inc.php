<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__."<BR>";
}
statInsererPageSurf(__FILE__);
?>

<table align="center" border='0' cellspacing='0' cellpadding='0' width='450px'>
    <?php
    include "affichage.team.inc.php";

    // afficher avec experience;
    afficherEntete(1, 0, VAR_LOOK_FONDS_BARRE_TITRE_EQUIPE_SUISSE);

    $requeteSQL = "SELECT * FROM `Personne`, `ClubsFstb` WHERE `encadrement`>'0' AND `ClubsFstb`.`id`=`Personne`.`idClub` ORDER BY `encadrement`, `nom`, `prenom`";
    $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

    while ($record = mysql_fetch_array($recordset)) {
        afficherPersonneTeam($record, "_port", 1, 0, $i%2?VAR_LOOK_FONDS_INTERIEUR:VAR_LOOK_FONDS_INTERIEUR_UNE_SUR_DEUX);
    }
    ?>
</table>
