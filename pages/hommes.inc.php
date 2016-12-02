<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);

$afficherNumero = false;
?>
<div class="equipe-es">
    <div class="photo-equipe-es"><img src="<?php echo PATH_TO_ROOT . VAR_IMAGE_PHOTOS_EQUIPES_PATH . "hommes.jpg"; ?>"
                                      border="0"></div>
    <div class="legende-photo-equipe-es"></div>
</div>

<div class="liste-joueur-es">
    <?php
    include "affichage.team.inc.php";

    $query = getTeamQuery(1);
    $recordset = mysql_query($query) or die ("<H1>mauvaise requete</H1>");

    /*$requeteSQL = "SELECT *, `equipeHomme` AS `idPosteEquipe`
                   FROM `Personne`, `ClubsFstb`
                   WHERE `equipeHomme`>'0'
                   AND `ClubsFstb`.`id`=`Personne`.`idClub`
                   ORDER BY `equipeHomme`, `nom`, `prenom`";
    $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");*/


    while ($record = mysql_fetch_array($recordset)) {
        afficherPersonneTeam($record, "_port", $afficherNumero);
    }
    ?>
</div>

<div class="acceder-bdd-fitb-es"><a href="http://www.fitbcompetitions.org"
                                    target="_blank"><?php echo VAR_LANG_ACCES_FITB_BDD; ?></a></div>
