<script lang="javascript">

	var couleurErreur;
	couleurErreur='#<?php echo VAR_LOOK_COULEUR_ERREUR_SAISIE; ?>';
	var couleurValide;
	couleurValide='#<?php echo VAR_LOOK_COULEUR_SAISIE_VALIDE; ?>';

	function checkForm(form) {

		var nbError = 0;
		//etat des membres au

		var dateEtatMembreAu = new Date(form.AnneeEtatMembresAu.value,form.MoisEtatMembresAu.value-1,form.JourEtatMembresAu.value,0,0,0);
		if(dateEtatMembreAu.getFullYear() != form.AnneeEtatMembresAu.value || (dateEtatMembreAu.getMonth() != form.MoisEtatMembresAu.value-1) || dateEtatMembreAu.getDate() != form.JourEtatMembresAu.value){
			form.AnneeEtatMembresAu.style.background=couleurErreur;
			form.MoisEtatMembresAu.style.background=couleurErreur;
			form.JourEtatMembresAu.style.background=couleurErreur;
			if(nbError==0)form.MoisEtatMembresAu.focus();
			nbError++;
		}
		else{
			form.AnneeEtatMembresAu.style.background=couleurValide;
			form.MoisEtatMembresAu.style.background=couleurValide;
			form.JourEtatMembresAu.style.background=couleurValide;
		}


		//délai de blocage
		var dateDelaiBloquer = new Date(form.AnneeDelaiBloquer.value,form.MoisDelaiBloquer.value-1,form.JourDelaiBloquer.value,0,0,0);
		if(dateDelaiBloquer.getFullYear() != form.AnneeDelaiBloquer.value || (dateDelaiBloquer.getMonth() != form.MoisDelaiBloquer.value-1) || dateDelaiBloquer.getDate() != form.JourDelaiBloquer.value){
			form.AnneeDelaiBloquer.style.background=couleurErreur;
			form.MoisDelaiBloquer.style.background=couleurErreur;
			form.JourDelaiBloquer.style.background=couleurErreur;
			if(nbError==0)form.MoisDelaiBloquer.focus();
			nbError++;
		}
		else{
			form.AnneeDelaiBloquer.style.background=couleurValide;
			form.MoisDelaiBloquer.style.background=couleurValide;
			form.JourDelaiBloquer.style.background=couleurValide;
		}


		//délai de paiement
		var dateDelaiPayer = new Date(form.AnneeDelaiPayer.value,form.MoisDelaiPayer.value-1,form.JourDelaiPayer.value,0,0,0);
		if(dateDelaiPayer.getFullYear() != form.AnneeDelaiPayer.value || (dateDelaiPayer.getMonth() != form.MoisDelaiPayer.value-1) || dateDelaiPayer.getDate() != form.JourDelaiPayer.value){
			form.AnneeDelaiPayer.style.background=couleurErreur;
			form.MoisDelaiPayer.style.background=couleurErreur;
			form.JourDelaiPayer.style.background=couleurErreur;
			if(nbError==0)form.MoisDelaiPayer.focus();
			nbError++;
		}
		else{
			form.AnneeDelaiPayer.style.background=couleurValide;
			form.MoisDelaiPayer.style.background=couleurValide;
			form.JourDelaiPayer.style.background=couleurValide;
		}

		return nbError==0;
	}



	function selectionAutomatiqueAnnee(form){
		form.AnneeDelaiBloquer.value = form.AnneeEtatMembresAu.value;
		form.AnneeDelaiPayer.value = form.AnneeEtatMembresAu.value;
	}
	function selectionAutomatiqueMois(form){
		form.MoisDelaiBloquer.value = form.MoisEtatMembresAu.value;
		form.MoisDelaiPayer.value = form.MoisEtatMembresAu.value;
	}
</script>

<div id="ouvrirCotisations">

	<?php
	// !Traitement des données d'ouverture de cotisation ---------------------------------------

	if (isset($_POST['annee'])) {
		$nbError = 0;

		$annee = $_POST['annee'];

		// Vérification date d'état des membres
		if (checkdate($_POST['MoisEtatMembresAu'], $_POST['JourEtatMembresAu'], $_POST['AnneeEtatMembresAu'])) {
			$etatMembresAu = $_POST['AnneeEtatMembresAu']."-".$_POST['MoisEtatMembresAu']."-".$_POST['JourEtatMembresAu'];
		} else { //avertissement Javascript
			echo "<p class='error'>Erreur dans la date d'état des membres</p>";
			$nbError++;
		}

		// Vérification délai blocage
		if (checkdate($_POST['MoisDelaiBloquer'], $_POST['JourDelaiBloquer'], $_POST['AnneeDelaiBloquer'])) {
			$delaiBloquer = $_POST['AnneeDelaiBloquer']."-".$_POST['MoisDelaiBloquer']."-".$_POST['JourDelaiBloquer'];
		} else { //avertissement Javascript
			echo "<p class='error'>Erreur dans le délai de blocage</p>";
			$nbError++;
		}

		// Vérification délai paiement
		if (checkdate($_POST['MoisDelaiPayer'], $_POST['JourDelaiPayer'], $_POST['AnneeDelaiPayer'])) {
			$delaiPayer = $_POST['AnneeDelaiPayer']."-".$_POST['MoisDelaiPayer']."-".$_POST['JourDelaiPayer'];
		} else { //avertissement Javascript
			echo "<p class='error'>Erreur dans le délai de paiement</p>";
			$nbError++;
		}

		if($nbError > 0) {
			echo "<p class='error'>Procédure annulée</p>";
		} else {
			if(mysql_query("INSERT INTO Cotisations(annee, etatMembresAu, delaiBloquer, delaiPayer) VALUES ('".$annee."', '".$etatMembresAu."', '".$delaiBloquer."', '".$delaiPayer."')")) {
				$erreurAjoutClub = 0;
				$retourClubsActifs = mysql_query("SELECT nbIdClub FROM ClubsFstb WHERE actif=1 ORDER BY nbIdClub");
				while ($clubActif = mysql_fetch_assoc($retourClubsActifs)) {
					if(!mysql_query("INSERT INTO Cotisations_Clubs(annee, idClub) VALUE ('".$annee."', '".$clubActif['nbIdClub']."')")) {
						$erreurAjoutClub++;
					}
				}
				if ($erreurAjoutClub == 0) {
					echo "<p class='success'>Ouverture des cotisations réussie.</p>";
				} else {
					echo "<p class='error'>Erreur lors de l'ajout des données de cotisation spécifique aux clubs dans la BDD. Ne plus rien faire et contacter le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.</p>";
				}
			} else {
				echo "<p class='error'>Erreur lors de l'ajout des données de cotisation dans la BDD.</p>";
			}
		}
	}



	// !Affichage des dernières cotisations ------------------------------
	$nbAnneesAfficherCotisations = 5;
	?>

	<h4>Cotisations ouvertes durant les <?php echo $nbAnneesAfficherCotisations; ?> dernières années</h4>
	<table>
		<tr>
			<td>Saison</td>
			<td>Etat des membres au</td>
			<td>Délai de blocage du montant</td>
			<td>Délai de paiement</td>
		</tr>
	<?php
	$derniereAnnee = "0";

	$retour = mysql_query("SELECT * FROM Cotisations WHERE annee>'".(date('Y') - $nbAnneesAfficherCotisations)."' ORDER BY annee");
	while($cotisation = mysql_fetch_assoc($retour)) {
		$saison = $cotisation['annee']." - ".($cotisation['annee'] + 1);
		$etatMembresAu = date_sql2date($cotisation['etatMembresAu']);
		$delaiBloquer = date_sql2date($cotisation['delaiBloquer']);
		$delaiPayer = date_sql2date($cotisation['delaiPayer']);
		?>
		<tr>
			<td><?php echo $saison; ?></td>
			<td><?php echo $etatMembresAu; ?></td>
			<td><?php echo $delaiBloquer; ?></td>
			<td><?php echo $delaiPayer; ?></td>
		</tr>
		<?php
		$derniereAnnee = $cotisation['annee'];
	}
	?>
	</table>

	<?php
	// !Formulaire d'ouverture des cotisations ---------------------------------------
	?>

	<h4>Ouvrir des cotisations</h4>
	<form method="POST" onsubmit="return checkForm(this);" name="ouvrirCotisations" action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>">
		<label>Saison</label>
		<select name="annee">
		<?php
		for ($annee = $derniereAnnee + 1; $annee <= $derniereAnnee + 2; $annee++) {
			echo "<option value='".$annee."'>".$annee." - ".($annee + 1)."</option>";
		}
		?>
		</select>
		<br />
		<label>Etat des membres au</label>
		<select name="JourEtatMembresAu">
			<?php echo modif_liste_jour(1); ?>
		</select>.<select name="MoisEtatMembresAu" onchange="selectionAutomatiqueMois(this.form);">
			<?php echo modif_liste_mois(10); ?>
		</select>.<select name="AnneeEtatMembresAu" onchange="selectionAutomatiqueAnnee(this.form);">
			<?php echo modif_liste_annee(-2, 2, $derniereAnnee + 1); ?>
		</select>
		<br />
		<label>Délai de blocage du montant de la cotisation</label>
		<select name="JourDelaiBloquer">
			<?php echo modif_liste_jour(31); ?>
		</select>.<select name="MoisDelaiBloquer">
			<?php echo modif_liste_mois(10); ?>
		</select>.<select name="AnneeDelaiBloquer">
			<?php echo modif_liste_annee(-2, 2, $derniereAnnee + 1); ?>
		</select>
		<br />
		<label>Délai de paiement de la cotisation</label>
		<select name="JourDelaiPayer">
			<?php echo modif_liste_jour(31); ?>
		</select>.<select name="MoisDelaiPayer">
			<?php echo modif_liste_mois(10); ?>
		</select>.<select name="AnneeDelaiPayer">
			<?php echo modif_liste_annee(-2, 2, $derniereAnnee + 1); ?>
		</select>
		<br />
		<input type="submit" value="Ouvrir" />
	</form>
	<h4>Choix des dates</h4>
	<p>Il semble plus adéquat de demander l'état de la liste des membres au 1er octobre. Cela laisse ainsi un petit moment en début de saison aux clubs pour valider les inscriptions de certains membres en phase d'essai.</p>
</div>
