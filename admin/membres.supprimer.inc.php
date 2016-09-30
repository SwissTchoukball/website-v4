<?php
$requestMembre =
	"SELECT idStatus, derniereModification, modificationPar, p.idClub, idLangue, idSexe, idCivilite,
			nom, prenom, adresse, cp, npa, ville, telPrive, telProf, portable, fax, email, emailFSTB,
			dateNaissance, raisonSociale, idPays, idCHTB, a.idArbitre AS niveauArbitreID,
			a.descriptionArbitre" . $_SESSION['__langue__'] . " AS niveauArbitre, arbitrePublic, suspendu,
			typeCompte, numeroCompte, remarque, c.idFonction AS idFonctionComite,
			cm.idNom AS idCommissionMembre, cn.id AS idCommissionResponsable,
			cnm.idEquipe AS idEquipeMembre, exp.idPersonne AS idExpert,
			cj.id AS idParticipationChampionnat,
			ce.idEquipe AS idEquipeChampionnatResponsable
	FROM DBDPersonne p
	LEFT OUTER JOIN DBDArbitre a ON p.idArbitre = a.idArbitre
	LEFT OUTER JOIN Comite_Membres c ON p.idDbdPersonne = c.idPersonne
	LEFT OUTER JOIN Commission_Membre cm ON p.idDbdPersonne = cm.idPersonne
	LEFT OUTER JOIN Commission_Nom cn ON p.idDbdPersonne = cn.idResponsable
	LEFT OUTER JOIN CadreNational_Membres cnm ON p.idDbdPersonne = cnm.idPersonne
	LEFT OUTER JOIN ExpertsJS exp ON p.idDbdPersonne = exp.idPersonne
	LEFT OUTER JOIN Championnat_Joueurs cj ON p.idDbdPersonne = cj.personId
	LEFT OUTER JOIN Championnat_Equipes ce ON p.idDbdPersonne = ce.idResponsable
	WHERE idDbdPersonne=" . $_GET['delete'] . "
	LIMIT 1";
$resultMembre = mysql_query($requestMembre);
if (!$resultMembre) {
	echo "<p class='error'>Erreur lors de la collecte d'information sur le membre à supprimer.</p>";
} elseif (mysql_num_rows($resultMembre) < 1) {
	echo "<p class='error'>Le membre que vous voulez supprimer n'existe pas.</p>";
} else {
	$member = mysql_fetch_assoc($resultMembre);

	// Defining if the member can be deleted
	$refereeLevelId = $member['niveauArbitreID'];
	$isCommitteeMember = $member['idFonctionComite'] != null;
	$isCommissionMember = $member['idCommissionMembre'] != null || $member['idCommissionResponsable'] != null;
	$isSwissTeamMember = $member['idEquipeMembre'] != null;
	$isJSExpert = $member['idExpert'] != null;
	$isChampionshipPlayer = $member['idParticipationChampionnat'] != null;
	$isChampionshipTeamManager = $member['idEquipeChampionnatResponsable'] != null;

	$isInvolvedInFederation = $refereeLevelId > 1 ||
		$isCommitteeMember ||
		$isCommissionMember ||
		$isSwissTeamMember ||
		$isJSExpert ||
		$isChampionshipPlayer ||
		$isChampionshipTeamManager;


	// Retrieving information to know if we are in the deletion period
	$deletionPeriodQuery =
		"SELECT ccLastYear.idClub, ccLastYear.datePaiement AS datePaiementAnneePassee, c.delaiSupprimerMembres
		 FROM Cotisations c
		 LEFT OUTER JOIN Cotisations_Clubs ccLastYear
		  ON ccLastYear.annee = c.annee - 1
		  AND ccLastYear.idClub = " . $member['idClub'] . "
		 WHERE c.annee <= '" . date('Y') . "'
		 ORDER BY c.annee DESC
		 LIMIT 1";

	$deletionPeriodResult = mysql_query($deletionPeriodQuery);
	$deletionPeriodData = mysql_fetch_assoc($deletionPeriodResult);

	$today = date('Y-m-d');

	$isDeletionPeriod = ($today <= $deletionPeriodData['delaiSupprimerMembres'] &&
		$deletionPeriodData['datePaiementAnneePassee'] != NULL) ||
        $deletionPeriodData['idClub'] == NULL; //S'il ne s'agit pas d'un club ou d'un club qui n'a pas de cotisations (non-adhérent)

	if ($_SESSION['__userLevel__'] > 5 && ($member['idClub'] != $_SESSION['__nbIdClub__'] || !$_SESSION['__gestionMembresClub__'])) {
		printErrorMessage("Vous ne pouvez pas supprimer un membre qui ne fait pas parti de votre club.");
	} elseif ($isInvolvedInFederation) {
		printErrorMessage("Vous ne pouvez pas supprimer un membre qui impliqué au sein de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".");
	} elseif(!$isDeletionPeriod && $_SESSION['__userLevel__'] > 5) {
		printErrorMessage("Vous ne pouvez pas supprimer un membre en dehors de la période de suppression qui se situe en début de saison.");
	} else {
		$memberDeleteRequest = "DELETE FROM DBDPersonne WHERE idDbdPersonne=".$_GET['delete']." LIMIT 1";
		$memberDeleteResult = mysql_query($memberDeleteRequest);
		if (!$memberDeleteResult) {
			printErrorMessage("Erreur lors de la suppression du membre de la base de données.");
		} else {
			printSuccessMessage($member['prenom']." ".$member['nom']." a correctement été supprimé de la base de données.");
		}
	}
}
?>
