<?php
	/**
		Affichage de l'agenda pour le championnat
	*/
	include "includes/agenda.utility.inc.php";
?>

<form name="affichage" method="post" action="">
<table class="formagenda">
	<tr>
		<td align="right" width="50%"><p><?php echo $agenda_annee;?> :</p></td>
		<td align="left"> <select name="annee" id="select">
         <?php
					// recherche de la premiere date
					$requeteAnnee = "SELECT MIN( Agenda_Evenement.dateDebut ) FROM `Agenda_Evenement`";
					$recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
					$dateMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");
					$anneeMin = annee($dateMin[0]);
					$anneeMinAffichee = $anneeMin - annee(date_actuelle());

					// pas plus de trois ans.
					if($anneeMinAffichee < -2){
						$anneeMinAffichee = -2;
					}
					// valeur initialisee ?
					if($annee==""){
						// non alors on créer la liste sans selection
						echo "<option selected value=Avenir>A venir</option>";
						echo creation_liste_annee($anneeMinAffichee,2);
						$annee = "Avenir";
					}
					else{
						// faut-il cocher la premiere option
						if($annee=="Avenir"){
							echo "<option selected value=Avenir>A venir</option>";
						}
						else{
							echo "<option value=Avenir>A venir</option>";
						}
						// oui, il faut selectionner la bonne date
						echo modif_liste_annee($anneeMinAffichee,2,$annee);
					}
				?></select>
		</td>
	</tr>
	<tr>
		<td align="right"><p><?php echo $agenda_categories;?> :</p></td>
		<td align="left"><select name="categorie" id="select2">
        <?php
					// valeur initialisee ?
					if($categorie==""){

						echo "<option value=Toutes>Toutes</option>";
						// non alors on créer la liste sans selection
						echo creation_liste_categorie();
						$categorie = "Toutes";
					}
					else{
						// faut-il cocher la premiere option
						if($annee=="Toutes"){
							echo "<option selected value=Toutes>Toutes</option>";
						}
						else{
							echo "<option value=Toutes>Toutes</option>";
						}
						// oui, il faut selectionner la bonne date
						echo modif_liste_categorie($categorie);
					}
				?>
				</select>
       </td>
	</tr>
	<tr>
			<td align="center" colspan="2">
					<input type="submit" name="Submit" value="<?php echo $agenda_actualiser; ?>">
			</td>
	</tr>
</table>
</form>

<?php

	// affichage des dates
	if($annee == "Avenir"){
		$annee = date_actuelle();
	}
	// date de championnat
	else{
		$annee = "$annee-00-00";
		$jusqua = $annee+1;
		$jusqua .= "-00-00";
	}

    // Création du tableau pour les équipes
    $tableauEquipes = array();
    $requeteEquipes = "SELECT equipe, idEquipe FROM Championnat_Equipes";
    $retourEquipes = mysql_query($requeteEquipes);
    while($donneesEquipes = mysql_fetch_array($retourEquipes)){
        $tableauEquipes[$donneesEquipes['idEquipe']] = $donneesEquipes['equipe'];
    }


	if($categorie == "Toutes"){
		/*$requeteSelect = "SELECT * FROM `Agenda_Evenement`, `Agenda_TypeEvent` WHERE
						`Agenda_Evenement`.`id_TypeEve` = `Agenda_TypeEvent`.`id_TypeEve` AND
						(`dateDebut` >= '".$annee."' OR `dateFin` >= '".$annee."')";*/
        $requeteAgenda = "SELECT couleur, nomType, Agenda_Evenement.id_TypeEve, affiche, description AS equipeA, '' AS equipeB, '' AS salle, lieu AS ville, dateDebut, dateFin, heureDebut, heureFin FROM Agenda_Evenement, Agenda_TypeEvent WHERE Agenda_Evenement.id_TypeEve=Agenda_TypeEvent.id_TypeEve AND Agenda_Evenement.affiche=1 AND Agenda_Evenement.id_TypeEve!=5000 AND (`dateDebut` >= '".$annee."' OR `dateFin` >= '".$annee."')";
        $requeteChampionnat ="SELECT couleur, Championnat_Types_Matchs.Type".$_SESSION['__langue__']." AS nomType, 5000 AS id_TypeEve, equipeA, equipeB, salle, ville, dateDebut, dateFin, heureDebut, heureFin FROM Championnat_Matchs, Agenda_TypeEvent, Championnat_Types_Matchs WHERE Championnat_Types_Matchs.idTypeMatch=Championnat_Matchs.idTypeMatch AND 5000=Agenda_TypeEvent.id_TypeEve AND (`dateDebut` >= '".$annee."' OR `dateFin` >= '".$annee."')";

		if(isset($jusqua)){
			/*$requeteSelect .= " AND (`dateDebut` <= '".$jusqua."' OR `dateFin` <= '".$jusqua."')";*/
			$requeteAgenda .= " AND (`dateDebut` <= '".$jusqua."' OR `dateFin` <= '".$jusqua."')";
			$requeteChampionnat .= " AND (`dateDebut` <= '".$jusqua."' OR `dateFin` <= '".$jusqua."')";
		}
        /*$requeteAgenda .= " ORDER BY `dateDebut` ASC";
        $requeteChampionnat .= " ORDER BY `dateDebut` ASC";*/

        // Plus besoin de mixer Agenda et Championnat par défaut, car on veut plus
        // $requeteSelect = "(".$requeteAgenda.") UNION ALL(".$requeteChampionnat.")";

        $requeteSelect = $requeteAgenda;

        $requeteSelect .= " ORDER BY `dateDebut` ASC, 'heureDebut' ASC";
	}
	else{

		$sousRequete = "SELECT `Agenda_Evenement_Categorie`.`id_TypeEve` FROM `Agenda_Evenement_Categorie` WHERE
									`Agenda_Evenement_Categorie`.`id_Categorie` = $categorie";
		//echo "<BR>Voici la sousRequete : $sousRequete<BR>";
		$recordSetId_TypeEve = mysql_query($sousRequete) or die ("<H3>Cette catégorie n'existe pas.</H3>");
		//$recordset = mysql_fetch_array($recordSetId_TypeEve);
		$valeursIN = "";
		while($record = mysql_fetch_array($recordSetId_TypeEve)){
			$eve = $record["id_TypeEve"];
			$valeursIN .= $eve.",";
		}
		if($valeursIN != ""){
			$valeursIN = substr($valeursIN,0,strlen($valeursIN)-1);
		}

		/*$requeteSelect = "SELECT * FROM `Agenda_Evenement`, `Agenda_TypeEvent` WHERE
						`Agenda_Evenement`.`id_TypeEve` = `Agenda_TypeEvent`.`id_TypeEve` AND
						(`dateDebut` >= '".$annee."' OR `dateFin` >= '".$annee."')";*/
        $requeteAgenda = "SELECT couleur, nomType, Agenda_Evenement.id_TypeEve, description AS equipeA, '' AS equipeB, '' AS salle, lieu AS ville, dateDebut, dateFin, heureDebut, heureFin FROM Agenda_Evenement, Agenda_TypeEvent WHERE Agenda_Evenement.id_TypeEve=Agenda_TypeEvent.id_TypeEve AND Agenda_Evenement.affiche=1 AND (`dateDebut` >= '".$annee."' OR `dateFin` >= '".$annee."')";
        $requeteChampionnat ="SELECT couleur, Championnat_Types_Matchs.Type".$_SESSION['__langue__']." AS nomType, 5000 AS id_TypeEve, equipeA, equipeB, salle, ville, dateDebut, dateFin, heureDebut, heureFin FROM Championnat_Matchs, Agenda_TypeEvent, Championnat_Types_Matchs WHERE Championnat_Types_Matchs.idTypeMatch=Championnat_Matchs.idTypeMatch AND 5000=Agenda_TypeEvent.id_TypeEve AND (`dateDebut` >= '".$annee."' OR `dateFin` >= '".$annee."')";

		if(isset($jusqua)){
			/*$requeteSelect .= " AND (`dateDebut` <= '".$jusqua."' OR `dateFin` <= '".$jusqua."')";*/
			$requeteAgenda .= " AND (`dateDebut` <= '".$jusqua."' OR `dateFin` <= '".$jusqua."')";
			$requeteChampionnat .= " AND (`dateDebut` <= '".$jusqua."' OR `dateFin` <= '".$jusqua."')";
		}
		/*$requeteSelect .= " AND `Agenda_TypeEvent`.`id_TypeEve` IN ($valeursIN)";	*/
        $requeteAgenda .= " AND `Agenda_TypeEvent`.`id_TypeEve` IN ($valeursIN)";
        $requeteChampionnat .= " AND `Agenda_TypeEvent`.`id_TypeEve` IN ($valeursIN)";
		/*$requeteAgenda .= " ORDER BY `dateDebut` ASC, 'heureDebut' ASC";
        $requeteChampionnat .= " ORDER BY `dateDebut` ASC, 'heureDebut' ASC";*/

        $requeteSelect = "(".$requeteAgenda.") UNION ALL (".$requeteChampionnat.")";
        $requeteSelect .= " ORDER BY `dateDebut` ASC, 'heureDebut' ASC";
	}

	$recordset = mysql_query($requeteSelect) or die ("<H3>Cette catégorie n'existe pas.</H3>");
	?>

<p class="center">Les dates des matchs de championnat ne sont pas affichées par défaut. Séléctionnez "Championnat" dans la liste déroulante "Catégories"</a></p>
<table class="agenda" >
    <tr>
        <th width="60px"><?php echo $agenda_du; ?></th>
        <th width="60px"><?php echo $agenda_au; ?></th>
        <th><?php echo $agenda_type; ?></th>
        <th><?php echo $agenda_description; ?></th>
        <th><?php echo $agenda_lieu; ?></th>
        <th width="40px"><?php echo $agenda_debut; ?></th>
        <th width="40px"><?php echo $agenda_fin; ?></th>
    </tr>
	<?php

	// afficher le resultat de la requete
	while($record = mysql_fetch_array($recordset)){
		$couleur = $record['couleur'];
		$dateDebut = date_sql2date($record['dateDebut']);
		$heureDebut = $record['heureDebut'];
		$dateFin = date_sql2date($record['dateFin']);
		$heureFin = $record['heureFin'];
		$nomType = $record['nomType'];
		if($record['equipeB'] == ''){
		  $description = $record['equipeA'];
		}
		else{
		  $description = $tableauEquipes[$record['equipeA']]."-".$tableauEquipes[$record['equipeB']];
		}
		if($record['salle'] == ''){
		  $lieu = $record['ville'];
		}
		else{
		  $lieu = $record['salle'].", ".$record['ville'];
		}
		$utilisateur = $record['utilisateur'];
		// $idEvent = $record['NumeroEvenement'];

		if($description == "") { $description = "&nbsp;"; }
		if($lieu == "") { $lieu = "&nbsp;"; }
        ?>
		<tr bgcolor="#<?php echo $couleur; ?>">
			<td align="center"><?php echo $dateDebut; ?></td>
			<td align="center"><?php echo $dateFin; ?></td>
			<td><?php echo $nomType; ?></td>
			<td><?php echo $description; ?></td>
			<td><?php echo $lieu; ?></td>
			<td align="center"><?php echo heure($heureDebut); ?>:<?php echo minute($heureDebut); ?></td>
			<td align="center"><?php echo heure($heureFin); ?>:<?php echo minute($heureFin); ?></td>
		</tr>
        <?php
    }
    ?>
    <tr>
        <th><?php echo $agenda_du;?></th>
        <th><?php echo $agenda_au;?></th>
        <th><?php echo $agenda_type;?></th>
        <th><?php echo $agenda_description;?></th>
        <th><?php echo $agenda_lieu;?></th>
        <th><?php echo $agenda_debut;?></th>
        <th><?php echo $agenda_fin;?></th>
    </tr>
</table>
<p class="center">Les dates des matchs de championnat ne sont pas affichées par défaut. Séléctionnez "Championnat" dans la liste déroulante "Catégories"</a></p>
