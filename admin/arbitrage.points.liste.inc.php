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

$refereeLevels = array();
$queryRefereeLevels = "SELECT idArbitre AS id, descriptionArbitre" . $_SESSION['__langue__']. " AS niveau, pointsPourGarderNiveau FROM DBDArbitre";
if ($dataRefereeLevels = mysql_query($queryRefereeLevels)) {
	while ($refereeLevel = mysql_fetch_assoc($dataRefereeLevels)) {
		$refereeLevels[$refereeLevel['id']] = $refereeLevel;
	}
} else {
	die(printErrorMessage("Problème lors de la récupération des niveaux d'arbitre."));
}
echo '<form name="seasonSelector" action="" method="post">';
echo '<h2>' . VAR_LANG_SAISON;
echo ' <select name="season" onChange="seasonSelector.submit();">';
$selectorStart = date('Y') - 3;
$selectorEnd = date('Y');
for ($s = $selectorStart; $s <= $selectorEnd; $s++) {
	$sEnd = $s + 1;
	if ($s == $season) {
		$selected = ' selected="selected"';
	} else {
		$selected = '';
	}
	echo '<option value="' . $s . '"' . $selected . '>';
	echo $s . ' - ' . $sEnd;
	echo '</option>';
}
echo '</select>';
echo '</h2>';
echo '</form>';

echo '<table id="refereePoints" class="adminTable alternateBackground">';
echo '<tr>';
echo '<th>' . ucfirst(VAR_LANG_ARBITRE) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_NIVEAU) . '</th>';
$pointsTypes = array();
// If we change the query ORDER, we have to change it in the second query as well
$queryPointsTypes = "SELECT id, typesPoints" . $_SESSION['__langue__']. " AS nom FROM Arbitres_Types_Points ORDER BY id";
if ($dataPointsTypes = mysql_query($queryPointsTypes)) {
	while ($pointType = mysql_fetch_assoc($dataPointsTypes)) {
		array_push($pointsTypes, $pointType);
	}
} else {
	die(printErrorMessage("Problème lors de la récupération des types de points."));
}
$totalPointsTypes = array();
foreach ($pointsTypes as $pointType) {
	echo '<th>' . $pointType['nom'] . '</th>';
	$totalPointsTypes[$pointType['id']] = 0;
}
echo '<th>Total</th>';
echo '</tr>';

$queryRefereesPoints = "SELECT ap.idArbitre, p.nom, p.prenom, p.idArbitre AS niveauArbitre, atp.id AS idTypesPoints, atp.typesPointsFr,
						(SELECT SUM(apo.points) AS points
						FROM Arbitres_Points apo
						WHERE apo.idArbitre=ap.idArbitre AND apo.idSaison = " . $season . " AND apo.idTypePoints = atp.id) AS points
						FROM Arbitres_Types_Points atp, Arbitres_Points ap
						LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = ap.idArbitre
						WHERE ap.idSaison = " . $season . "
						GROUP BY p.nom, p.prenom, atp.id
						ORDER BY p.nom, p.prenom, atp.id";

if ($refereesPoints = mysql_query($queryRefereesPoints)) {
	$currentRefereeID = 0;
	$currentRefereeLevel = 0;
	$grandTotalPoints = 0;
	while ($referee = mysql_fetch_assoc($refereesPoints)) {
		if ($referee['idArbitre'] != $currentRefereeID) {
			if ($currentRefereeID != 0) { // Not first line
                // Computation of the total number of points of the referee in the previous loop.
				$grandTotalPoints += $totalPoints;
				if ($totalPoints < $refereeLevels[$currentRefereeLevelID]['pointsPourGarderNiveau']) {
					$totalPointsClass = ' class="notOK"';
				} else {
					$totalPointsClass = '';
				}
				echo '<td' . $totalPointsClass . '>' . $totalPoints . '</td>';
				echo '</tr>';
			}
			echo '<tr>';
			echo '<td>' . $referee['prenom'] . ' ' . $referee['nom'] . '</td>';
			$refereeLevel = $referee['niveauArbitre'] - 1;
			echo '<td>' . chif_rome($refereeLevel) . '</td>';
			$currentRefereeID = $referee['idArbitre'];
			$currentRefereeLevelID = $referee['niveauArbitre'];
			$totalPoints = 0;
		}
		if ($referee['points'] == '') {
			$points = 0;
		} else {
			$points = $referee['points'];
		}
		echo '<td>' . $points . '</td>';
		$totalPoints += $points;
		$totalPointsTypes[$referee['idTypesPoints']] += $points;
	}
	$grandTotalPoints += $totalPoints;
	echo '<td' . $totalPointsClass . '>' . $totalPoints . '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th>Totaux</th>';
	echo '<th></th>';
	foreach ($pointsTypes as $pointType) {
		echo '<th>' . $totalPointsTypes[$pointType['id']] . '</th>';
	}
	echo '<th>' . $grandTotalPoints . '</th>';
	echo '</tr>';
} else {
	printErrorMessage("Problème lors de la récupération de la liste des arbitres");
}
echo '</table>';

echo '<h3>Arbitres n\'ayant obtenus aucun points</h3>';
echo '<p class="noPointsReferees">';
$queryNoPointsReferees = "SELECT p.idDbdPersonne, p.nom, p.prenom
						  FROM DBDPersonne p
						  WHERE p.idArbitre > 1
						  AND p.idDbdPersonne NOT IN (SELECT ap.idArbitre
						  							  FROM Arbitres_Points ap
						  							  WHERE ap.idSaison=" . $season . ")
						  ORDER BY p.nom, p.prenom";
if ($dataNoPointsReferees = mysql_query($queryNoPointsReferees)) {
	while ($referee = mysql_fetch_assoc($dataNoPointsReferees)) {
		echo '<span>' . $referee['prenom'] . ' ' . $referee['nom'] . '</span>';
	}
} else {
	die(printErrorMessage("Problème lors de la récupération des arbitres sans points."));
}
echo '</p>';

echo '<h3>Nombre de points pour conserver son titre d\'arbitre</h3>';
echo '<ul>';
foreach ($refereeLevels as $refereeLevel) {
	if ($refereeLevel['id'] > 1) {
		echo '<li>' . $refereeLevel['niveau'] . ' : ' . $refereeLevel['pointsPourGarderNiveau'] . ' ' . VAR_LANG_POINTS . '</li>';
	}
}
echo '</ul>';
?>
