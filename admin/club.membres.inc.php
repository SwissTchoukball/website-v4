<div id="membres"><?
	statInsererPageSurf(__FILE__);

	//$clubRequest = "SELECT nbIdClub AS idClub, gestionMembresClub, club FROM Personne, ClubsFstb WHERE Personne.nom='".$_SESSION["__nom__"]."' AND Personne.prenom='".$_SESSION["__prenom__"]."' AND Personne.idClub=ClubsFstb.id";
	$clubRequest = "SELECT club FROM ClubsFstb WHERE nbIdClub=".$_SESSION["__nbIdClub__"];
	$clubResult = mysql_query($clubRequest);
	$clubData = mysql_fetch_assoc($clubResult);

	$clubName = $clubData['club'];

	//Vérification si le montant de la cotisation est bloqué et non-payé.
	$montantBloqueEtNonPaye = false;
	$resultCheckIfBlocked = mysql_query("SELECT montantBloque, datePaiement FROM Cotisations_Clubs WHERE idClub=".$_SESSION['__nbIdClub__']);
	while($donnees = mysql_fetch_assoc($resultCheckIfBlocked)) {
		if ($donnees['montantBloque'] == 1 && $donnees['datePaiement'] == NULL) {
			$montantBloqueEtNonPaye = true;
		}
	}


	echo "<h4>".$clubName." ".$testSubmit."</h4><br />";

	if (!$_SESSION['__gestionMembresClub__']) {
		echo "<p class='info'>Vous n'êtes pas reconnu en tant que gestionnaire des membres de votre club (Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a> si vous l'êtes)</p>";
	} elseif ($montantBloqueEtNonPaye) {
		echo "<p class='info'>Vous ne pouvez pas faire de modification car le montant de la cotisation est bloqué et en attente de confirmation de paiement.</p>";
	} else {
		?>
<p><a href="?menuselection=<? echo $menuselection; ?>&smenuselection=<? echo $smenuselection; ?>&new"><img src="admin/images/ajouter.png" alt="Ajouter un membre" /> Ajouter un membre</a><br />
<a href="?menuselection=<? echo $menuselection; ?>&smenuselection=<? echo $smenuselection; ?>"><img src="admin/images/liste.png" alt="Liste des membres simple" /> Liste des membres simple</a><br />
<a href="?menuselection=<? echo $menuselection; ?>&smenuselection=<? echo $smenuselection; ?>&details"><img src="admin/images/liste.png" alt="Liste des membres détaillée" /> Liste des membres détaillée</a><br />
<a href="admin/club.export.excel.php"><img src="admin/images/document_excel.png" alt="Liste Excel" /> Liste Excel</a></p>
		<?

		$newMember = false;
		$nbError = 0;
		if (isset($_POST['memberID']) && (isValidMemberID($_POST['memberID']) || $_POST['memberID'] == 0)) {
			include("admin/membres.databaseWrite.php");
			if ($nbError == 0) {
				include("admin/club.liste.membres.inc.php");
			} else {
				include("admin/membres.editer.inc.php");
			}
		} elseif (isset($_GET['edit']) && isValidMemberID($_GET['edit'])) { //édition demandé && id conforme
			$idMemberToEdit = $_GET['edit'];
			include("admin/membres.editer.inc.php");
		} elseif (isset($_GET['new'])) { //ajout
			$newMember = true;
			include("admin/membres.editer.inc.php");
		} elseif (isset($_GET['delete']) && isValidMemberID($_GET['delete'])) { //suppression demandé && id conforme
			include("admin/membres.supprimer.inc.php");
			include("admin/club.liste.membres.inc.php");
		} elseif (isset($_GET['transfer-request']) && isValidMemberID($_GET['transfer-request'])) { // demande de transfert demandé et id conforme
			include("admin/membres.requete-transfert.inc.php");
		} else {
			include("admin/club.liste.membres.inc.php");
		}
	}
	?>
</div>