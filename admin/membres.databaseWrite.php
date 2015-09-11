<?php

if ($_POST['postType'] == "newMember" || $_POST['postType'] == "editMember") {

	if ($_SESSION['__userLevel__'] <= 5) {
		$idOrigineAdresse = 2; // FSTB
	} else {
		$idOrigineAdresse = 11; // Club
	}

	// V�rification date de naissance
	if (checkdate($_POST['birthDateMonth'], $_POST['birthDateDay'], $_POST['birthDateYear'])) {
		$birthDate = $_POST['birthDateYear']."-".$_POST['birthDateMonth']."-".$_POST['birthDateDay'];
	} elseif ($_POST['birthDateMonth'] == 0 && $_POST['birthDateDay'] == 0 && $_POST['birthDateYear'] == 0 && $_POST['statutID'] != 2) {
		// Si la date de naissance n'est pas pr�cis� et le statut du membre n'est pas actif/junior, alors c'est ok.
		$birthDate = 'NULL';
	} else { //avertissement Javascript
		$birthDate = 'NULL';
		echo "<p class='error'>Erreur dans la date de naissance.</p>";
		$nbError++;
	}

	//V�rification et calcul statut
	if ($_POST['statutID'] == 1) { // Non sp�cifi� (avertissement Javascript)
		$statutID = 3; //Membre actif
		echo "<p class='error'>Statut non sp�cifi�.</p>";
		$nbError++;
	} else if ($_POST['statutID'] != 2) { // Pas membre actif/junior
		$statutID = $_POST['statutID'];
	} else if ($_POST['statutID'] == 2) { // Actif/Junior
		if	($nbError == 0) { //Pas calculable si erreur date
			if (date('Y')-$_POST['birthDateYear'] >=  21) {
				$statutID = 3; // Actif
			} else {
				$statutID = 6; // Junior
			}
		}
	} else {
		 // Ne devrait jamais arriver.
	}


	//V�rification nom et pr�nom non vide
	$noName = false;
	$lastname = ucwordspecific(strtolower(htmlspecialchars(validiteInsertionTextBd($_POST['lastname']))),'-');
	$firstname = ucwordspecific(strtolower(htmlspecialchars(validiteInsertionTextBd($_POST['firstname']))),'-');
	if ($_POST['lastname'] == "" && $_POST['firstname'] == "" && $_POST['companyName'] != "") {
		//C'est ok de ne pas mettre de nom et pr�nom si raison sociale d�finie.
		$noName = true;
	} elseif ($_POST['lastname'] == "" && $_POST['firstname'] == "" && $_POST['companyName'] == "") {
		echo "<p class='error'>Il faut pr�ciser un nom et un pr�nom OU une raison sociale.</p>";
	} elseif ($_POST['lastname'] == "" || $_POST['firstname'] == "") {
		echo "<p class='error'>Nom ou pr�nom manquant.</p>";
		$nbError++;
	}

	if(!$noName) {
		// V�rification existance nom et pr�nom (+indication club)
		//!TODO: G�rez de multiples personnes avec le m�me nom.
		$duplicateNameRequest = "SELECT club, idDbdPersonne FROM `DBDPersonne`, `ClubsFstb` WHERE `nom` LIKE '".$lastname."' AND `prenom` LIKE '".$firstname."' AND `nbIdClub`=`idClub` AND `idDbdPersonne`!=".$_POST['memberID']." LIMIT 1";
		$duplicateNameResult = mysql_query($duplicateNameRequest);
		if (mysql_num_rows($duplicateNameResult) > 0) {
			$duplicateName = mysql_fetch_assoc($duplicateNameResult);
			echo "<p class='error'>Un &laquo; ".$firstname." ".$lastname." &raquo; existe d�j� dans la base de donn�es et est membre du club &laquo; ".$duplicateName['club']." &raquo;.<br />";
			echo "<a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&transfer-request=".$duplicateName['idDbdPersonne']."'>Demandez � le transf�rer</a>.</p>";
			$nbError++;
		}
	}


	$titleID = htmlspecialchars(validiteInsertionTextBd($_POST['DBDCivilite']));
	$companyName = htmlspecialchars(validiteInsertionTextBd($_POST['companyName']));
	$address1 = htmlspecialchars(validiteInsertionTextBd($_POST['address1']));
	$address2 = htmlspecialchars(validiteInsertionTextBd($_POST['address2']));
	$zipCode = htmlspecialchars(validiteInsertionTextBd($_POST['zipCode']));
	$city = htmlspecialchars(validiteInsertionTextBd($_POST['city']));
	$countryID = htmlspecialchars(validiteInsertionTextBd($_POST['DBDPays']));
	$privatePhone = htmlspecialchars(validiteInsertionTextBd($_POST['privatePhone']));
	$workPhone = htmlspecialchars(validiteInsertionTextBd($_POST['workPhone']));
	$mobile = htmlspecialchars(validiteInsertionTextBd($_POST['mobile']));
	$fax = htmlspecialchars(validiteInsertionTextBd($_POST['fax']));
	$email = strtolower(htmlspecialchars(validiteInsertionTextBd($_POST['email'])));
	$clubID = htmlspecialchars(validiteInsertionTextBd($_POST['ClubsFstb']));
	$languageID = htmlspecialchars(validiteInsertionTextBd($_POST['DBDLangue']));
	$sexID = htmlspecialchars(validiteInsertionTextBd($_POST['DBDSexe']));
	$tchoukupID = htmlspecialchars(validiteInsertionTextBd($_POST['DBDCHTB']));
	$refereeID = htmlspecialchars(validiteInsertionTextBd($_POST['DBDArbitre']));
	$publicReferee = isset($_POST['arbitrePublic']) ? 1 : 0;
	$suspended = isset($_POST['suspendu']) ? 1 : 0;
	$typeCompte = htmlspecialchars(validiteInsertionTextBd($_POST['DBDTypeCompte']));
	$numeroCompte = htmlspecialchars(validiteInsertionTextBd($_POST['numeroCompte']));
	$remarques = htmlspecialchars(validiteInsertionTextBd($_POST['remarques']));
	//Si membre passif => tchoukup = E-mail.
	if ($statutID == 4) {
		$tchoukupID = 5;
	}


	if ($nbError > 0) { // Erreur. Si c'�tait un ajout, on veut afficher le formulaire pour nouveau membre, sinon on affiche le formulaire de modification du membre.
		echo "<p class='error'>Proc�dure annul�e.</p>";
		if ($_POST['memberID'] == 0) {
			$newMember = true;
		} else {
			$idMemberToEdit = $_POST['memberID'];
		}
	} else if ($_SESSION['__userLevel__'] <= 5 || ($_SESSION['__nbIdClub__'] == $clubID && $_SESSION['__gestionMembresClub__'])) { // Pas d'erreur. On v�rifie bien que c'est une personne autoris�e qui proc�de � l'ajout ou la modification
		$newMember = false;
		if ($_POST['memberID'] == 0 && $_POST['postType'] == "newMember") { // New member to add
			$memberID = $_POST['memberID'];
			//TODO: Autoriser la modification du nom, pr�nom raison sociale que si ce n'est pas un b�n�vole FSTB
			//TODO: Autoriser la modification des coordonn�es que si ce n'est pas un membre du comit�.
			$memberInsertRequest = "INSERT INTO `DBDPersonne` (`idStatus`,
															   `idOrigineAdresse`,
															   `derniereModification`,
															   `modificationPar`,
															   `editor_id`,
															   `idClub`,
															   `idLangue`,
															   `idSexe`,
															   `idCivilite`,
															   `nom`,
															   `prenom`,
															   `adresse`,
															   `cp`,
															   `npa`,
															   `ville`,
															   `telPrive`,
															   `telProf`,
															   `portable`,
															   `fax`,
															   `email`,
															   `dateNaissance`,
															   `raisonSociale`,
															   `idPays`,
															   `idCHTB`,
															   `dateAjout`";
			if ($_SESSION['__userLevel__'] <= 5) {
				$memberInsertRequest .= ", `idArbitre`,
										   `typeCompte`,
										   `numeroCompte`,
										   `remarque`";
			}
			$memberInsertRequest .= ") VALUES ('".$statutID."',
											   '".$idOrigineAdresse."',
											   '".date('Y-m-d')."',
											   '".$_SESSION['__nom__']." ".$_SESSION['__prenom__']."',
											   '".$_SESSION['__idUser__']."',
											   '".$clubID."',
											   '".$languageID."',
											   '".$sexID."',
											   '".$titleID."',
											   '".$lastname."',
											   '".$firstname."',
											   '".$address1."',
											   '".$address2."',
											   '".$zipCode."',
											   '".$city."',
											   '".$privatePhone."',
											   '".$workPhone."',
											   '".$mobile."',
											   '".$fax."',
											   '".$email."',
											   '".$birthDate."',
											   '".$companyName."',
											   '".$countryID."',
											   '".$tchoukupID."',
											   '".date('Y-m-d')."'";

			if ($_SESSION['__userLevel__'] <= 5) {
				$memberInsertRequest .= ", '".$refereeID."',
										   '".$typeCompte."',
										   '".$numeroCompte."',
										   '".$remarques."'";
			}
			$memberInsertRequest .= ")";
			$memberInsertResult = mysql_query($memberInsertRequest);
			if ($memberInsertResult) { // Tout s'est bien pass�.
				echo "<p class='success'>Insertion r�ussie.</p>";
				$idMemberToEdit = mysql_insert_id();
			} else {
				echo "<p class='error'>Erreur lors de l'insertion dans la base de donn�es.";
				echo "<br/>Requ�te: ".$memberInsertRequest;
				echo "<br/>Message: ".mysql_error()."</p>";
				$nbError++;
				$newMember = true;
			}
		} elseif ($_POST['postType'] == "editMember") { // Modification of an already existing member
			$memberID = $_POST['memberID'];

			//R�cup�ration des donn�es actuelles pour comparaison avec les nouvelles afin d'enregistrer les modifications dans le log.
			//Ne pas d�placer apr�s l'insertion des nouvelles donn�es car on ne r�cup�rerait plus les anciennes valeurs.
			/*$memberCurrentValuesRequest = "SELECT * FROM DBDPersonne WHERE idDbdPersonne=".$memberID." LIMIT 1";
			if($memberCurrentValuesResult = mysql_query($memberCurrentValuesRequest)) {
				$memberCurrentValues = mysql_fetch_assoc($memberCurrentValuesResult);
			} else {
				echo '<p class="error">Erreur lors de la r�cup�ration des donn�es actuelles pour comparaison. Contactez le <a href="mailto:webmaster@tchoukball.ch">webmaster</a>.</p>';
				//echo '<p class="info">'.$memberCurrentValuesRequest.' <br /> $_POST[\'memberID\']='.$_POST['memberID'].'</p>';
			}*/


			$memberUpdateRequest = "UPDATE DBDPersonne
									SET idStatus='".$statutID."',
										idOrigineAdresse=11,
										derniereModification='".date('Y-m-d')."',
										modificationPar='".$_SESSION['__nom__']." ".$_SESSION['__prenom__']."',
										editor_id=".$_SESSION["__idUser__"].",
										idLangue=".$languageID.",
										idSexe=".$sexID.",
										idCivilite=".$titleID.",
										nom='".$lastname."',
										prenom='".$firstname."',
										adresse='".$address1."',
										cp='".$address2."',
										npa='".$zipCode."',
										ville='".$city."',
										telPrive='".$privatePhone."',
										telProf='".$workPhone."',
										portable='".$mobile."',
										fax='".$fax."',
										email='".$email."',
										dateNaissance='".$birthDate."',
										raisonSociale='".$companyName."',
										idPays='".$countryID."',
										idCHTB=".$tchoukupID;
			if ($_SESSION['__userLevel__'] <= 5) {
				$memberUpdateRequest .= ", idClub=".$clubID."
										 , idArbitre=".$refereeID."
										 , arbitrePublic=".$publicReferee."
										 , suspendu=".$suspended."
										 , typeCompte='".$typeCompte."'
										 , numeroCompte='".$numeroCompte."'
										 , remarque='".$remarques."'";
			}
			$memberUpdateRequest .= " WHERE idDbdPersonne=".$memberID;
			//echo "<p class='info'>".$memberUpdateRequest."</p>";
			$memberUpdateResult = mysql_query($memberUpdateRequest);
			if ($memberUpdateResult) { // Tout s'est bien pass�.
				echo "<p class='success'>Modification r�ussie.</p>";
			} else {
				echo "<p class='error'>Erreur lors de la modification dans la base de donn�es. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.</p>";
				if ($_SESSION['__userLevel__'] == 6) {
					printMessage(mysql_error());
					printMessage($memberUpdateRequest);
				}
				$nbError++;
			}
			$idMemberToEdit = $_POST['memberID'];
		} else {
			echo '<p class="error">Action ind�finie.</p>';
			//Ne devrait pas arriver
		}
	} else {
		echo "<p class='error'>Vous n'avez pas le droit d'effectuer cet action.</p>";
	}
} elseif ($_POST['postType'] == "transfer-request") {
	//On ne v�rifie pas que la personne qui fait la demande soit du club entrant ou sortant. Ce n'est qu'une demande.
	if (isValidClubID($_POST['currentClubID']) && isValidClubID($_POST['ClubsFstb'])) {
		$transferRequestQuery = "INSERT INTO `DBDRequetesChangementClub` (`userID`, `idDbdPersonne`, `from_clubID`, `to_clubID`, `datetime`)
								 VALUES (".$_SESSION['__idUser__'].", ".$_POST['memberID'].", ".$_POST['currentClubID'].", ".$_POST['ClubsFstb'].", '".date('Y-m-d H:i:s')."')";
		if (mysql_query($transferRequestQuery)) {
			$requestID = mysql_insert_id();
			//Envoi d'un e-mail pour avertir le webmaster
			$destinataireMail ="webmaster@tchoukball.ch";
			$objectMail = "Auto: Demande de transfert No ".$requestID;
			$messageMail = $_SESSION['__prenom__']." ".$_SESSION['__nom__']." demande un transfert pour <strong>".htmlentities($_POST['memberName'])."</strong>.<br /><br />";
			$messageMail .= "Club d'origine : <strong>".htmlentities($_POST['currentClubName'])."</strong><br /><br />";
			$messageMail .= "Nouveau club : <strong>".htmlentities($_POST['newClubName'])."</strong><br /><br />";
			$from = "From:no-reply@tchoukball.ch\n";
			$from .= "MIME-version: 1.0\n";
			$from .= "Content-type: text/html; charset= iso-8859-1\n";
			if(mail($destinataireMail, $objectMail, $messageMail, $from)) {
				echo '<p class="success">Demande de transfert envoy�e. Elle sera trait�e prochainement et vous serez tenu inform� de son ex�cution.</p>';
				//echo '<p class="info">mail('.$destinataireMail.', '.$objectMail.', '.$messageMail.', '.$from.')</p>';
			} else {
				echo '<p class="error">La demande de transfert a �t� enregistr�e, mais d� � une erreur, le webmaster n\'a pas automatiquement �t� averti, veuillez <a href="mailto:webmaster@tchoukball.ch">le contacter</a> s\'il vous pla�t.</p>';
			}
		} else {
			echo '<p class="error">Erreur lors de l\'enregistrement de la demande de transfert.<br />'.mysql_error().'</p>';
		}
	} else {
		echo '<p class="error">ID invalide<br />'.htmlentities($_POST['currentClubID'].' '.$_POST['ClubsFstb']).'</p>';
	}
}
?>