<?php
session_start();

require_once("../config.php");
mysql_set_charset('utf8');

require_once("../includes/date.inc.php");

if (!$_SESSION["__gestionMembresClub__"]) {
    header($_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
    include("../http_status_pages/401-authorization_required.html");
    exit;
}

if (!isset($_GET['annee']) || !is_numeric($_GET['annee'])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    include("../http_status_pages/400-bad_request.html");
    exit;
}

$annee = $_GET['annee'];

$queryClub = "SELECT c.club, c.nomComplet, cc.montant, cc.datePaiement, cc.nbMembresActifs, cc.nbMembresJuniors, cc.nbMembresSoutiens, cc.nbMembresPassifs, cc.nbMembresVIP
			  FROM ClubsFstb c, Cotisations_Clubs cc
			  WHERE c.id = " . $_SESSION['__idClub__'] . "
			  AND c.nbIdClub = cc.idClub
			  AND cc.annee = " . $annee . "
			  LIMIT 1";
if (!$dataClub = mysql_query($queryClub)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    include("../http_status_pages/500-internal_server_error.html");
    exit;
}
$club = mysql_fetch_assoc($dataClub);

$clubName_short = $club['club'];
$clubName_full = $club['nomComplet'];
$amount = $club['montant'];
$paymentDate = $club['datePaiement'];

$nbMembresActifs = $club['nbMembresActifs'];
$nbMembresJuniors = $club['nbMembresJuniors'];
$nbMembresSoutiens = $club['nbMembresSoutiens'];
$nbMembresPassifs = $club['nbMembresPassifs'];
$nbMembresVIP = $club['nbMembresVIP'];

$nbMembresActifsJuniors = $nbMembresActifs + $nbMembresJuniors;
//$nbMembresTotal = $nbMembresActifs + $nbMembresJuniors + $nbMembresSoutiens + $nbMembresPassifs + $nbMembresVIP;

$nbMembresPourUnAbonnementVIPOffert = 20;

$cotisationMembreActif = 25;
$cotisationMembreJunior = 15;
$cotisationMembreSoutien = 10;
$cotisationMembrePassif = 0;
$cotisationMembreVIP = 10;

$nbAbonnementVIPOffertsMax = floor($nbMembresActifsJuniors / $nbMembresPourUnAbonnementVIPOffert + 1);
$nbAbonnementVIPOfferts = min($nbAbonnementVIPOffertsMax, $nbMembresVIP);

$totalMembresActifs = $nbMembresActifs * $cotisationMembreActif;
$totalMembresJuniors = $nbMembresJuniors * $cotisationMembreJunior;
$totalMembresSoutiens = $nbMembresSoutiens * $cotisationMembreSoutien;
$totalMembresPassifs = $nbMembresPassifs * $cotisationMembrePassif;
$totalMembresVIP = $nbMembresVIP * $cotisationMembreVIP;
$reductionVIP = $nbAbonnementVIPOfferts * (-$cotisationMembreVIP);
$computedAmount = $totalMembresActifs + $totalMembresJuniors + $totalMembresSoutiens + $totalMembresPassifs + $totalMembresVIP + $reductionVIP;

if ($paymentDate == null || $computedAmount != $amount) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    include("../http_status_pages/400-bad_request.html");
    exit;
}

$department = "Finances";
$filename = "quittance_cotisation_" . $annee;
$title = "Quittance cotisation " . $clubName_short . " " . $annee;
$subject = "Quittance de la cotisation de \"" . $clubName_full . "\" pour l'année " . $annee;
$keywords = "Swiss Tchoukball, tchoukball, cotisation, quittance, " . $clubName_full . ", " . $annee;

$content = "<h2>Quittance</h2>";
$content .= "<h3>Cotisation " . $annee . " - " . $clubName_full . "</h3>";
$content .= "<table>" .
    "<tr><th>Statut</th><th>Par membre</th><th>Nombre</th><th>Montant</th></tr>" .
    "<tr><td>Membres actifs</td><td>CHF " . $cotisationMembreActif . "</td><td>" . $nbMembresActifs . "</td><td>CHF " . $totalMembresActifs . "</td></tr>" .
    "<tr><td>Membres juniors</td><td>CHF " . $cotisationMembreJunior . "</td><td>" . $nbMembresJuniors . "</td><td>CHF " . $totalMembresJuniors . "</td></tr>" .
    "<tr><td>Membres soutiens</td><td>CHF " . $cotisationMembreSoutien . "</td><td>" . $nbMembresSoutiens . "</td><td>CHF " . $totalMembresSoutiens . "</td></tr>" .
    "<tr><td>Membres passifs</td><td>CHF " . $cotisationMembrePassif . "</td><td>" . $nbMembresPassifs . "</td><td>CHF " . $totalMembresPassifs . "</td></tr>" .
    "<tr><td>Membres VIP</td><td>CHF " . $cotisationMembreVIP . "</td><td>" . $nbMembresVIP . "</td><td>CHF " . $totalMembresVIP . "</td></tr>" .
    "<tr><td>Membres VIP offerts</td><td>CHF -" . $cotisationMembreVIP . "</td><td>" . $nbAbonnementVIPOfferts . "</td><td>CHF " . $reductionVIP . "</td></tr>" .
    "<tr><th colspan=\"3\">Total</th><th>CHF " . $amount . "</th></tr>" .
    "</table>";
$content .= "<p>Payé le " . date_sql2date($paymentDate) . ".</p>";

include("generate_pdf.php");

?>