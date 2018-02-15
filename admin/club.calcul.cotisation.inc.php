<div id="calculCotisation"><?php
    statInsererPageSurf(__FILE__);

    $idActifs = 3;
    $idJuniors = 6;
    $idSoutiens = 5;
    $idPassifs = 4;
    $idVIP = 23;
    $idStatutsACompter = array($idActifs, $idJuniors, $idSoutiens, $idPassifs, $idVIP);
    $clubRequestPart_nbMembresParStatut = "";
    $statutsRequestPart_WHERE = "WHERE ";
    foreach ($idStatutsACompter as $id) {
        $clubRequestPart_nbMembresParStatut .= "COUNT(if(p.idStatus=" . $id . ",1,NULL)) AS `nbMembresParStatut[" . $id . "]`,";
        $statutsRequestPart_WHERE .= " idStatus=" . $id . " OR";
    }
    $statutsRequestPart_WHERE = substr($statutsRequestPart_WHERE, 0, -3); // Removing the "OR" left at the end.

    $nbMembresPourUnAbonnementVIPOffert = 20;

    if ($_SESSION['__userLevel__'] > 5 || !isset($_POST['club'])) {
        $clubRequestPart_WHERE = "c.nbIdClub = " . $_SESSION['__nbIdClub__'];
    } else {
        $clubRequestPart_WHERE = "c.nbIdClub = " . $_POST['club'];
    }

    /* CLUB REQUEST */
    $clubRequest = "SELECT
						c.nbIdClub AS idClub,
						club AS clubName,
						$clubRequestPart_nbMembresParStatut
						c.statusId,
						cs.fixedFeeAmount
					FROM clubs_status cs, clubs c
					LEFT OUTER JOIN DBDPersonne p
						ON p.idClub = c.nbIdClub
					WHERE " . $clubRequestPart_WHERE . "
						AND (c.statusId = 1 OR c.statusId = 2)
                    	AND cs.id = c.statusId
					GROUP BY p.idClub";
    //echo $clubRequest;

    $clubResult = mysql_query($clubRequest) or die ("<p class='notification notification--notification notification--error'>Mauvaise requête</p>");
    $clubData = mysql_fetch_array($clubResult);

    $clubId = $clubData['idClub'];
    $clubName = $clubData['clubName'];
    $clubStatusId = $clubData['statusId'];
    $montantCotisationFixe = $clubData['fixedFeeAmount'];
    if (mysql_num_rows($clubResult) == 0) {
        $clubId = 15;
    }

    //Donner l'autorisation de changer de club si l'utilisateur est membre du comité.
    if ($_SESSION['__userLevel__'] <= 5) {
        if (isset($_POST['club'])) {
            $clubId = $_POST['club'];
        }

        ?>
        <form name="clubSwitcher" method="post"
              action="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>">
            <select name="club" title="Choisir un club" onChange="document.clubSwitcher.submit();">
                <option value="15">Choisir un club</option>
                <?php
                $clubsRequest = "SELECT nbIdClub, club FROM clubs WHERE statusId = 1 OR statusId = 2 ORDER BY club";
                $clubsResult = mysql_query($clubsRequest);
                while ($clubsData = mysql_fetch_assoc($clubsResult)) {
                    $selected = "";
                    if ($clubsData['nbIdClub'] == $clubId) {
                        $selected = " selected='selected'";
                        $clubName = $clubsData['club'];
                    }
                    ?>
                    <option
                        value="<?php echo $clubsData['nbIdClub']; ?>"<?php echo $selected; ?>><?php echo $clubsData['club']; ?></option>
                    <?php
                }
                ?>
            </select>
        </form>
        <?php
        $isManager = true;
    } else {
        if ($_SESSION["__gestionMembresClub__"] == 1) {
            $isManager = true;
        } else {
            $isManager = false;
        }
    }
    if ($_SESSION["__nbIdClub__"] == 15 AND $_SESSION['__userLevel__'] > 5) {
        echo "<p class='notification'>Aucun club n'est associé à votre compte.</p>";
    } else if (!$isManager) {
        echo "<p class='notification'>Vous n'êtes pas reconnu en tant que gestionnaire des membres de votre club. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a> si vous l'êtes.</p>";
    } else {
        echo "<h4>" . $clubName . "</h4>";

        if ($montantCotisationFixe != 0) {
            echo "<p>Montant de cotisation fixe : CHF " . $montantCotisationFixe . "</p>";
        }

        // On affiche le tableau si le club n'est pas adhérent passif
        if ($clubStatusId != 2) {
            ?>
            <table class="st-table st-table--spaced">
                <thead>
                <tr>
                    <th></th>
                    <th>Par membre</th>
                    <th>Nombre</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php

                /* STATUTS REQUEST */
                $statutsRequest =
                    "SELECT idStatus, descriptionStatus" . $_SESSION['__langue__'] . " AS name, cotisation
			 FROM DBDStatus " . $statutsRequestPart_WHERE . "
			 ORDER BY name";
                //echo $statutsRequest;
                $statutsResult = mysql_query($statutsRequest);


                $nbMembres = array();
                $montantCotisation = 0;
                $nbMembresTotal = 0;
                while ($statutsData = mysql_fetch_assoc($statutsResult)) {
                    $id = $statutsData['idStatus'];
                    $nomStatuts = $statutsData['name'];
                    $cotisationStatuts = $statutsData['cotisation'];
                    $nbMembres[$id] = $clubData["nbMembresParStatut[$id]"];
                    $nbMembresTotal += $nbMembres[$id];

                    $cotisationTotaleParStatut = $cotisationStatuts * $nbMembres[$id];
                    $montantCotisation += $cotisationTotaleParStatut;

                    switch ($id) {
                        case $idActifs:
                            $nbMembresActifs = $nbMembres[$id];
                            break;
                        case $idJuniors:
                            $nbMembresJuniors = $nbMembres[$id];
                            break;
                        case $idSoutiens:
                            $nbMembresSoutiens = $nbMembres[$id];
                            break;
                        case $idPassifs:
                            $nbMembresPassifs = $nbMembres[$id];
                            break;
                        case $idVIP:
                            $nbMembresVIP = $nbMembres[$id];
                            break;
                    }
                    ?>
                    <tr>
                        <td><?php echo $nomStatuts; ?></td>
                        <td>CHF <?php echo $cotisationStatuts; ?></td>
                        <td><?php echo $nbMembres[$id]; ?></td>
                        <td>CHF <?php echo $cotisationTotaleParStatut; ?></td>
                    </tr>
                    <?php
                    if ($id == $idVIP) {
                        // Attention, il faut que les membres actifs à compter le soient avant le calcul pour les membres VIPs. Or cela n'est pas vérifié et si ce n'est pas le cas, le calcul sera faux.
                        $nbAbonnementVIPOffertsMax = floor(($nbMembres[$idActifs] + $nbMembres[$idJuniors]) / $nbMembresPourUnAbonnementVIPOffert + 1);
                        $nbAbonnementVIPOfferts = min($nbAbonnementVIPOffertsMax, $nbMembres[$id]);
                        $reductionVIP = $nbAbonnementVIPOfferts * (-$cotisationStatuts);
                        $montantCotisation += $reductionVIP;
                        ?>
                        <tr>
                            <td>Abonnements VIP offerts</td>
                            <td>CHF -<?php echo $cotisationStatuts; ?></td>
                            <td><?php echo $nbAbonnementVIPOfferts ?></td>
                            <td>CHF <?php echo $reductionVIP; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>CHF <?php echo $montantCotisation; ?></td>
                </tr>
                </tfoot>
            </table>
            <p>Total de <?php echo $nbMembresTotal; ?> membres</p>
            <br/>
            <?php
        }

        if ($isManager) {
            //Affichage de l'état actuel du paiement de la cotisation et de la possibilité de supprimer des membres
            $anneePassee = date('Y') - 1;

            $requeteEtatCotisation =
                "SELECT c.annee, cc.montant, cc.datePaiement, ccLastYear.datePaiement AS datePaiementAnneePassee,
					    c.etatMembresAu, c.delaiSupprimerMembres, c.delaiPayer
				 FROM Cotisations c
				 LEFT OUTER JOIN Cotisations_Clubs ccLastYear
				 	ON ccLastYear.annee = c.annee - 1
				 	AND ccLastYear.idClub = " . $clubId . "
				 LEFT OUTER JOIN Cotisations_Clubs cc
				 	ON cc.annee = c.annee
				 	AND cc.idClub = ccLastYear.idClub
				 WHERE c.annee >= '" . $anneePassee . "'
				 	AND c.annee < '" . date('Y') . "'
				 ORDER BY c.annee DESC";
            //echo $requeteEtatCotisation;

            $retourEtatCotisation = mysql_query($requeteEtatCotisation);
            while ($etatCotisation = mysql_fetch_assoc($retourEtatCotisation)) {
                if ($etatCotisation['annee'] < $anneePassee && $etatCotisation['datePaiement'] != null) {
                    //Ne rien afficher si l'année passée est en ordre.
                } else {
                    $saisonCotisationAnneeDebut = $etatCotisation['annee'];
                    $saisonCotisationAnneeFin = $etatCotisation['annee'] + 1;
                    echo "<h4>Cotisation " . $saisonCotisationAnneeDebut . "-" . $saisonCotisationAnneeFin . "</h4>";

                    $today = date('Y-m-d');

                    // Si on est dans les délais pour supprimer des membres et que le club n'est pas adhérent passif
                    if ($today < $etatCotisation['delaiSupprimerMembres'] && $clubStatusId != 2) {
                        if (!$etatCotisation['datePaiementAnneePassee']) {
                            printMessage("Votre club doit s'acquitter du montant de la cotisation de la saison passée afin que vous puissiez supprimer des membres.");
                        } else {
                            printMessage("Vous avez jusqu'" . date_sql2date_joli($etatCotisation['delaiSupprimerMembres'],
                                    "au",
                                    $_SESSION['__langue__']) . " pour supprimer les membres qui ne feront pas partie du club durant la saison.");
                        }
                    }

                    if ($today < $etatCotisation['etatMembresAu']) {
                        printMessage(
                            "Le calcul du montant de la cotisation sera effectué " . date_sql2date_joli($etatCotisation['etatMembresAu'],
                                "le", $_SESSION['__langue__']) . " à 00h00.<br>" .
                            "Vous aurez ensuite jusqu'" . date_sql2date_joli($etatCotisation['delaiPayer'], "au",
                                $_SESSION['__langue__']) . " pour vous acquitter de ce montant."
                        );
                    } else {
                        if (!$etatCotisation['datePaiement']) {
                            $unpaidFeeMessage = "Montant de " . $etatCotisation['montant'] . " CHF <strong>non-payé</strong>.<br />" .
                                "Votre club a jusqu'" . date_sql2date_joli($etatCotisation['delaiPayer'], "au",
                                    $_SESSION['__langue__']) . " pour s'en acquitter.";

                            if ($today < $etatCotisation['delaiPayer']) {
                                printMessage($unpaidFeeMessage);
                            } else {
                                printErrorMessage($unpaidFeeMessage);
                            }
                        } else {
                            printSuccessMessage(
                                "Montant de " . $etatCotisation['montant'] . " CHF <strong>payé</strong> le " . date_sql2date($etatCotisation['datePaiement']) . ". " .
                                "<a href='/pdf_generator/quittance_cotisation_club.php?annee=" . $saisonCotisationAnneeDebut . "'>Télécharger la quittance</a>"
                            );
                        }
                    }
                    echo "<br />";
                }
            }
        }
        ?>
        <h3>CCP <?php echo VAR_LANG_ASSOCIATION_NAME; ?></h3>
        <div class='ccp-number'>20-8957-2</div>
        <?php
    }

    ?>
</div>
