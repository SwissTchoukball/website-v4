<? 

function triScorePointsMarques($informations,$tableau,$debug){
	$nouveauTableau=array();
	$groupeEgalite=0;
	$annee=$informations['annee'];
	$idCategorie=$informations['idCategorie'];
	$idTour=$informations['idTour'];
	$noGroupe=$informations['noGroupe'];

	for($k=1;$k<=$tableau[0];$k++){
		if(count($tableau[$k])>1){
			if($debug){
				echo "<br /><strong>Il y a une égalité de goal-average.</strong><br />";
			}
			
			$pointsMarques=array();
			$ordningEquipesEgalitesPoints=array();
			$ordningEquipesEgalitesId=array();
			$l=0;
			
			for($i=1;$i<=count($tableau[$k]);$i++){ // Une boucle par équipe à égalité ==>> $i = EQUIPE EGALITE
				$pointsMarques[$i]=0; // Initialisation des points interne de l'équipe.
				$requete = "SELECT equipeA, equipeB, pointsA, pointsB FROM Championnat_Matchs WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe." AND (equipeA=".$tableau[$k][$i]." OR equipeB=".$tableau[$k][$i].") AND (pointsA!=0 AND pointsB!=0)";
				if($debug){
					echo "<br /><br />Tri par points marqués : ".$requete;
				}
				$retour = mysql_query($requete);
				if(mysql_num_rows($retour)==0){// requête vide, le nouveauTableau ne bouge pas. Donc s'il y a une équipe qui n'a pas joué de match dans le tour. Le classement peut être faux.
					$pasChangerClassement=true;
					break 2;
				}
				while($donnees = mysql_fetch_array($retour)){ // Boucle pour chaque match où les équipes ont joués ensemble dans le tour.
					if($donnees['equipeA']==$tableau[$k][$i]){ // Si l'équipe sélectionnée est equipeA
						$pointsMarques[$i]=$pointsMarques[$i]+$donnees['pointsA'];
						
					}
					elseif($donnees['equipeB']==$tableau[$k][$i]){ // Si l'équipe sélectionnée est equipeB
						$pointsMarques[$i]=$pointsMarques[$i]+$donnees['pointsB'];
					}
					else{
						echo "<br />ERREUR E2<br />";
					}
				} // Fin boucle chaque match de deux équipes spécifiques
				if($debug){
					echo "<br /><br />".$tableau[$k][$i]." : Points marqués :".$pointsMarques[$i];
				}
				
				$ordningEquipesEgalitesPoints[$i]=$pointsMarques[$i];
				$ordningEquipesEgalitesId[$i]=$tableau[$k][$i];
				$pointsMarques = array();
				
			} // Fin boucle par équipe égalité
			
			for($m=max($ordningEquipesEgalitesPoints);$m>=min($ordningEquipesEgalitesPoints);$m--){
				$compteur=0;
				for($n=1;$n<=count($tableau[$k]);$n++){
					if($ordningEquipesEgalitesPoints[$n]==$m){
						if($compteur==0){
							$groupeEgalite++; // Nouveau groupe a égalité
						}
						$compteur++;
						$nouveauTableau[$groupeEgalite][$compteur]=$ordningEquipesEgalitesId[$n];
					}
				}
			}
			$pointsMarquesAvant=NULL;
		}
		elseif(count($tableau[$k])==1){
			$groupeEgalite++; // Nouveau groupe a égalité
			$nouveauTableau[$groupeEgalite][1]=$tableau[$k][1];
		}
		else{
			echo "<br /><strong>ERREUR E1</strong><br />";
		}
	}
	$nouveauTableau[0]=$groupeEgalite;
	if($pasChangerClassement){
		$nouveauTableau=$tableau;
	}
	return $nouveauTableau;

}
?>