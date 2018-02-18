<?php
$debugMessage = "";

//Affichage de la liste des clubs avec leur état actuel de paiemenet de cotisations
$clubsFeeState = ClubService::getClubsFeeState();
foreach ($clubsFeeState as $key => $clubFeeState) {
    $annee = $clubFeeState['seasonStartYear'];
    $idClub = $clubFeeState['clubId'];

    //Gestion du changement d'état de paiement
    if (isset($_POST[$annee . ':' . $idClub . ':paye'])) {
        $nouveauMontantPaye = $_POST[$annee . ':' . $idClub . ':paye'] == 1;
        $nouveauAnneePaiement = $_POST[$annee . ':' . $idClub . ':annee'];
        $nouveauMoisPaiement = $_POST[$annee . ':' . $idClub . ':mois'];
        $nouveauJourPaiement = $_POST[$annee . ':' . $idClub . ':jour'];
        if (($nouveauMontantPaye && $clubFeeState['paymentDate'] == null) ||
            ($nouveauMontantPaye &&
                ($nouveauAnneePaiement != substr($clubFeeState['paymentDate'], 0, 4) ||
                $nouveauMoisPaiement != substr($clubFeeState['paymentDate'], 5, 2) ||
                $nouveauJourPaiement != substr($clubFeeState['paymentDate'], 8, 2)))
        ) { //Non-payé -> payé OU changement de date
            if (checkdate($nouveauMoisPaiement, $nouveauJourPaiement, $nouveauAnneePaiement)) {
                $clubFeeState['paymentDate'] = $nouveauAnneePaiement . "-" . $nouveauMoisPaiement . "-" . $nouveauJourPaiement;
                try {
                    ClubService::markClubFeeAsPaid($idClub, $annee, $clubFeeState['paymentDate']);
                } catch (Exception $exception) {
                    printErrorMessage(
                        "La cotisation n'a pas pu être marqué comme payé pour " .
                        $clubFeeState['clubName'] . ".<br>" . $exception->getMessage()
                    );
                }
            } else {
                printErrorMessage('Mauvais format de date');
            }
        } else if (!$nouveauMontantPaye && $clubFeeState['paymentDate'] != null) { //Payé -> non-payé
            $clubFeeState['paymentDate'] = null;
            try {
                ClubService::markClubFeeAsUnpaid($idClub, $annee, $clubFeeState['paymentDate']);
            } catch (Exception $exception) {
                printErrorMessage(
                    "La cotisation n'a pas pu être marqué comme impayé pour " .
                    $clubFeeState['clubName'] . ".<br>" . $exception->getMessage()
                );
            }
        }
        $clubsFeeState[$key]['paymentDate'] = $clubFeeState['paymentDate']; // update in original array for the table
    }
}
?>

<h4>Situation actuelle</h4>
<form id="etatCotisations" method="post"
      action="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>">
    <table class="st-table">
        <tr>
            <th>Saison</th>
            <th>Club</th>
            <th>Montant</th>
            <th>Payé</th>
        </tr>
        <?php foreach ($clubsFeeState as $clubFeeState): ?>
        <tr class="<?php echo $clubFeeState['paymentDate'] != null ? "st-table__ok" : "st-table__wrong"; ?>">
            <td><?php echo $clubFeeState['seasonStartYear'] . " - " . ($clubFeeState['seasonStartYear'] + 1); ?></td>
            <td><?php echo $clubFeeState['clubName']; ?></td>
            <td><?php echo $clubFeeState['amount'] . " CHF"; ?></td>
            <td>
                <select name="<?php echo $clubFeeState['seasonStartYear']; ?>:<?php echo $clubFeeState['clubId']; ?>:paye" title="Statut">
                    <option value="1" <?php echo $clubFeeState['paymentDate'] !== null ? "selected='selected'" : ""; ?>>Payé</option>
                    <option value="0" <?php echo $clubFeeState['paymentDate'] !== null ? "" : "selected='selected'"; ?>>Non-payé</option>
                </select>
                <label> le </label>
                <select name="<?php echo $clubFeeState['seasonStartYear']; ?>:<?php echo $clubFeeState['clubId']; ?>:jour" title="Date de paiement (jour)">
                    <?php echo modif_liste_jour(substr($clubFeeState['paymentDate'], 8, 2)); ?>
                </select>
                <select name="<?php echo $clubFeeState['seasonStartYear']; ?>:<?php echo $clubFeeState['clubId']; ?>:mois" title="Date de paiement (mois)">
                    <?php echo modif_liste_mois(substr($clubFeeState['paymentDate'], 5, 2)); ?>
                </select>
                <select name="<?php echo $clubFeeState['seasonStartYear']; ?>:<?php echo $clubFeeState['clubId']; ?>:annee" title="Date de paiement (année)">
                    <?php echo modif_liste_annee(-1, 0, substr($clubFeeState['paymentDate'], 0, 4)); ?>
                </select>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <input type="submit" value="Valider" class="button button--primary"/>
</form>
<script>
    $(function () {
        // Ici, le DOM est entièrement défini
    });
</script>
