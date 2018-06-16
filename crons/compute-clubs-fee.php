<?php
include('../config.php');

date_default_timezone_set('Europe/Zurich');

$idActifs = 3;
$idJuniors = 6;
$idSoutiens = 5;
$idPassifs = 4;
$idVIP = 23;

$nbMembersToGetAFreeVIPSubscription = 20;

$idStatutsACompter = array($idActifs, $idJuniors, $idSoutiens, $idPassifs, $idVIP);
$clubsRequestPart_nbMembresParStatut = "";
$statutsRequestPart_WHERE = "WHERE ";
foreach ($idStatutsACompter as $id) {
    $clubsRequestPart_nbMembresParStatut .= "COUNT(if(p.idStatus=" . $id . ",1,NULL)) AS `nbMembresParStatut[" . $id . "]`, ";
    $statutsRequestPart_WHERE .= " idStatus=" . $id . " OR";
}
$statutsRequestPart_WHERE = substr($statutsRequestPart_WHERE, 0, -3); // Removing the "OR" left at the end.

$nbMembresPourUnAbonnementVIPOffert = 20;

/* CLUBS REQUEST */
$clubsRequest = "SELECT
                    c.nbIdClub AS idClub,
                    c.club AS name,
                    $clubsRequestPart_nbMembresParStatut
                    c.statusId,
                    cs.fixedFeeAmount
                 FROM clubs_status cs, clubs c
                 LEFT OUTER JOIN DBDPersonne p
                    ON p.idClub = c.nbIdClub
                 WHERE (c.statusId = 1 OR c.statusId = 2)
                    AND cs.id = c.statusId
                 GROUP BY p.idClub";
$clubsResult = mysql_query($clubsRequest) or die (mysql_error());

/* STATUTS REQUEST */
$statutsRequest =
    "SELECT idStatus, cotisation
     FROM DBDStatus " .
    $statutsRequestPart_WHERE;

$statutsResult = mysql_query($statutsRequest);

$feeAmountByStatus = array();
while ($statusData = mysql_fetch_assoc($statutsResult)) {
    $id = $statusData['idStatus'];
    $feeAmountByStatus[$id] = $statusData['cotisation'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Clubs fee computation</title>
    <meta charset="iso-8859-1">
</head>
<body>
<h1>Computing the annual fee for each club</h1>
<?php
while ($club = mysql_fetch_assoc($clubsResult)) {
    $totalFee = 0;
    $VIPFeeAmount = 0;
    if ($club['statusId'] == 1) {
        foreach ($feeAmountByStatus as $statusId => $feeAmount) {
            $totalFee += $feeAmount * $club['nbMembresParStatut' . "[$statusId]"];
            if ($statusId == $idVIP) {
                $VIPFeeAmount = $feeAmount;
            }
        }
    } else {
        $totalFee += $club['fixedFeeAmount'];
    }
    // Computing the number of offered VIP subscriptions and reducing the fee accordingly.
    $maxNbOfferedVIPSubscriptions = floor(($club['nbMembresParStatut' . "[$idActifs]"] + $club['nbMembresParStatut' . "[$idJuniors]"]) / $nbMembersToGetAFreeVIPSubscription + 1);
    $nbOfferedVIPSubscriptions = min($maxNbOfferedVIPSubscriptions, $club['nbMembresParStatut' . "[$idVIP]"]);
    $VIPReduction = $nbOfferedVIPSubscriptions * (-$VIPFeeAmount);
    $totalFee += $VIPReduction;

//    echo $club['name'] . ': ' . $totalFee . '<br>';

    $saveClubFeeQuery =
        "INSERT INTO Cotisations_Clubs (
            annee,
            idClub,
            montant,
            nbMembresActifs,
            montantMembresActifs,
            nbMembresJuniors,
            montantMembresJuniors,
            nbMembresSoutiens,
            montantMembresSoutiens,
            nbMembresPassifs,
            montantMembresPassifs,
            nbMembresVIP,
            nbMembresVIPOfferts,
            montantMembresVIP
         )
         VALUES (
            '" . (date('Y') - 1) . "',
            " . $club['idClub'] . ",
            " . $totalFee . ",
            " . $club['nbMembresParStatut[3]'] . ",
            " . $feeAmountByStatus[3] . ",
            " . $club['nbMembresParStatut[6]'] . ",
            " . $feeAmountByStatus[6] . ",
            " . $club['nbMembresParStatut[5]'] . ",
            " . $feeAmountByStatus[5] . ",
            " . $club['nbMembresParStatut[4]'] . ",
            " . $feeAmountByStatus[4] . ",
            " . $club['nbMembresParStatut[23]'] . ",
            " . $nbOfferedVIPSubscriptions . ",
            " . $feeAmountByStatus[23] . "
         )";

    echo $saveClubFeeQuery;

    if (mysql_query($saveClubFeeQuery)) {
        echo "<p><strong>" . $club['name'] . "</strong> Fee: CHF " . $totalFee . "</p>";
    } else {
        echo "<p>Error while saving the fee for <strong>" . $club['name'] . "</strong>.</p>";
        echo "<pre>" . mysql_error() . "</pre>";
    }
}

mysql_close();
?>
</body>
</html>