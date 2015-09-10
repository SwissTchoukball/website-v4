<? 

function triScorePointsMarquesInterne($informations,$tableau,$debug){
	$nouveauTableau=array();
	$groupeEgalite=0;
	$annee=$informations['annee'];
	$idCategorie=$informations['idCategorie'];
	$idTour=$informations['idTour'];
	$noGroupe=$informations['noGroupe'];

	for($k=1;$k<=$tableau[0];$k++){
		if(count($tableau[$k])>1){
			if($debug){
				echo "<br /><strong>Il y a une �galit� de diff�rence de points(score) interne.</strong><br />";
			}
			
			$pointsMarqueInterne=array();
			$ordningEquipesEgalitesPoints=array();
			$ordningEquipesEgalitesId=array();
			$l=0;
			
			for($i=1;$i<=count($tableau[$k]);$i++){ // Une boucle par �quipe � �galit� ==>> $i = EQUIPE EGALITE
				$pointsMarqueInterne[$i]=0; // Initialisation des points interne de l'�quipe.
				for($j=1;$j<=count($tableau[$k]);$j++){ // Une boucle pour chaque rencontre possible que cette �quipe a fait avec une autre �quipe � �galit�. Le syst�me calcule uniquement les points de l'�quipe s�lectionn�e ==>> $j = AUTRE EQUIPE EGALITE avec qui on compte les points
					if($i!=$j){ // Condition pour qu'on ne cherche pas de match ou �quipe joue avec elle-m�me
						$requete = "SELECT equipeA, equipeB, pointsA, pointsB FROM Championnat_Matchs WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe." AND ((equipeA=".$tableau[$k][$i]." AND equipeB=".$tableau[$k][$j].") OR (equipeA=".$tableau[$k][$j]." AND equipeB=".$tableau[$k][$i].")) AND (pointsA!=0 AND pointsB!=0)";
						if($debug){
							echo "<br /><br />Tri par points marqu�s internes : ".$requete;
						}
						$retour = mysql_query($requete);
						if(mysql_num_rows($retour)==0){// requ�te vide, le nouveauTableau ne bouge pas. Donc s'il y a une �quipe qui n'a pas jou� de match dans le tour. Le classement peut �tre faux.
							$pasChangerClassement=true;
							break 3;
						}
						while($donnees = mysql_fetch_array($retour)){ // Boucle pour chaque match o� les �quipes ont jou�s ensemble dans le tour.
							if($donnees['equipeA']==$tableau[$k][$i]){ // Si l'�quipe s�lectionn�e est equipeA
								$pointsMarqueInterne[$i]=$pointsMarqueInterne[$i]+$donnees['pointsA'];
								
							}
							elseif($donnees['equipeB']==$tableau[$k][$i]){ // Si l'�quipe s�lectionn�e est equipeB
								$pointsMarqueInterne[$i]=$pointsMarqueInterne[$i]+$donnees['pointsB'];
							}
							else{
								echo "<br />ERREUR C2<br />";
							}
						} // Fin boucle chaque match de deux �quipes sp�cifiques
					} // Fin condition �quipe avec elle-m�me
				} // Fin boucle pour chaque rencontre possible
				if($debug){
					echo "<br /><br />".$tableau[$k][$i]." : Points marqu�s Interne :".$pointsMarqueInterne[$i];
				}
				
				$ordningEquipesEgalitesPoints[$i]=$pointsMarqueInterne[$i];
				$ordningEquipesEgalitesId[$i]=$tableau[$k][$i];
				$pointsMarqueInterne = array();
				
			} // Fin boucle par �quipe �galit�
			
			for($m=max($ordningEquipesEgalitesPoints);$m>=min($ordningEquipesEgalitesPoints);$m--){
				$compteur=0;
				for($n=1;$n<=count($tableau[$k]);$n++){
					if($ordningEquipesEgalitesPoints[$n]==$m){
						if($compteur==0){
							$groupeEgalite++; // Nouveau groupe a �galit�
						}
						$compteur++;
						$nouveauTableau[$groupeEgalite][$compteur]=$ordningEquipesEgalitesId[$n];
					}
				}
			}
			$pointsMarqueInterneAvant=NULL;
		}
		elseif(count($tableau[$k])==1){
			$groupeEgalite++; // Nouveau groupe a �galit�
			$nouveauTableau[$groupeEgalite][1]=$tableau[$k][1];
		}
		else{
			echo "<br /><strong>ERREUR C1</strong><br />";
		}
	}
	$nouveauTableau[0]=$groupeEgalite;
	if($pasChangerClassement){
		$nouveauTableau=$tableau;
	}
	return $nouveauTableau;

}
?>