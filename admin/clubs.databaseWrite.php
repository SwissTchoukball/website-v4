<?php

if ($_POST['postType'] == "newClub" || $_POST['postType'] == "editClub") {

	$clubID = $_POST['clubID']; //validity already checked in clubs.inc.php
	$shortName = htmlspecialchars(validiteInsertionTextBd($_POST['shortName']));
	$fullName = htmlspecialchars(validiteInsertionTextBd($_POST['fullName']));
	$nameForSorting = htmlspecialchars(validiteInsertionTextBd($_POST['nameForSorting']));
	$address = htmlspecialchars(validiteInsertionTextBd($_POST['address']));
	if (isValidNPA($_POST['npa'])) {
		$npa = $_POST['npa'];
	} else {
		$npa = 'NULL';
	}
	$city = htmlspecialchars(validiteInsertionTextBd($_POST['city']));
	$cantonID = htmlspecialchars(validiteInsertionTextBd($_POST['Canton']));
	$active = isset($_POST['active']) ? 1 : 0;
	$phone = htmlspecialchars(validiteInsertionTextBd($_POST['phone']));
	$email = htmlspecialchars(validiteInsertionTextBd($_POST['email']));
	$url = htmlspecialchars(validiteInsertionTextBd($_POST['url']));


	if ($nbError > 0) { // Erreur. Si c'�tait un ajout, on veut afficher le formulaire pour nouveau club, sinon on affiche le formulaire de modification du club.
		echo "<p class='error'>Proc�dure annul�e.</p>";
		if ($clubID == 0) {
			$newClub = true;
		} else {
			$clubToEditID = $clubID;
		}
	} else if ($_SESSION['__userLevel__'] <= 0 || ($clubID != 0 && $_SESSION['__idClub__'] == $clubID && $_SESSION['__gestionMembresClub__'])) { // Pas d'erreur. On v�rifie bien que c'est une personne autoris�e qui proc�de � l'ajout ou la modification
		$newClub = false;
		if ($clubID == 0 && $_POST['postType'] == "newClub" && $_SESSION['__userLevel__'] <= 0) { // New club to add, only by admins
			//Getting the auto-increment value for the shitty club double ID
			$autoIncrementQuery = "SELECT `AUTO_INCREMENT`
									FROM  INFORMATION_SCHEMA.TABLES
									WHERE TABLE_SCHEMA = 'kuix_tchoukball1'
									AND   TABLE_NAME   = 'ClubsFstb'";
			$autoIncrementData = mysql_query($autoIncrementQuery);
			$autoIncrementArray = mysql_fetch_assoc($autoIncrementData);
			$autoIncrement = $autoIncrementArray['AUTO_INCREMENT'];
			$newClubID = $autoIncrement - 4;


			$memberID = $_POST['memberID'];
			$clubInsertRequest = "INSERT INTO `ClubsFstb` (`id`,
														   `club`,
														   `nomComplet`,
														   `nomPourTri`,
														   `adresse`,
														   `npa`,
														   `ville`,
														   `canton`,
														   `telephone`,
														   `email`,
														   `url`,
														   `actif`,
														   `lastEdit`,
														   `lastEditorID`";
			$clubInsertRequest .= ") VALUES ('".$newClubID."',
											   '".$shortName."',
											   '".$fullName."',
											   '".$nameForSorting."',
											   '".$address."',
											   ".$npa.",
											   '".$city."',
											   ".$cantonID.",
											   '".$phone."',
											   '".$email."',
											   '".$url."',
											   ".$active.",
											   '".date('Y-m-d')."',
											   ".$_SESSION['__idUser__'];
			$clubInsertRequest .= ")";
			$clubInsertResult = mysql_query($clubInsertRequest);
			if ($clubInsertResult) { // Tout s'est bien pass�.
				echo "<p class='success'>Insertion r�ussie.</p>";
				$clubToEditID = $newClubID;
			} else {
				echo "<p class='error'>Erreur lors de l'insertion dans la base de donn�es.</p>";
				$nbError++;
				$newClub = true;
			}
		} elseif ($_POST['postType'] == "editClub") { // Modification of an already existing club


			$clubUpdateRequest = "UPDATE ClubsFstb
									SET adresse='".$address."',
										npa=".$npa.",
										ville='".$city."',
										telephone='".$phone."',
										email='".$email."',
										url='".$url."',
										lastEdit='".date('Y-m-d')."',
										lastEditorID=".$_SESSION['__idUser__'];
			if ($_SESSION['__userLevel__'] <= 0) {
				$clubUpdateRequest .= ", club='".$shortName."'
										 , nomComplet='".$fullName."'
										 , nomPourTri='".$nameForSorting."'
										 , canton=".$cantonID."
										 , actif=".$active."";
			}
			$clubUpdateRequest .= " WHERE id=".$clubID;
			//echo "<p class='info'>".$clubUpdateRequest."</p>";
			$clubUpdateResult = mysql_query($clubUpdateRequest);
			if ($clubUpdateResult) { // Tout s'est bien pass�.
				echo "<p class='success'>Modification r�ussie.</p>";
			} else {
				echo "<p class='error'>Erreur lors de la modification dans la base de donn�es. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.</p>";
				$nbError++;
			}
			$clubToEditID = $clubID;
		} else {
			echo '<p class="error">Action ind�finie.</p>';
			//Ne devrait pas arriver
		}
	} else {
		echo "<p class='error'>Vous n'avez pas le droit d'effectuer cet action.</p>";
	}
} else {
	echo "<p class='error'>Action inconnue</p>";
}
?>