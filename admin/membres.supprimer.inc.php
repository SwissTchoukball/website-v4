<?php
$requestMembre = "SELECT nom, prenom, idArbitre, idClub, c.idFonction AS idFonctionComite, cm.idNom AS idCommissionMembre, cr.id AS idCommissionResponsable, cnm.idEquipe AS idEquipeMembre
				  FROM DBDPersonne p
				  LEFT OUTER JOIN Comite_Membres c ON p.idDbdPersonne = c.idPersonne
				  LEFT OUTER JOIN Commission_Membre cm ON p.idDbdPersonne = cm.idPersonne
				  LEFT OUTER JOIN Commission_Nom cr ON p.idDbdPersonne = cr.idResponsable
				  LEFT OUTER JOIN CadreNational_Membres cnm ON p.idDbdPersonne = cnm.idPersonne
				  WHERE idDbdPersonne=".$_GET['delete']."
				  LIMIT 1";
$resultMembre = mysql_query($requestMembre);
if (!$resultMembre) {
	echo "<p class='error'>Erreur lors de la collecte d'information sur le membre à supprimer.</p>";
} elseif (mysql_num_rows($resultMembre) < 1) {
	echo "<p class='error'>Le membre que vous voulez supprimer n'existe pas.</p>";
} else {
	$membre = mysql_fetch_assoc($resultMembre);
	$isCommitteeMember = $membre['idFonctionComite'] != null;
	$isCommissionMember = $membre['idCommissionMembre'] != null || $membre['idCommissionResponsable'] != null;
	$isSwissTeamMember = $member['idEquipeMembre'] != null;
	if ($_SESSION['__userLevel__'] > 5 && ($membre['idClub'] != $_SESSION['__nbIdClub__'] || !$_SESSION['__gestionMembresClub__'])) {
		echo "<p class='error'>Vous ne pouvez pas supprimer un membre qui ne fait pas parti de votre club.</p>";
	} elseif ($_SESSION['__userLevel__'] > 5 && $membre['idArbitre'] > 1) {
		echo "<p class='error'>Vous ne pouvez pas supprimer un membre qui est arbitre. <a href='mailto:webmaster@tchoukball.ch'>Contactez le webmaster</a>.</p>";
	} elseif ($isCommitteeMember) {
		echo "<p class='error'>Vous ne pouvez pas supprimer un membre qui a fait partie du comité exécutif de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".</p>";
	} elseif ($isCommissionMember) {
		echo "<p class='error'>Vous ne pouvez pas supprimer un membre qui a fait partie d'une commission de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".</p>";
	} elseif ($isSwissTeamMember) {
		echo "<p class='error'>Vous ne pouvez pas supprimer un membre qui a fait partie du cadre national.</p>";
	} else {
		$memberDeleteRequest = "DELETE FROM DBDPersonne WHERE idDbdPersonne=".$_GET['delete']." LIMIT 1";
		$memberDeleteResult = mysql_query($memberDeleteRequest);
		if (!$memberDeleteResult) {
			echo "<p class='error'>Erreur lors de la suppression du membre de la base de données.</p>";
		} else {
			echo "<p class='success'>".$membre['prenom']." ".$membre['nom']." a correctement été supprimé de la base de données.</p>";
		}
	}
}
?>
