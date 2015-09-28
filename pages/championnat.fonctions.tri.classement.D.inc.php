<?php

function triDifferenceScorePoints($informations,$tableau,$debug){
	$nouveauTableau=array();
	$groupeEgalite=0;
	$annee=$informations['annee'];
	$idCategorie=$informations['idCategorie'];
	$idTour=$informations['idTour'];
	$noGroupe=$informations['noGroupe'];

	for($k=1;$k<=$tableau[0];$k++){
		if(count($tableau[$k])>1){
			if($debug){
				echo "<br /><strong>Il y a une �galit� de points marqu�s interne.</strong><br />";
			}

			$goalaverage=array();
			$ordningEquipesEgalitesPoints=array();
			$ordningEquipesEgalitesId=array();
			$l=0;

			for($i=1;$i<=count($tableau[$k]);$i++){ // Une boucle par �quipe � �galit� ==>> $i = EQUIPE EGALITE
				$goalaverage[$i]=0; // Initialisation des points interne de l'�quipe.
				$requete = "SELECT equipeA, equipeB, pointsA, pointsB FROM Championnat_Matchs WHERE saison=".$annee." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$noGroupe." AND (equipeA=".$tableau[$k][$i]." OR equipeB=".$tableau[$k][$i].") AND (pointsA!=0 AND pointsB!=0)";
				if($debug){
					echo "<br /><br />Tri par goal-average : ".$requete;
				}
				$retour = mysql_query($requete);
				if(mysql_num_rows($retour)==0){// requ�te vide, le nouveauTableau ne bouge pas. Donc s'il y a une �quipe qui n'a pas jou� de match dans le tour. Le classement peut �tre faux.
					$pasChangerClassement=true;
					break 2;
				}
				while($donnees = mysql_fetch_array($retour)){ // Boucle pour chaque match o� les �quipes ont jou�s ensemble dans le tour.
					if($donnees['equipeA']==$tableau[$k][$i]){ // Si l'�quipe s�lectionn�e est equipeA
						$goalaverage[$i]=$goalaverage[$i]+$donnees['pointsA']-$donnees['pointsB'];

					}
					elseif($donnees['equipeB']==$tableau[$k][$i]){ // Si l'�quipe s�lectionn�e est equipeB
						$goalaverage[$i]=$goalaverage[$i]+$donnees['pointsB']-$donnees['pointsA'];
					}
					else{
						echo "<br />ERREUR D2<br />";
					}
				} // Fin boucle chaque match de deux �quipes sp�cifiques
				if($debug){
					echo "<br /><br />ID �quipe ".$tableau[$k][$i]." : Goal-average : ".$goalaverage[$i];
				}

				$ordningEquipesEgalitesPoints[$i]=$goalaverage[$i];
				$ordningEquipesEgalitesId[$i]=$tableau[$k][$i];
				$goalaverage = array();

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
						if($debug){
							echo "<br />nombre de groupes � �galit� : ".$groupeEgalite."<br />";
						}
					}
				}
			}
			$goalaverageAvant=NULL;
		}
		elseif(count($tableau[$k])==1){
			$groupeEgalite++; // Nouveau groupe a �galit�
			$nouveauTableau[$groupeEgalite][1]=$tableau[$k][1];
		}
		else{
			echo "<br /><strong>ERREUR D1</strong><br />";
		}
	}
	$nouveauTableau[0]=$groupeEgalite;
	if($pasChangerClassement){
		$nouveauTableau=$tableau;
	}
	return $nouveauTableau;

}
?>
