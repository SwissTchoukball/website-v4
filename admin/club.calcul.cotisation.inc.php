<script lang="javascript">
	function askForConfirmation() {
		return confirm("Voulez-vous vraiment bloquer le montant de la cotisation ?\r\nA partir du moment où vous bloquez le montant de la cotisation et jusqu'à confirmation du paiement de notre part, vous ne pourrez plus modifier les informations de vos membres.");
	}
</script>

<div id="calculCotisation"><?php
	statInsererPageSurf(__FILE__);

	//TODO : Vérifier si les statuts correspondent à l'âge des membres.

	$idActifs = 3;
	$idJuniors = 6;
	$idSoutiens = 5;
	$idPassifs = 4;
	$idVIP = 23;
	$idStatutsACompter = array($idActifs, $idJuniors, $idSoutiens, $idPassifs, $idVIP);
	$clubRequestPart_nbMembresStatut = "";
	$statutsRequestPart_WHERE = "WHERE ";
	foreach ($idStatutsACompter as $id) {
		$clubRequestPart_nbMembresStatut .= ", COUNT(if(idStatus=".$id.",1,NULL)) AS nbMembresStatut".$id;
		$statutsRequestPart_WHERE .= " idStatus=".$id." OR";
	}
	$statutsRequestPart_WHERE .= " 1=0"; //because of the "OR" left at the end.

	$nbMembresPourUnAbonnementVIPOffert = 20;

	if($_SESSION['__userLevel__'] > 5 || !isset($_POST['club'])) {
		$clubRequestPart_WHERE = "Personne.nom='".$_SESSION["__nom__"]."' AND Personne.prenom='".$_SESSION["__prenom__"]."' AND Personne.idClub=ClubsFstb.id";
	} else {
		$clubRequestPart_WHERE = "ClubsFstb.nbIdClub=".$_POST['club']." AND Personne.id=168";
	}

	/* CLUB REQUEST */
	$clubRequest = "SELECT ClubsFstb.nbIdClub AS idClub, gestionMembresClub, club".$clubRequestPart_nbMembresStatut."
					FROM Personne, ClubsFstb LEFT OUTER JOIN DBDPersonne ON ClubsFstb.nbIdClub=DBDPersonne.idClub
					WHERE ".$clubRequestPart_WHERE." AND actif=1
					GROUP BY DBDPersonne.idClub";
	//echo $clubRequest;

	$clubResult = mysql_query($clubRequest) or die ("<p class='error'>Mauvaise requête</p>");
	$clubData = mysql_fetch_array($clubResult);

	$clubId = $clubData['idClub'];
	$clubName = $clubData['club'];
	if (mysql_num_rows($clubResult) == 0) {
		$clubId = 15;
	}

	//Donner l'autorisation de changer de club si l'utilisateur est membre du comité.
	if ($_SESSION['__userLevel__'] <= 5) {
		if (isset($_POST['club'])) {
			$clubId = $_POST['club'];
		}

		?>
	<form name="clubSwitcher" method="post" action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>">
		<select name="club" onChange="document.clubSwitcher.submit();">
			<option value="15">Choisir un club</option>
			<?php
			$clubsRequest = "SELECT nbIdClub, club FROM ClubsFstb WHERE actif=1 ORDER BY club";
			$clubsResult = mysql_query($clubsRequest);
			while($clubsData = mysql_fetch_assoc($clubsResult)) {
				$selected = "";
				if ($clubsData['nbIdClub'] == $clubId) {
					$selected = " selected='selected'";
					$clubName = $clubsData['club'];
				}
				?>
				<option value="<?php echo $clubsData['nbIdClub']; ?>"<?php echo $selected; ?>><?php echo $clubsData['club']; ?></option>
				<?php
			}
			?>
		</select>
	</form>
		<?php
		$isManager = true;
	} else {
		if ($clubData['gestionMembresClub'] == 1){
			$isManager = true;
		} else {
			$isManager = false;
		}
	}
	if ($_SESSION["__nbIdClub__"] == 15 AND $_SESSION['__userLevel__'] > 5) {
		echo "<p class='info'>Aucun club n'est associé à votre compte.</p>";
	} else if (!$isManager) {
		echo "<p class='info'>Vous n'êtes pas reconnu en tant que gestionnaire des membres de votre club. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a> si vous l'êtes.</p>";
	} else {
		echo "<h4>".$clubName."</h4>";
		?>
	<table>
		<thead>
			<tr>
				<th></th>
				<th>Par membre</th>
				<th>Nombre</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
		<?php

		/* STATUTS REQUEST */
		$statutsRequest = "SELECT idStatus, descriptionStatus".$_SESSION['__langue__']." AS name, cotisation FROM DBDStatus ".$statutsRequestPart_WHERE." ORDER BY name";
		$statutsResult = mysql_query($statutsRequest);


		$nbMembres = array();
		$montantCotisation = 0;
		$nbMembresTotal = 0;
		while ($statutsData = mysql_fetch_assoc($statutsResult)) {
			$id = $statutsData['idStatus'];
			$nomStatuts = $statutsData['name'];
			$cotisationStatuts = $statutsData['cotisation'];
			$nbMembres[$id] = $clubData['nbMembresStatut'.$id];
			$nbMembresTotal += $nbMembres[$id];

			$cotisationTotaleParStatut = $cotisationStatuts * $nbMembres[$id];
			$montantCotisation += $cotisationTotaleParStatut;

			switch ($id) {
				case $idActifs:
					$nbMembresActifs = $nbMembres[$id];
					break;
				case $idJuniors:
					$nbMembresJuniors = $nbMembres[$id];
					break;
				case $idSoutiens:
					$nbMembresSoutiens = $nbMembres[$id];
					break;
				case $idPassifs:
					$nbMembresPassifs = $nbMembres[$id];
					break;
				case $idVIP:
					$nbMembresVIP = $nbMembres[$id];
					break;
			}
			?>
			<tr>
				<td><?php echo $nomStatuts; ?></td>
				<td>CHF <?php echo $cotisationStatuts; ?></td>
				<td><?php echo $nbMembres[$id]; ?></td>
				<td>CHF <?php echo $cotisationTotaleParStatut; ?></td>
			</tr>
			<?php
			if ($id == $idVIP) {
				// Attention, il faut que les membres actifs à compter le soient avant le calcul pour les membres VIPs. Or cela n'est pas vérifié et si ce n'est pas le cas, le calcul sera faux.
				$nbAbonnementVIPOffertsMax = floor(($nbMembres[$idActifs] + $nbMembres[$idJuniors]) / $nbMembresPourUnAbonnementVIPOffert + 1);
				$nbAbonnementVIPOfferts = min($nbAbonnementVIPOffertsMax,$nbMembres[$id]);
				$reductionVIP = $nbAbonnementVIPOfferts * (-$cotisationStatuts);
				$montantCotisation += $reductionVIP;
				?>
				<tr>
					<td>Abonnements VIP offerts</td>
					<td>CHF -<?php echo $cotisationStatuts; ?></td>
					<td><?php echo $nbAbonnementVIPOfferts ?></td>
					<td>CHF <?php echo $reductionVIP; ?></td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td>Total</td>
				<td></td>
				<td></td>
				<td>CHF <?php echo $montantCotisation; ?></td>
			</tr>
		</tfoot>
	</table>
	<p>Total de <?php echo $nbMembresTotal; ?> membres</p>
	<br />
		<?php
		if ($isManager) {
			//Gestion du blocage de la cotisation
			if (isset($_POST['annee']) && isset($_POST['montant'])) {
					$saisonCotisationAnneeDebut = $_POST['annee'];
					$saisonCotisationAnneeFin = $_POST['annee'] + 1;
				$requeteBloquerCotisation = "UPDATE Cotisations_Clubs
											 SET montantBloque=1, montant='".$montantCotisation."',
											 	 nbMembresActifs='".$nbMembresActifs."',
											 	 nbMembresJuniors='".$nbMembresJuniors."',
											 	 nbMembresSoutiens='".$nbMembresSoutiens."',
											 	 nbMembresPassifs='".$nbMembresPassifs."',
											 	 nbMembresVIP='".$nbMembresVIP."'
											 WHERE idClub=".$clubId." AND annee='".$saisonCotisationAnneeDebut."'";
				if (mysql_query($requeteBloquerCotisation)) {
					echo "<p class='success'>Le montant de la cotisation ".$saisonCotisationAnneeDebut."-".$saisonCotisationAnneeFin." a été bloqué à ".$montantCotisation." CHF.</p>";
					//Envoi d'un e-mail pour avertir le responsable des finances
					$from = "From:no-reply@tchoukball.ch\n";
					$from .= "MIME-version: 1.0\n";
					$from .= "Content-type: text/html; charset= iso-8859-1\n";
					$destinataireMail ="finances@tchoukball.ch, webmaster@tchoukball.ch";
					mail($destinataireMail, "Auto: Blocage montant cotisation", "Le club « ".$clubName." » a bloqué le montant de sa cotisation ".$saisonCotisationAnneeDebut."-".$saisonCotisationAnneeFin." à ".$montantCotisation." CHF et doit maintenant effectuer le versement à " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ". ",$from);
				} else {
					echo "<p class='error'>Erreur lors du blocage du montant de la cotisation. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.</p>";
				}
			}


			//Affichage de l'état actuel du bloquage et paiement de la cotisation
			$anneePassee = date('Y')-1;
			$requeteEtatCotisation = "SELECT Cotisations_Clubs.annee, montantBloque, montant, datePaiement, etatMembresAu, delaiBloquer, delaiPayer FROM Cotisations_Clubs, Cotisations WHERE Cotisations_Clubs.annee=Cotisations.annee AND Cotisations_Clubs.annee>='".$anneePassee."' AND Cotisations_Clubs.idClub=".$clubId." ORDER BY annee DESC";
			//echo $requeteEtatCotisation;
			$retourEtatCotisation = mysql_query($requeteEtatCotisation);
			while ($etatCotisation = mysql_fetch_assoc($retourEtatCotisation)) {
				if ($etatCotisation['annee'] < $anneePassee && $etatCotisation['datePaiement'] != NULL) {
					//Ne rien afficher si l'année passée est en ordre.
				} else {
					$saisonCotisationAnneeDebut = $etatCotisation['annee'];
					$saisonCotisationAnneeFin = $etatCotisation['annee'] + 1;
					echo "<h4>Cotisation ".$saisonCotisationAnneeDebut."-".$saisonCotisationAnneeFin." <span class='precision'>Etat des membres du club au ".date_sql2date($etatCotisation['etatMembresAu'])."</span></h4><br />";
					if ($etatCotisation['montantBloque'] == 1) {
						if ($etatCotisation['datePaiement'] != NULL) {
							echo "<p class='success'>Montant de ".$etatCotisation['montant']." CHF <strong>payé</strong> le " . date_sql2date($etatCotisation['datePaiement']) . ". <a href='/pdf_generator/quittance_cotisation_club.php?annee=" . $saisonCotisationAnneeDebut . "'>Télécharger la quittance</a></p>";
						} else {
							if ($etatCotisation['delaiPayer'] > date('Y-m-d')) {
								$etatCotisationClass = "info";
							} else {
								$etatCotisationClass = "error";
							}
							echo "<p class='".$etatCotisationClass."'>Montant de ".$etatCotisation['montant']." CHF <strong>non-payé</strong>.<br />Vous avez jusqu'".date_sql2date_joli($etatCotisation['delaiPayer'],"au",$_SESSION['__langue__'])." pour payer votre cotisation.</p>";
						}
					} else {
						if ($etatCotisation['delaiBloquer'] > date('Y-m-d')) {
							$etatCotisationClass = "info";
						} else {
							$etatCotisationClass = "error";
						}
						echo "<p class='".$etatCotisationClass."'>Montant de la cotisation non-bloqué.<br />Vous avez jusqu'".date_sql2date_joli($etatCotisation['delaiBloquer'],"au",$_SESSION['__langue__'])." pour bloquer le montant de votre cotisation";
						if ($etatCotisation['delaiPayer'] != $etatCotisation['delaiBloquer']) {
							echo ", puis jusqu'".date_sql2date_joli($etatCotisation['delaiPayer'],"au",$_SESSION['__langue__'])." pour la payer.";
						} else {
							echo " et la payer.";
						}
						echo "</p><br />";
						?>
						<form method="post" onsubmit="return askForConfirmation();" action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>">
							<input type="hidden" name="montant" value="<?php echo $montantCotisation; ?>" />
							<input type="hidden" name="annee" value="<?php echo $saisonCotisationAnneeDebut; ?>" />
							<input type="submit" value="Bloquer un montant de <?php echo $montantCotisation; ?> CHF pour la cotisation <?php echo $saisonCotisationAnneeDebut."-".$saisonCotisationAnneeFin; ?> du <?php echo $clubName; ?>" />
						</form>
						<?php
					}
					echo "<br />";
				}
			}
		}
		?>
	<div id='ccpFSTB'>CCP <?php echo VAR_LANG_ASSOCIATION_NAME; ?><br />20-8957-2</div>
		<?php
	}

	?>
</div>
