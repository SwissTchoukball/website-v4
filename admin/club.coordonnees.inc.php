<div id="clubs">
	<?php
	if (!$_SESSION['__gestionMembresClub__']) {
		//Il faudra éventuellement redéfinir qui peut vraiment modifier ces informations (faire une matrice avec des rôles pour chaque club)
		echo "<p class='info'>Vous n'êtes pas reconnu en tant que gestionnaire des membres de votre club (Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a> si vous l'êtes)</p>";
	} else {
		if (isset($_POST['clubID']) && isValidClubID($_POST['clubID'])) {
			include("admin/clubs.databaseWrite.php");
		}
		$idClubToEdit = $_SESSION['__idClub__'];
		include("admin/clubs.editer.inc.php");
	}
	?>
</div>