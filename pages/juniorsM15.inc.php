<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);

$afficherNumero = false;

$query = "SELECT p.body" . $_SESSION['__langue__'] . " AS body
        FROM IdTextCorpPage p
        WHERE p.id = 41";
$resource = mysql_query($query);
$pageData = mysql_fetch_assoc($resource);
$body = Markdown($pageData['body']);
echo '<p>' . $body . '</p>';
?>
<!--<div class="equipe-es">
    <div class="photo-equipe-es"><img src="<?php /*echo VAR_IMAGE_PHOTOS_EQUIPES_PATH . "m15_2013-02-09.jpg"; */?>"></div>
    <div class="legende-photo-equipe-es"></div>
</div>-->

<div class="liste-joueur-es">
    <?php
    include "affichage.team.inc.php";

    $query = getTeamQuery(4);
    $recordset = mysql_query($query) or die ("<H1>mauvaise requete</H1>");

//    while ($record = mysql_fetch_array($recordset)) {
//        afficherPersonneTeam($record, "_port", $afficherNumero);
//    }
    ?>
</div>

<?php
showTeamCoaches(4);
showCommissionHead(13);
