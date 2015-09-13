<form name="resultatsChampionnat" action="" method="post"><table border="0" align="center">
      <tr>
        <td><p><?php echo $agenda_annee; ?> :</p></td>
        <td><select name="annee" id="select" onChange="resultatsChampionnat.submit();">
         <?php
         				$annee = $_POST['annee'];
						// recherche de la premiere date
						$requeteAnnee = "SELECT MIN( Agenda_Evenement.dateDebut ) FROM `Agenda_Evenement`";
						$recordset = mysql_query($requeteAnnee) or die ("<h3>Aucune date existe</h3>");
						$dateMin = mysql_fetch_array($recordset) or die ("<h3>erreur extraction</h3>");
						$anneeMin = annee($dateMin[0]);
						$anneeMinAffichee = $anneeMin - annee(date_actuelle());

						// championnat de aout à aout => deux date de différence => il y a deux années.
						$nbChampionnatExistant = -$anneeMinAffichee;

						// si on est en aout, on peut afficher une option en plus pour le nouveau championnat
						if(mois(date_actuelle())>7){
							$nbChampionnatExistant++;
						}

						$anneDebutChampionnat=$anneeMin;
						if($annee == ""){
							$annee = annee(date_actuelle());
							if(mois(date_actuelle())<8){
								$annee--;
							}
						}

						for($i=0;$i<$nbChampionnatExistant;$i++){
							if($annee == $anneDebutChampionnat)
								echo "<option selected value='$anneDebutChampionnat'>".VAR_LANG_CHAMPIONNAT." $anneDebutChampionnat-".($anneDebutChampionnat+1)."</option>";
							else
								echo "<option value='$anneDebutChampionnat'>".VAR_LANG_CHAMPIONNAT." $anneDebutChampionnat-".($anneDebutChampionnat+1)."</option>";
							$anneDebutChampionnat++;
						}
				?></select></td>
      </tr>
    </table>
</form>

<?php

/* Requête pour afficher la liste des ligues */
$requete = "SELECT DISTINCT categorie".$_SESSION['__langue__']." AS nomCategorie FROM Championnat_Equipes_Tours cet, Championnat_Categories cc WHERE saison=".$annee." and cc.idCategorie=cet.idCategorie ORDER BY cc.idCategorie";
if($debug){
	echo "<br /><br />Liste ligues : ".$requete;
}
$retourListeLigues = mysql_query($requete);
echo "<ul class='listeLigues'>";
//boucle pour afficher la liste des ligues en haut de page
while($ligue = mysql_fetch_array($retourListeLigues)){
	$nomCategorie = $ligue['nomCategorie'];
	echo "<li><a href='#".slugify($nomCategorie)."'>".$nomCategorie."</a></li>";
}
echo "</ul>";


// Sélection de la politique des points de l'année choisie.
$retour = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=".$annee."");
$donnees = mysql_fetch_array($retour);
$pointsMatchGagne = $donnees['pointsMatchGagne'];
$pointsMatchNul = $donnees['pointsMatchNul'];
$pointsMatchPerdu = $donnees['pointsMatchPerdu'];
$pointsMatchForfait = $donnees['pointsMatchForfait'];
$nbMatchGagnatPlayoff = $donnees['nbMatchGagnatPlayoff'];
$nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
$nbMatchGagnatPromoReleg = $donnees['nbMatchGagnatPromoReleg'];

$requete = "SELECT * FROM Championnat_Tours WHERE saison=".$annee." ORDER BY idCategorie, idTour DESC, idGroupe";
$retour = mysql_query($requete);
$nbCategories = 0;
//$nbTours = 0; //Apparement inutile, à voir si des disfonctionnements arrivent si je mets la ligne en commentaire
$nbTours = mysql_num_rows($retour);
$nbGroupes = 0;
$tableauCategories = array();
if($nbTours==0){
	printMessage('Planification en cours...');
}
else{
	while($donnees = mysql_fetch_array($retour)){
		$idTour = $donnees['idTour'];

		if($tableauCategories[$donnees['idCategorie']] != oui){ // On vérifie si c'est la première fois que cette catégorie apparaît dans la liste des tours. Si c'est le cas, l'id de la categorie n'appartient pas à $tableauCategories donc on affiche le nom de la categorie.
			$tableauCategories[$donnees['idCategorie']] = oui;
			$requeteA = "SELECT categorie".$_SESSION['__langue__']." FROM Championnat_Categories WHERE idCategorie=".$donnees['idCategorie']."";
			// echo $requeteA;
			$retourA = mysql_query($requeteA);
			$donneesA = mysql_fetch_array($retourA);
			$nomCategorie = $donneesA['categorie'.$_SESSION['__langue__']];
			$idCategorie = $donnees['idCategorie'];
			if($idCategorie != -1){
				echo "<h2 id='".slugify($nomCategorie)."' class='alt'>".$nomCategorie."</h2>";
			}
		}

		if($idTour != 2000){
			if($donnees['idGroupe']<2){
				$retourB = mysql_query("SELECT tour".$_SESSION['__langue__']." FROM Championnat_Types_Tours WHERE idTour=".$donnees['idTour']."");
				$donneesB = mysql_fetch_array($retourB);
				$nomTour = $donneesB['tour'.$_SESSION['__langue__']];
				echo "<h3>".$nomTour."</h3>";
			}
		}
		// $nbTours++; //Apparement inutile, à voir si des disfonctionnements arrivent si je mets la ligne en commentaire
		$requeteC = "SELECT * FROM Championnat_Matchs WHERE saison=".$annee." AND idCategorie=".$donnees['idCategorie']." AND idTour=".$donnees['idTour']." AND noGroupe=".$donnees['idGroupe']." ORDER BY idTypeMatch, journee, dateDebut, heureDebut";
		// echo $requeteC;
		$retourC = mysql_query($requeteC);
		$nbMatchs = mysql_num_rows($retourC);
		$tableauJournees = array();

		if($nbMatchs==0){
			printMessage('Planification en cours...</h5>');
		}
		else{
			if($donnees['idGroupe']!=0){
				echo "<h4>".VAR_LANG_GROUPE." ".$donnees['idGroupe']."</h4>";
			}
			echo "<table class='resultatsTour'>";
			echo "<tr><th class='center'>";
			if($idTour == 10000 OR $idTour == 2000 OR $idTour == 3000 OR $idTour == 4000){
				?>
					Description
				<?php
			}
			else{
				?>
					Journée
				<?php
			}
			?>
					</th>
				    <th class="right">Club recevant</th>
				    <th class="center">Score</th>
				    <th>Club visiteur</th>
				</tr>
			<?php
			while($donneesC = mysql_fetch_array($retourC)){
				if ($donneesC['journee']%2 == 0) {
					$matchClass = 'journeePair';
				} else {
					$matchClass = 'journeeImpair';
				}
				echo "<tr class='".$matchClass."'>";
				echo "<td class='center' height='20px'>";
				if($idTour == 10000 OR $idTour == 2000 OR $idTour == 3000 OR $idTour == 4000){
				$requeteD = "SELECT * FROM Championnat_Types_Matchs WHERE idTypeMatch=".$donneesC['idTypeMatch']."";
				$retourD = mysql_query($requeteD);
				$donneesD = mysql_fetch_array($retourD);
				echo $donneesD['type'.$_SESSION['__langue__']];
				}
				else{
					if($tableauJournees[$donneesC['journee']] != oui){
						$tableauJournees[$donneesC['journee']] = oui;
						if($donneesC['journee'] != 0){
							echo $donneesC['journee'];
						}
					}
				}
				echo "</td>";
				echo "<td class='right'>";
				$requeteD = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$donneesC['equipeA']."";
				$retourD = mysql_query($requeteD);
				$donneesD = mysql_fetch_array($retourD);
				if($donneesC['pointsA'] > $donneesC['pointsB']){
					echo "<strong>";
				}
				echo $donneesD['equipe'];
				if($donneesC['pointsA'] > $donneesC['pointsB']){
					echo "</strong>";
				}
				echo "</td>";
				echo "<td class='center'><a href='/championnat/match/" . $donneesC['idMatch'] . "' title='Détails'>";
				if($donneesC['pointsA'] != 0 OR $donneesC['pointsB'] != 0){
					echo $donneesC['pointsA']." - ".$donneesC['pointsB'];
				}
				elseif($donneesC['reportDebut'] != 0000-00-00){
					echo "Reporté";
				}
				echo "</a></td>";
				echo "<td>";
				$requeteD = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$donneesC['equipeB']."";
				$retourD = mysql_query($requeteD);
				$donneesD = mysql_fetch_array($retourD);
				if($donneesC['pointsA'] < $donneesC['pointsB']){
					echo "<strong>";
				}
				echo $donneesD['equipe'];
				if($donneesC['pointsA'] < $donneesC['pointsB']){
					echo "</strong>";
				}
				echo "</td>";
				echo "</tr>";
			}
		}
		echo "</table>";
	}
}
?>
