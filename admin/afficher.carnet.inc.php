<?php
	if (isset($_GET['modificationId']) && is_numeric($_GET['modificationId'])) {
		$modificationId = $_GET['modificationId'];
	}
	if (isset($_GET['suppressionId']) && is_numeric($_GET['suppressionId'])) {
		$suppressionId = $_GET['suppressionId'];
	}
	// page intermediaire pour les modifs
	if($_SESSION["__userLevel__"]<=5 && $modificationId!=""){
	//|| $action=="modifierContact"
		include "modifier.contact.inc.php";
	}
	// passe normale
	else{
		// suppression
		if($_SESSION["__userLevel__"]<=5 && $suppressionId!=""){
			$requeteSQL = "DELETE FROM Personne WHERE id='".$suppressionId."'";
			mysql_query($requeteSQL) or die ("h4>Erreur de suppression</h4>");
		}
		// modification
		if ($_POST["action"]=="modifierContact" AND isset($_POST['idPersonne']) AND is_numeric($_POST['idPersonne'])){
			$idPersonne = $_POST['idPersonne'];

			$requeteSQL = "SELECT * FROM Personne WHERE Personne.id='".$idPersonne."'";
			$recordset = mysql_query($requeteSQL);
			$record = mysql_fetch_array($recordset);

			//$nom=validiteInsertionTextBd($_POST["nom"]);
			//$prenom=validiteInsertionTextBd($_POST["prenom"]);
			$userLevel=10;
			$password=md5($_POST["nouveauPass"]);
			$email=validiteInsertionTextBd($_POST["email"]);
			$idClub=validiteInsertionTextBd($_POST["ClubsFstb"]);

			// selection pour le prochain affichage par lettre
			$lettre = substr($record["nom"],0,1);

			if($_POST["nouveauPass"]==""){
					$requeteSQL = "UPDATE `Personne` SET
								`email`='$email',
								`idClub`='$idClub',
								WHERE Personne.id='".$idPersonne."'";
			} else {
				$requeteSQL = "UPDATE `Personne` SET
								`password`='$password'
								`email`='$email',
								`idClub`='$idClub'
								WHERE Personne.id='".$idPersonne."'";
			}

			//echo $requeteSQL;

			if(mysql_query($requeteSQL)===false){
				printErrorMessage("Erreur de modification : contactez le webmaster.");
			}
			else{
				printSuccessMessage("Modification réussie");
			}
		}

		// affichage du carnet
		include "carnet.adresse.inc.php";
	}
?>