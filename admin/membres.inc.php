<div id="membres">
	<?php
	statInsererPageSurf(__FILE__);
	?>

	<p><a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&new"><img src="admin/images/ajouter.png" alt="Ajouter un membre" /> Ajouter un membre</a><br />
	<a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>"><img src="admin/images/liste.png" alt="Liste des membres détaillée" /> Liste des membres</a></p>

	<?php

	$showDetails = true;
	$nbMembersPerPage = 100;
	$clubToShowId = null;

	$isManager = true;

	$newMember = false;
	$nbError = 0;
	if (isset($_POST['memberID']) && (isValidMemberID($_POST['memberID']) || $_POST['memberID'] == 0)) {
		include("admin/membres.databaseWrite.php");
		if ($nbError == 0) {
			include('admin/membres.liste.inc.php');
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
		include('admin/membres.liste.inc.php');
	} elseif (isset($_GET['transfer-request']) && isValidMemberID($_GET['transfer-request'])) { // transfert demandé et id conforme
		include("admin/membres.requete-transfert.inc.php");
	} else {
		include('admin/membres.liste.inc.php');
	}

	?>
</div>
