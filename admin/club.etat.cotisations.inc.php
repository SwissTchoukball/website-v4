<h4>Situation actuelle</h4>
<form id="etatCotisations" method="post" action="?menuselection=<? echo $menuselection; ?>&smenuselection=<? echo $smenuselection; ?>">
	<table>
		<tr>
			<th>Année</th>
			<th>Club</th>
			<th>Montant</th>
			<th>Payé</th>
		</tr>
		<?
		$anneePassee = date('Y')-1;
		$debugMessage = "";

		//Affichage de la liste des clubs avec leur état actuel de paiemenet de cotisations
		$result = mysql_query("SELECT Cotisations_Clubs.id, annee, ClubsFstb.club, Cotisations_Clubs.idClub, montantBloque, montant, datePaiement FROM Cotisations_Clubs, ClubsFstb WHERE Cotisations_Clubs.idClub=ClubsFstb.nbIdClub AND annee>='".$anneePassee."' ORDER BY annee DESC");
		while ($donnees = mysql_fetch_assoc($result)) {
			$annee = $donnees['annee'];
			$club = $donnees['club'];
			$idClub = $donnees['idClub'];
			$montantBloque = $donnees['montantBloque'] == 1;
			$montant = $donnees['montant'];
			$datePaiement = $donnees['datePaiement'];
			if ($datePaiement != NULL) {
				$anneePaiement = substr($datePaiement,0,4);
				$moisPaiement = substr($datePaiement,5,2);
				$jourPaiement = substr($datePaiement,8,2);
			} else {
				$anneePaiement = date('Y');
				$moisPaiement = date('m');
				$jourPaiement = date('d');
			}
			$montantPaye = $datePaiement != NULL;

			//Gestion du changement d'état de paiement
			if (isset($_POST[$annee.':'.$idClub.':paye'])) {
				$nouveauMontantPaye = $_POST[$annee.':'.$idClub.':paye'] == 1;
				$nouveauAnneePaiement = $_POST[$annee.':'.$idClub.':annee'];
				$nouveauMoisPaiement = $_POST[$annee.':'.$idClub.':mois'];
				$nouveauJourPaiement = $_POST[$annee.':'.$idClub.':jour'];
				if (($nouveauMontantPaye && !$montantPaye) ||
					$nouveauAnneePaiement != $anneePaiement ||
					$nouveauMoisPaiement != $moisPaiement ||
					$nouveauJourPaiement != $jourPaiement) { //Non-payé -> payé OU changement de date
					if (checkdate($nouveauMoisPaiement, $nouveauJourPaiement, $nouveauAnneePaiement)) {
						$requetePaye = "UPDATE Cotisations_Clubs SET datePaiement='".$nouveauAnneePaiement."-".$nouveauMoisPaiement."-".$nouveauJourPaiement."' WHERE annee='".$annee."' AND idClub=".$idClub;
						mysql_query($requetePaye);
						$montantPaye = true;
						$anneePaiement = $nouveauAnneePaiement;
						$moisPaiement = $nouveauMoisPaiement;
						$jourPaiement = $nouveauJourPaiement;
					}
				} elseif (!$nouveauMontantPaye && $montantPaye) { //Payé -> non-payé
					$requeteNonPaye = "UPDATE Cotisations_Clubs SET datePaiement=NULL WHERE annee='".$annee."' AND idClub=".$idClub;
					mysql_query($requeteNonPaye);
					$montantPaye = false;
				}
			}
			?>
			<tr class="<? echo $montantPaye ? "paye" : "nonPaye"; ?>">
				<td><? echo $annee; ?></td>
				<td><? echo $club; ?></td>
				<td><? echo $montantBloque ? $montant." CHF" : "En cours"; ?></td>
				<td>
				<?
					if ($montantBloque) {
						?>
						<select name="<? echo $annee; ?>:<? echo $idClub; ?>:paye">
							<option value="1" <? echo $montantPaye ? "selected='selected'" : ""; ?>>Payé</option>
							<option value="0" <? echo $montantPaye ? "" : "selected='selected'"; ?>>Non-payé</option>
						</select>
						<label> le </label>
						<select name="<? echo $annee; ?>:<? echo $idClub; ?>:jour">
							<?php echo modif_liste_jour($jourPaiement); ?>
						</select>
						<select name="<? echo $annee; ?>:<? echo $idClub; ?>:mois">
							<?php echo modif_liste_mois($moisPaiement); ?>
						</select>
						<select name="<? echo $annee; ?>:<? echo $idClub; ?>:annee">
							<?php echo modif_liste_annee(-1, 0, $anneePaiement); ?>
						</select>
						<?
					} else {
						?>
						Non-payé
						<?
					}

				?>
				</td>
			</tr>
			<?
		}
		?>
	</table>
	<input type="submit" value="Valider" />
</form>
<script>
$(function() {
  // Ici, le DOM est entièrement défini
});
</script>