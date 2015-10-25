<?php

if (isset($_POST['season']) and is_numeric($_POST['season'])) {
    $season = $_POST['season'];
} else {
    if (date('m') >= 9) {
        $season = date('Y');
    } else {
        $season = date('Y') - 1;
    }
}

$seasonEnd = $season + 1;
echo '<form name="seasonSelector" action="" method="post">';
echo '<h2>'.VAR_LANG_SAISON;
echo ' <select name="season" onChange="seasonSelector.submit();">';
$selectorStart = date('Y') - 3;
$selectorEnd = date('Y');
for ($s = $selectorStart; $s <= $selectorEnd; ++$s) {
    $sEnd = $s + 1;
    if ($s == $season) {
        $selected = ' selected="selected"';
    } else {
        $selected = '';
    }
    echo '<option value="'.$s.'"'.$selected.'>';
    echo $s.' - '.$sEnd;
    echo '</option>';
}
echo '</select>';
echo '</h2>';
echo '</form>';

$categoriesQuery = "SELECT c.idCategorie,
                           c.categorie{$_SESSION['__langue__']} AS nomCategorie,
                           d.montantDefraiementArbitre
                    FROM Championnat_Categories c, Championnat_Defraiements d
                    WHERE c.idCategorie = d.idCategorie
                      AND d.saison = $season";
$categories = array();
if ($result = mysql_query($categoriesQuery)) {
    while ($category = mysql_fetch_assoc($result)) {
        $cid = $category['idCategorie'];
        $categories[$cid]['name'] = $category['nomCategorie'];
        $categories[$cid]['defrayalAmount'] = $category['montantDefraiementArbitre'];
    }
} else {
    printErrorMessage("Problème lors de la récupération de la liste des catégories");
}

// TODO Add the possibility to select a specific timeframe.
//      That is going to be useful for the treasurer as she has to pay the referees twice a season.
//      STOP ! I have another idea. It would maybe be better that the treasurer enter what she has already
//             paid to the referee. We would therefore need a table to store that (Arbitres_Defraiements).

$matchesByRefereeQuery =
    "SELECT COUNT(*) AS nbPeriodes, cp.idMatch, cp.idTypePeriode, cp.idArbitre, cm.idCategorie,
            p.nom, p.prenom, p.idArbitre AS niveauArbitre
     FROM ((SELECT noPeriode, idMatch, idArbitreA AS idArbitre, idTypePeriode
            FROM Championnat_Periodes
            WHERE !ISNULL(idArbitreA)
           )
           UNION
           (SELECT noPeriode, idMatch, idArbitreB AS idArbitre, idTypePeriode
            FROM Championnat_Periodes
            WHERE !ISNULL(idArbitreB)
           )
           UNION
           (SELECT noPeriode, idMatch, idArbitreC AS idArbitre, idTypePeriode
            FROM Championnat_Periodes
            WHERE !ISNULL(idArbitreC)
           )
          ) cp, Championnat_Matchs cm, DBDPersonne p
     WHERE cp.idMatch = cm.idMatch
       AND cp.idArbitre = p.idDbdPersonne
       AND cm.saison = $season
     GROUP BY cp.idMatch, cp.idArbitre, cp.idTypePeriode
     ORDER BY cp.idArbitre, cm.idCategorie, cp.idMatch";
$matchesByReferee = array();
if ($matchesByRefereeResult = mysql_query($matchesByRefereeQuery)) {
    $previousRefereeID = 0;
    while ($refereeMatches = mysql_fetch_assoc($matchesByRefereeResult)) {
        $refereeID = $refereeMatches['idArbitre'];
        $categoryID = $refereeMatches['idCategorie'];
        $periodType = $refereeMatches['idTypePeriode'];
        $nbPeriods = $refereeMatches['nbPeriodes'];
        if ($refereeID != $previousRefereeID) {
            $matchesByReferee[$refereeID]['firstname'] = $refereeMatches['prenom'];
            $matchesByReferee[$refereeID]['lastname'] = $refereeMatches['nom'];
            $matchesByReferee[$refereeID]['level'] = $refereeMatches['niveauArbitre'] - 1;
        }
        if ($periodType == 4 || $periodType == 8 || ($periodType == 7 && $nbPeriods >= 2) || $nbPeriods >= 3) {
            // Conditions for the match to be counted are fullfiled
            $matchesByReferee[$refereeID]['matchesByCategory'][$categoryID]++;
        }
    }

    // Getting information about the payments
    $paymentsByRefereeQuery = "SELECT idArbitre, montantTotalPaye
                               FROM Arbitres_VersementsTotauxParArbitreParSaison
                               WHERE saison = $season";

    if ($paymentsByRefereeResult = mysql_query($paymentsByRefereeQuery)) {
        while ($referee = mysql_fetch_assoc($paymentsByRefereeResult)) {
            $matchesByReferee[$referee['idArbitre']]['totalAmountPaid'] = $referee['montantTotalPaye'];
        }
    } else {
        printErrorMessage("Problème lors de la récupération des informations sur les versements");
    }
} else {
    printErrorMessage("Problème lors de la récupération de la liste des arbitres");
}

echo '<table id="refereeDefrayal" class="adminTable alternateBackground">';
echo '<tr>';
echo '<th>' . ucfirst(VAR_LANG_ARBITRE) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_NIVEAU) . '</th>';
echo '<th>Nombre de matchs arbitrés</th>';
echo '<th>Défraiement</th>';
if ($season > 2014) {
    // We hide season before 2015-2016 as payments were not tracked here.
    echo '<th>Payé</th>';
    echo '<th>Reste à payer</th>';
}
echo '</tr>';
$totalDefrayalAmounts = array();
foreach ($matchesByReferee as $refereeID => $referee) {
    echo '<tr id="referee-' . $refereeID . '">';
    echo '<td>' . $referee['firstname'] . ' ' . $referee['lastname'] . '</td>';
    echo '<td>' . chif_rome($referee['level']) . '</td>';
    echo '<td class="nbMatches">';
    $refereeDefrayalAmount = 0;
    foreach ($referee['matchesByCategory'] as $category => $nbMatchs) {
        echo '<span>' . $categories[$category]['name'] . ' : ' . $nbMatchs . '</span>';

        $refereeDefrayalAmount += $nbMatchs * $categories[$category]['defrayalAmount'];
        $totalDefrayalAmounts[$category] += $nbMatchs * $categories[$category]['defrayalAmount'];
    }
    if ($refereeDefrayalAmount == 0) {
        echo "Pas de matchs entiers";
    }
    echo '</td>';
    echo '<td>CHF ' . $refereeDefrayalAmount . '</td>';
    if ($season > 2014) {
        // We hide season before 2015-2016 as payments were not tracked here.
        $totalAmountPaid = $referee['totalAmountPaid'] == '' ? 0 : $referee['totalAmountPaid'];
        echo '<td>CHF ' . $totalAmountPaid . '</td>';
        $leftToPay = $refereeDefrayalAmount - $totalAmountPaid;
        if ($leftToPay > 0) {
            $leftToPayClass = 'notOK';
        } else {
            $leftToPayClass = '';
        }
        echo '<td class="' . $leftToPayClass . '">CHF ' . $leftToPay;
        if ($leftToPay > 0) {
            echo '<a href="?menuselection=' . $_GET['menuselection'] . '&smenuselection=' . $_GET['smenuselection'];
            echo '&ajouter&saison=' . $season . '&id=' . $refereeID . '&montant=' . $leftToPay . '">';
            echo ' <img src="admin/images/ajouter.png" alt="Ajouter un versement" height="14px" /></a>';
        }
        echo '</td>';
    }
}
echo '</table>';

$finalTotal = 0;
echo '<h3>Totaux</h3>';
echo '<p>';
foreach ($totalDefrayalAmounts as $category => $amount) {
    echo $categories[$category]['name'] . ' : CHF ' . $amount . '<br />';
    $finalTotal += $amount;
}
echo 'Total : CHF ' . $finalTotal;
echo '</p>';
