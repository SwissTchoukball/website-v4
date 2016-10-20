<?php
if (hasRefereeManagementAccess()) {


	$orderRefereesByLevel = true;
	$referees = getReferees($orderRefereesByLevel);

	if (date('m') >= 8) {
		$currentSeason = date('Y');
	} else {
		$currentSeason = date('Y') - 1;
	}

	if (isset($_GET['ajouter'])) {
		$action = 'add';
		$titleAction = 'Ajout';
		$submitAction = 'Ajouter';
		$refereeID = null;
		$seasonID = $currentSeason;
		$pointsTypeID = 4;
		$points = null;
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		$description = null;
	} elseif (isset($_GET['modifier']) && is_numeric($_GET['modifier'])) {
		$action = 'edit';
		$titleAction = 'Édition';
		$submitAction = 'Modifier';
		$pointsID = $_GET['modifier'];

		$queryPoints = "SELECT ap.idArbitre AS personId, ap.idSaison, ap.idTypePoints, ap.points, ap.date, ap.description,
						p1.nom AS nomCreateur, p1.prenom AS prenomCreateur, p2.nom AS nomEditeur, p2.prenom AS prenomEditeur
						FROM Arbitres_Points ap
						LEFT OUTER JOIN Personne p1 ON p1.id = ap.creator
						LEFT OUTER JOIN Personne p2 ON p2.id = ap.lastEditor
						LEFT OUTER JOIN Arbitres_Types_Points atp ON atp.id = ap.idTypePoints
						WHERE ap.id = " . $pointsID . " AND atp.ajoutManuel = 1";
		if ($dataPoints = mysql_query($queryPoints)) {
			$p = mysql_fetch_assoc($dataPoints);
			if (mysql_num_rows($dataPoints) < 1) {
				die(printErrorMessage("Données introuvables"));
			}
			$refereeID = $p['personId'];
			$seasonID = $p['idSaison'];
			$pointsTypeID = $p['idTypePoints'];
			$points = $p['points'];
			$date = $p['date'];
			$year = annee($date);
			$month = mois($date);
			$day = jour($date);
			$description = $p['description'];
			$creatorName = $p['prenomCreateur'] . ' ' .$p['nomCreateur'];
			$lastEditorName = $p['prenomEditeur'] . ' ' .$p['nomEditeur'];
		} else {
			die(printErrorMessage("Problème lors de la récupération des données"));
		}
	} else {
		die(printErrorMessage("Action indéfinie"));
	}
	?>

	<script>
	$(function() {
		$.datepicker.setDefaults($.datepicker.regional[ "<?php echo strtolower($_SESSION['__langue__']); ?>" ]);
		$("#datepicker").datepicker({
		    dateFormat: 'dd.mm.yy',
		    onClose: function(dateText, inst) {
				$('#description').focus();
			}
		});
		$("#datepicker").datepicker('setDate', '<?php echo $day . '.' . $month . '.' . $year; ?>');
	});
	</script>

	<?php
	echo '<h2>' . $titleAction . ' de points d\'arbitres</h2>';

	echo '<form name="editRefereePoints" action="?menuselection=' . $_GET['menuselection'] . '&smenuselection=' . $_GET['smenuselection'] . '&gerer" method="post" class="adminForm">';

	echo '<label for="refereeID">' . ucfirst(VAR_LANG_ARBITRE) . '</label>';
	echo '<select name="refereeID" id="refereeID">';
	printRefereesOptionsList($referees, $refereeID);
	echo '</select>';

	echo '<label for="seasonID">' . VAR_LANG_SAISON . '</label>';
	echo '<select name="seasonID" id="seasonID">';
	printSeasonsOptionsForSelect(1970, date('Y'), $seasonID);
	echo '</select>';

	echo '<label for="pointsTypeID">' . ucfirst(VAR_LANG_TYPE_POINTS) . '</label>';
	printManualRefereePointsTypesSelect($pointsTypeID, 'pointsTypeID');

	echo '<label for="points">' . ucfirst(VAR_LANG_POINTS) . '</label>';
	echo '<input type="number" name="points" id="points" min="1" value="' . $points . '" />';

	echo '<label for="datepicker">' . VAR_LANG_DATE . '</label>';
	echo '<input type="text" name="date" id="datepicker" value="' . $date . '" />';

	echo '<label for="description">' . VAR_LANG_DESCRIPTION . '</label>';
	echo '<textarea name="description" id="description">' . $description . '</textarea>';

	echo '<input type="hidden" name="pointsID" value="' . $pointsID . '" />';
	echo '<input type="submit" name="' . $action . '" value="' . $submitAction . '" />';
	echo '</form>';


	if ($action == 'edit') {
		echo '<a href="?menuselection=' . $_GET['menuselection'] . '&smenuselection=' . $_GET['smenuselection'] . '&delete=' . $pointsID . '"  onclick="return confirm(\'Voulez-vous vraiment supprimer ces points ?\')">Supprimer ces points</a>';
		echo '<p>Ajouté par ' . $creatorName . '. Dernière modification par ' . $lastEditorName . '</p>';
	}

} else {
	printErrorMessage("Vous n'avez pas accès à la gestion des arbitres.");
}
?>