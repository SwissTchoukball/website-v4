<?php

$memberQuery = "SELECT p.idDbdPersonne AS id, p.nom, p.prenom, p.raisonSociale, c.nbIdClub AS idClubActuel, c.club AS nomClubActuel FROM DBDPersonne p, ClubsFstb c WHERE p.idDbdPersonne=".$_GET['transfer-request']." AND p.idClub=c.nbIdClub LIMIT 1";
if ($memberResource = mysql_query($memberQuery)) {
	$member = mysql_fetch_assoc($memberResource);
	if ($member['prenom'] == "" && $member['nom'] == "") {
		$name = $member['raisonSociale'];
	} else {
		$name = $member['prenom'].' '.$member['nom'];
	}

	if ($member['idClubActuel'] == 15) {
		$currentClubName = "Aucun club";
	} else {
		$currentClubName = $member['nomClubActuel'];
	}
	?>
	<h3>Demande de transfert pour <?php echo $name; ?></h3>
	<form method="post" onchange="updateNewClubName();" name="transfer-request" action="?menuselection=<? echo $menuselection; ?>&smenuselection=<? echo $smenuselection; ?>">
		<label>Club d'origine</label>
		<p><?php echo $currentClubName; ?></p>

		<label>Nouveau club</label>
		<?php afficherListeClubs($member['idClubActuel'], "nbIdClub"); ?>
		<input type="hidden" name="memberID" value="<?php echo $member['id']; ?>" />
		<input type="hidden" name="memberName" value="<?php echo $name; ?>" />
		<input type="hidden" name="currentClubID" value="<?php echo $member['idClubActuel']; ?>" />
		<input type="hidden" name="currentClubName" value="<?php echo $currentClubName; ?>" />
		<input type="hidden" name="newClubName" value="<?php echo $currentClubName; /* It will change with JavaScript*/ ?>" />
		<input type="hidden" name="postType" value="transfer-request" />
		<input type="submit" value="Envoyer la demande de transfert" />
	</form>
	<?php
} else {
	echo '<p class="error">Erreur lors de la récupération des données du membre.<br />'.mysql_error().'</p>';
}

?>
<script type="text/javascript">
	function updateNewClubName() {
		var newClubName = $("select[name=ClubsFstb] option:selected").text();
		$("input[name=newClubName]").attr("value",newClubName);
	}
</script>