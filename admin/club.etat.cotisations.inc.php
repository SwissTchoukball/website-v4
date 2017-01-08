<h4>Situation actuelle</h4>
<form id="etatCotisations" method="post"
      action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>">
    <table>
        <tr>
            <th>Saison</th>
            <th>Club</th>
            <th>Montant</th>
            <th>Payé</th>
        </tr>
        <?php
        $anneePassee = date('Y') - 1;
        $debugMessage = "";

        //Affichage de la liste des clubs avec leur état actuel de paiemenet de cotisations
        $clubQuery =
            "SELECT Cotisations_Clubs.id, annee, ClubsFstb.club, Cotisations_Clubs.idClub, montant, datePaiement
			 FROM Cotisations_Clubs, ClubsFstb
			 WHERE Cotisations_Clubs.idClub=ClubsFstb.nbIdClub
			 	AND annee>='" . $anneePassee . "'
			 ORDER BY annee DESC";
        $result = mysql_query($clubQuery);
        while ($donnees = mysql_fetch_assoc($result)) {
            $annee = $donnees['annee'];
            $saison = $annee . " - " . ($annee + 1);
            $club = $donnees['club'];
            $idClub = $donnees['idClub'];
            $montant = $donnees['montant'];
            $datePaiement = $donnees['datePaiement'];
            if ($datePaiement != null) {
                $anneePaiement = substr($datePaiement, 0, 4);
                $moisPaiement = substr($datePaiement, 5, 2);
                $jourPaiement = substr($datePaiement, 8, 2);
            } else {
                $anneePaiement = date('Y');
                $moisPaiement = date('m');
                $jourPaiement = date('d');
            }
            $montantPaye = $datePaiement != null;

            //Gestion du changement d'état de paiement
            if (isset($_POST[$annee . ':' . $idClub . ':paye'])) {
                $nouveauMontantPaye = $_POST[$annee . ':' . $idClub . ':paye'] == 1;
                $nouveauAnneePaiement = $_POST[$annee . ':' . $idClub . ':annee'];
                $nouveauMoisPaiement = $_POST[$annee . ':' . $idClub . ':mois'];
                $nouveauJourPaiement = $_POST[$annee . ':' . $idClub . ':jour'];
                if (($nouveauMontantPaye && !$montantPaye) ||
                    $nouveauAnneePaiement != $anneePaiement ||
                    $nouveauMoisPaiement != $moisPaiement ||
                    $nouveauJourPaiement != $jourPaiement
                ) { //Non-payé -> payé OU changement de date
                    if (checkdate($nouveauMoisPaiement, $nouveauJourPaiement, $nouveauAnneePaiement)) {
                        $requetePaye = "UPDATE Cotisations_Clubs SET datePaiement='" . $nouveauAnneePaiement . "-" . $nouveauMoisPaiement . "-" . $nouveauJourPaiement . "' WHERE annee='" . $annee . "' AND idClub=" . $idClub;
                        mysql_query($requetePaye);
                        $montantPaye = true;
                        $anneePaiement = $nouveauAnneePaiement;
                        $moisPaiement = $nouveauMoisPaiement;
                        $jourPaiement = $nouveauJourPaiement;
                    }
                } elseif (!$nouveauMontantPaye && $montantPaye) { //Payé -> non-payé
                    $requeteNonPaye = "UPDATE Cotisations_Clubs SET datePaiement=NULL WHERE annee='" . $annee . "' AND idClub=" . $idClub;
                    mysql_query($requeteNonPaye);
                    $montantPaye = false;
                }
            }
            ?>
            <tr class="<?php echo $montantPaye ? "paye" : "nonPaye"; ?>">
                <td><?php echo $saison; ?></td>
                <td><?php echo $club; ?></td>
                <td><?php echo $montant . " CHF"; ?></td>
                <td>
                    <select name="<?php echo $annee; ?>:<?php echo $idClub; ?>:paye">
                        <option value="1" <?php echo $montantPaye ? "selected='selected'" : ""; ?>>Payé</option>
                        <option value="0" <?php echo $montantPaye ? "" : "selected='selected'"; ?>>Non-payé</option>
                    </select>
                    <label> le </label>
                    <select name="<?php echo $annee; ?>:<?php echo $idClub; ?>:jour">
                        <?php echo modif_liste_jour($jourPaiement); ?>
                    </select>
                    <select name="<?php echo $annee; ?>:<?php echo $idClub; ?>:mois">
                        <?php echo modif_liste_mois($moisPaiement); ?>
                    </select>
                    <select name="<?php echo $annee; ?>:<?php echo $idClub; ?>:annee">
                        <?php echo modif_liste_annee(-1, 0, $anneePaiement); ?>
                    </select>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <input type="submit" value="Valider"/>
</form>
<script>
    $(function () {
        // Ici, le DOM est entièrement défini
    });
</script>
