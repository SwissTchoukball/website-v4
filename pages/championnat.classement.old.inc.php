<div class="classement">
<form name="classementChampionnat" action="" method="post"><table border="0" align="center">
      <tr>
        <td><p><?php echo $agenda_annee; ?> :</p></td>
        <td><select name="annee" id="select" onChange="classementChampionnat.submit();">
         <?php
						// recherche de la premiere date
						$requeteAnnee = "SELECT MIN( Agenda_Evenement.dateDebut ) FROM `Agenda_Evenement`";
						$recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
						$dateMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");
						$anneeMin = annee($dateMin[0]);
						$anneeMinAffichee = $anneeMin - annee(date_actuelle());

						// championnat de aout à aout => deux date de différence => il y a deux années.
						$nbChampionnatExistant = -$anneeMinAffichee;

						// si on est en aout, on peut afficher une option en plus pour le nouveau championnat
						if(mois(date_actuelle())>7){
							$nbChampionnatExistant++;
						}

						$anneeDebutChampionnat=$anneeMin;
						if($annee == ""){
							$annee = annee(date_actuelle());
							if(mois(date_actuelle())<8){
								$annee--;
							}
						}

						for($i=0;$i<$nbChampionnatExistant;$i++){
							if($annee == $anneeDebutChampionnat)
								echo "<option selected value='$anneeDebutChampionnat'>".VAR_LANG_CHAMPIONNAT." $anneeDebutChampionnat-".($anneeDebutChampionnat+1)."</option>";
							else
								echo "<option value='$anneeDebutChampionnat'>".VAR_LANG_CHAMPIONNAT." $anneeDebutChampionnat-".($anneeDebutChampionnat+1)."</option>";
							$anneeDebutChampionnat++;
						}

                        $couleurLigneA = "none";
                        $couleurLigneB = "none";

				?></select></td>
      </tr>
    </table></form>

		<?php
			// Sélection de la politique des points de l'année choisie.
			$retour = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=".$annee."");
			$donnees = mysql_fetch_array($retour);
			$pointsMatchGagne = $donnees['pointsMatchGagne'];
			$pointsMatchNul = $donnees['pointsMatchNul'];
			$pointsMatchPerdu = $donnees['pointsMatchPerdu'];
			$pointsMatchForfait = $donnees['pointsMatchForfait'];
			$nbMatchGagnantTourFinal = $donnees['nbMatchGagnantTourFinal'];
			$nbMatchGagnantPlayoff = $donnees['nbMatchGagnantPlayoff'];
			$nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
			$nbMatchGagnantPromoReleg = $donnees['nbMatchGagnantPromoReleg'];

			// prendre tous les tours de l'annee choisie
			$retour = mysql_query("SELECT * FROM Championnat_Tours WHERE saison=".$annee." ORDER BY idCategorie, idTour DESC, idGroupe");
			$nbCategories = 0;
			$nbTours = 0;
			$nbGroupes = 0;
			$tableauCategories = array();
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
						echo "<h3>".$nomCategorie."</h3>";
					}
				}

				if($idTour != 2000){
					if($donnees['idGroupe']<2){
						$retourB = mysql_query("SELECT tour".$_SESSION['__langue__']." FROM Championnat_Types_Tours WHERE idTour=".$donnees['idTour']."");
						$donneesB = mysql_fetch_array($retourB);
						$nomTour = $donneesB['tour'.$_SESSION['__langue__']];
						echo "<h4>".$nomTour."</h4><br />";
						$compteurPosition = 0;
					}
				}
				$nbTours++;
				if($donnees['idGroupe']!=0){
					echo "<h5>".VAR_LANG_GROUPE." ".$donnees['idGroupe']."</h5>";
				}

					if(($idTour == 4000 AND $nbMatchGagnantPlayoff>1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout>1) OR ($idTour == 2000 AND $nbMatchGagnantPromoReleg>1) OR ($idTour == 10000 AND $nbMatchGagnantTourFinal>1)) { // Play-off, Play-out, Promotion/Relegation ou tour final de + de 1 match
						echo "<table class='classementTour'>";
						?>
						<tr>
							<th>
							<?php
							if($idTour == 2000){
								echo "Promu";
							}
							else{
								echo "Position";
							}
							?>
							</th>
						   <th>Equipes</th>
						   <th>Joué</th>
						   <th>Gagné</th>
						   <th>Perdu</th>
						   <th>Forfait</th>
						   <th>Marqué</th>
						   <th>Reçu</th>
						   <th>Diff.</th>
					    </tr>
					    <?php
					    $requeteC = "SELECT DISTINCT Championnat_Matchs.saison, Championnat_Matchs.idCategorie, Championnat_Matchs.idTour, Championnat_Matchs.noGroupe, Championnat_Equipes_Tours.idEquipe, idTypeMatch, points, goolaverage, nbMatchJoue, nbMatchGagne, nbMatchPerdu, nbMatchForfait, nbPointMarque, nbPointRecu, equipe
						FROM Championnat_Equipes_Tours, Championnat_Matchs, Championnat_Equipes
						WHERE Championnat_Equipes_Tours.saison =".$annee."
						AND Championnat_Matchs.saison =".$annee."
						AND Championnat_Equipes_Tours.idCategorie =".$idCategorie."
						AND Championnat_Matchs.idCategorie =".$idCategorie."
						AND Championnat_Equipes_Tours.idTour =".$idTour."
						AND Championnat_Matchs.idTour =".$idTour."
						AND Championnat_Equipes_Tours.noGroupe =".$donnees['idGroupe']."
						AND Championnat_Matchs.noGroupe =".$donnees['idGroupe']."
						AND (
						equipeA = Championnat_Equipes_Tours.idEquipe
						OR equipeB = Championnat_Equipes_Tours.idEquipe
						)
						AND Championnat_Equipes.idEquipe = Championnat_Equipes_Tours.idEquipe
						ORDER BY Championnat_Matchs.idTypeMatch, nbMatchGagne DESC, Championnat_Equipes_Tours.points DESC , Championnat_Equipes_Tours.goolaverage DESC
						LIMIT 0 , 30";
						$retourC = mysql_query($requeteC);
						$i=1;
						while($donneesC = mysql_fetch_array($retourC)){
							$idTypeMatch=$donneesC['idTypeMatch'];
							$nbMatchGagne=$donneesC['nbMatchGagne'];
							$nbMatchPerdu=$donneesC['nbMatchPerdu'];
							$nbMatchJoue=$donneesC['nbMatchJoue'];
							$nbMatchForfait=$donneesC['nbMatchForfait'];
							$nbPointMarque=$donneesC['nbPointMarque'];
							$nbPointRecu=$donneesC['nbPointRecu'];
							$goolaverage=$donneesC['goolaverage'];
							$equipe=$donneesC['equipe'];
							$compteurPosition++;
							if($i%2==0){
                                $style = "background-color:".$couleurLigneA.";";
							}
							else{
                                $style = "background-color:".$couleurLigneB.";";
							}
							echo "<tr style='".$style."'>";
							if($idTour == 4000){
								$nbMatchGagnant = $nbMatchGagnantPlayoff;
							}
							elseif($idTour == 3000){
								$nbMatchGagnant = $nbMatchGagnantPlayout;
							}
							elseif($idTour == 2000){
								$nbMatchGagnant = $nbMatchGagnantPromoReleg;
							}
							elseif($idTour == 10000){
								$nbMatchGagnant = $nbMatchGagnantTourFinal;
							}
							if($idTour == 2000 AND $nbMatchGagne==$nbMatchGagnant){
								$gagnant = oui;
							}
							elseif($idTour == 2000 AND $nbMatchPerdu==$nbMatchGagnant){
								$gagnant = non;
							}
							else{
								//$requeteE = "SELECT * FROM Championnat_Matchs WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$donnees['idGroupe']." AND (equipeA=".$donneesC["idEquipe"]." OR equipeB=".$donneesC["idEquipe"].") LIMIT 0,1";
								// echo $requeteE;
								//$retourE = mysql_query($requeteE);
								//$donneesE = mysql_fetch_array($retourE);
								if($idTypeMatch==1 AND $nbMatchGagne==$nbMatchGagnant){
									$gagnant = "1er";
								}
								elseif($idTypeMatch==1 AND $nbMatchPerdu==$nbMatchGagnant){
									$gagnant = "2ème";
								}
								elseif($idTypeMatch==2 AND $nbMatchGagne==$nbMatchGagnant){
									$gagnant = "3ème";
								}
								elseif($idTypeMatch==2 AND $nbMatchPerdu==$nbMatchGagnant){
									$gagnant = "4ème";
								}
								elseif($idTypeMatch==3 AND $nbMatchGagne==$nbMatchGagnant){
									$gagnant = "5ème";
								}
								elseif($idTypeMatch==3 AND $nbMatchPerdu==$nbMatchGagnant){
									$gagnant = "6ème";
								}
								elseif($idTypeMatch==4 AND $nbMatchGagne==$nbMatchGagnant){
									$gagnant = "7ème";
								}
								elseif($idTypeMatch==4 AND $nbMatchPerdu==$nbMatchGagnant){
									$gagnant = "8ème";
								}
								elseif($idTypeMatch==2000 AND $nbMatchGagne==$nbMatchGagnant){
									$gagnant = "Passe en finale";
								}
								elseif($idTypeMatch==2000 AND $nbMatchPerdu==$nbMatchGagnant){
									$gagnant = "Passe en petite finale";
								}
								else{
									$gagnant = "";
								}
							}
							echo "<td><p align='center'>".$gagnant."</p></td>";
							echo "<td><p>".$equipe."</p></td>";
							echo "<td><p>".$nbMatchJoue."</p></td>";
							echo "<td><p>".$nbMatchGagne."</p></td>";
							echo "<td><p>".$nbMatchPerdu."</p></td>";
							echo "<td><p>".$nbMatchForfait."</p></td>";
							echo "<td><p>".$nbPointMarque."</p></td>";
							echo "<td><p>".$nbPointRecu."</p></td>";
							echo "<td><p align='right'>".$goolaverage."</p></td>";
							echo "</tr>";

							$i++;
						}
					}
					elseif(($idTour == 4000 AND $nbMatchGagnantPlayoff==1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout==1) OR ($idTour == 2000 AND $nbMatchGagnantPromoReleg==1) OR ($idTour == 10000 AND $nbMatchGagnantTourFinal==1)){ // Play-off, Play-out, Promotion/Relegation, tour final de 1 match
						echo "<table class='classementTourFinal'>";
						?>
						<tr>
							<th>
							<?php
							if($idTour == 2000){
								echo "Promu";
							}
							else{
								echo "Position";
							}
							?>
							</th>
							<th>Equipes</th>
					    </tr>
					    <?php
						$retourC = mysql_query("SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$donnees['idGroupe']." ORDER BY position, points DESC, goolaverage DESC");
						$i=1;
						while($donneesC = mysql_fetch_array($retourC)){
							$compteurPosition++;
							if($i%2==0){
                                $style = "background-color:".$couleurLigneA.";";
							}
							else{
                                $style = "background-color:".$couleurLigneB.";";
							}
							echo "<tr style='".$style."'>";
							$nbMatchGagnant = 1;
							if($idTour == 2000 AND $donneesC['nbMatchGagne']==$nbMatchGagnant){ // Match de Promo / Releg gagné
								$gagnant = oui;
							}
							elseif($idTour == 2000 AND $donneesC['nbMatchPerdu']==$nbMatchGagnant){ // Match de Promo / Releg perdu
								$gagnant = non;
							}
							elseif($donneesC['position']==2000 AND $donneesC['nbMatchGagne']==$nbMatchGagnant){ // Match de demi-finale gagné
								$gagnant = "Passe en finale";
							}
							elseif($donneesC['position']==2000 AND $donneesC['nbMatchPerdu']==$nbMatchGagnant){ // Match de demi-finale perdu
									$gagnant = "Passe en petite finale";
							}
							elseif($donneesC['position']!=0){
								if($donneesC['position']==1){
									$gagnant = $compteurPosition."er";
								}
								else{
									$gagnant = $compteurPosition."ème";
								}
							}
							else{ // Pas de position accordé
								$gagnant = "N/A";
							}
							echo "<td><p align='center'>".$gagnant."</p></td>";
							$retourD = mysql_query("SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$donneesC['idEquipe']."");
							$donneesD = mysql_fetch_array($retourD);
							echo "<td><p>".$donneesD["equipe"]."</p></td>";
							echo "</tr>";

							$i++;
						}

					}
					else{ // Tour normal
						echo "<table class='classementTour'>";
						?>
						<tr>
						   <th>Position</th>
						   <th>Equipes</th>
						   <th>Joué</th>
						   <th>Gagné</th>
						   <th>Nul</th>
						   <th>Perdu</th>
						   <th>Forfait</th>
						   <th>Marqué</th>
						   <th>Reçu</th>
						   <th>Diff.</th>
						   <th>Points</th>
					    </tr>
						<?php
						$requeteC = "SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$donnees['idGroupe']." ORDER BY points DESC, position, goolaverage DESC";
						// echo $requeteC;
						$retourC = mysql_query($requeteC);
						$i=1;
						while($donneesC = mysql_fetch_array($retourC)){
							$compteurPosition++;
							if($i%2==0){
                                $style = "background-color:".$couleurLigneA.";";
							}
							else{
                                $style = "background-color:".$couleurLigneB.";";
							}
							echo "<tr style='".$style."'>";
							echo "<td><p align='center'>";
							if($compteurPosition==1){
								echo $compteurPosition."er";
							}
							else{
								echo $compteurPosition."ème";
							}
							echo "</p></td>";
							$retourD = mysql_query("SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$donneesC['idEquipe']."");
							$donneesD = mysql_fetch_array($retourD);
							$nbMatchJoue = $donneesC['nbMatchGagne']+$donneesC['nbMatchPerdu']+$donneesC['nbMatchForfait']+$donneesC['nbMatchNul'];
							echo "<td><p>".$donneesD["equipe"]."</p></td>";
							echo "<td><p>".$nbMatchJoue."</p></td>";
							echo "<td><p>".$donneesC['nbMatchGagne']."</p></td>";
							echo "<td><p>".$donneesC['nbMatchNul']."</p></td>";
							echo "<td><p>".$donneesC['nbMatchPerdu']."</p></td>";
							echo "<td><p>".$donneesC['nbMatchForfait']."</p></td>";
							echo "<td><p>".$donneesC['nbPointMarque']."</p></td>";
							echo "<td><p>".$donneesC['nbPointRecu']."</p></td>";
							$goolaverage = $donneesC['nbPointMarque']-$donneesC['nbPointRecu'];
							echo "<td><p>".$goolaverage."</p></td>";
							echo "<td><p>".$donneesC['points']."</p></td>";
							echo "</tr>";

							$i++;
						}
					}

					echo "</table>";
			} // fin boucle while tours


		?>
</div>
