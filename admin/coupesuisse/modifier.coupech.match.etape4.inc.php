<?php ?>
<h3>
    <?php echo VAR_LANG_ETAPE_4; ?>
</h3>
<?php
if (!isset($_POST['idMatch'])) {
    echo "Erreur: il manque des informations.";
} else {
    $idMatch = $_POST['idMatch'];

    $nbSetsGagnants = $_POST['nbSetsGagnants'];
    $nbMaxSets = $_POST['nbMaxSets'];
    $scoreGagnantParForfait = $_POST['scoreGagnantParForfait'];

    $idTypeMatchValide = false;

    if (isset($_POST['AGagneParForfait'])) {
        $forfait = 1;
        for ($k = 1; $k <= $nbSetsGagnants; $k++) {
            $score['A' . $k] = $scoreGagnantParForfait;
            $score['B' . $k] = 0;
        }
        for ($k = $nbSetsGagnants + 1; $k <= $nbMaxSets; $k++) {
            $score['A' . $k] = 0;
            $score['B' . $k] = 0;
        }
    } elseif (isset($_POST['BGagneParForfait'])) {
        $forfait = 1;
        for ($k = 1; $k <= $nbSetsGagnants; $k++) {
            $score['A' . $k] = 0;
            $score['B' . $k] = $scoreGagnantParForfait;
        }
        for ($k = $nbSetsGagnants + 1; $k <= $nbMaxSets; $k++) {
            $score['A' . $k] = 0;
            $score['B' . $k] = 0;
        }
    } elseif (isset($_POST['ADisqualifie'])) {
        $forfait = 2;
        for ($k = 1; $k <= $nbSetsGagnants; $k++) {
            $score['A' . $k] = 0;
            $score['B' . $k] = $scoreGagnantParForfait;
        }
        for ($k = $nbSetsGagnants + 1; $k <= $nbMaxSets; $k++) {
            $score['A' . $k] = 0;
            $score['B' . $k] = 0;
        }
    } elseif (isset($_POST['BDisqualifie'])) {
        $forfait = 2;
        for ($k = 1; $k <= $nbSetsGagnants; $k++) {
            $score['A' . $k] = $scoreGagnantParForfait;
            $score['B' . $k] = 0;
        }
        for ($k = $nbSetsGagnants + 1; $k <= $nbMaxSets; $k++) {
            $score['A' . $k] = 0;
            $score['B' . $k] = 0;
        }
    } else {
        $forfait = 0;
        for ($k = 1; $k <= $nbMaxSets; $k++) {
            $score['A' . $k] = $_POST['scoreA' . $k];
            $score['B' . $k] = $_POST['scoreB' . $k];
        }
    }

    $nomEquipeA = $_POST['EquipeA'];
    $nomEquipeB = $_POST['EquipeB'];

    // ATTENTION !!! Si le nombre de sets gagnant augmente il faut changer le code ici et aussi ajouter des colonnes dans la BDD !!!
    $requete = "UPDATE `CoupeCH_Matchs` SET scoreA1=" . $score['A1'] . ", scoreB1=" . $score['B1'] . ", scoreA2=" . $score['A2'] . ", scoreB2=" . $score['B2'] . ", scoreA3=" . $score['A3'] . ", scoreB3=" . $score['B3'] . ", scoreA4=" . $score['A4'] . ", scoreB4=" . $score['B4'] . ", scoreA5=" . $score['A5'] . ", scoreB5=" . $score['B5'] . ", forfait=" . $forfait . ", heureDebut='" . $_POST['debutHeure'] . ":" . $_POST['debutMinute'] . ":00', heureFin='" . $_POST['finHeure'] . ":" . $_POST['finMinute'] . ":00', utilisateur='" . $_SESSION['__prenom__'] . $_SESSION['__nom__'] . "' WHERE idMatch=" . $_POST['idMatch'];
    // echo $requete."<br />";
    mysql_query($requete) or die("Erreur, le match " . $nomEquipeA . " - " . $nomEquipeB . " n'a pas été modifié." . mysql_error());
    echo $nomEquipeA . " - " . $nomEquipeB . " : OK !<br />";

    include("modifier.coupech.match.miseajour.inc.php");

}
?>
<p class="center"><a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>">Modifier
        d'autres matchs</a></p><br/>
<p class="center"><a
        href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&modAnnee=<?php echo $_POST['annee']; ?>&modCat=<?php echo $_POST['idCategorie']; ?>">Modifier
        d'autres matchs de la même édition</a></p>

