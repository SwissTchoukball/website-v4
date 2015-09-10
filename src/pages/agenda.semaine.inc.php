<div id="navigationCalendrier">
	<?
	if($jour+7>$nombreJoursMois){
		$jourSuivant=$jour+7-$nombreJoursMois;
		$jourPrecedant=$jour-7;
		$moisSuivant=$mois+1;
		$moisPrecedant=$mois;
		$anneeSuivante=$annee;
		$anneePrecedante=$annee;
		$afficheMoisSuivant=true;

	}
	elseif($jour-7<1){
		$jourPrecedant=$jour-7+$nombreJoursMoisPrecedant;
		$jourSuivant=$jour+7;
		$moisPrecedant=$mois-1;
		$moisSuivant=$mois;
		$anneeSuivante=$annee;
		$anneePrecedante=$annee;
	}
	else{
		$jourSuivant=$jour+7;
		$jourPrecedant=$jour-7;
		$moisSuivant=$mois;
		$moisPrecedant=$mois;
		$anneeSuivante=$annee;
		$anneePrecedante=$annee;
	}


	if($moisSuivant==13){
		$moisSuivant=1;
		$anneeSuivante=$annee+1;
	}
	elseif($moisPrecedant==0){
		$moisPrecedant=12;
		$anneePrecedante=$annee-1;
	}

	if($jour+6==$nombreJoursMois){
		$afficheMoisSuivant=false;
	}
	?>
	<span class="calendrierPrecedant"><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>&affichage=calendrier&semaine&jour=<? echo $jourPrecedant; ?>&mois=<? echo $moisPrecedant; ?>&annee=<? echo $anneePrecedante; ?>#navigationCalendrier" title="Semaine précédante"><img src="pictures/calendrier.precedant.png" alt="Semaine précédante" /></a></span>
	<span class="titreCalendrier">
		<?
		if($afficheMoisSuivant){
			$bonus="- ".ucfirst($moisDeLAnnee[$moisSuivant])." ".$anneeSuivante." ";
		}
		else{
			$bonus="";
		}
		echo ucfirst($moisDeLAnnee[$mois])." ".$annee." ".$bonus."| Semaine ".$numeroSemaine;
		?>
	</span>
	<span class="calendrierSuivant"><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>&affichage=calendrier&semaine&jour=<? echo $jourSuivant; ?>&mois=<? echo $moisSuivant; ?>&annee=<? echo $anneeSuivante; ?>#navigationCalendrier" title="Semaine suivante"><img src="pictures/calendrier.suivant.png" alt="Semaine suivante" /></a></span>
</div>
<table id="calendrierSemaine">
	<?
	for($k=1;$k<=7;$k++){
		echo "<tr>";
			$jourAffiche=$jourPremierJourSemaine+$k-1;
			if($jourAffiche>$nombreJoursMois){ // Si le mois se termine dans le courant de la semaine
				$jourAffiche=$jourAffiche-$nombreJoursMois;
				$mois++;
				if($mois==13){
					$mois=1;
					$annee++;
				}
			}
			if($jourAffiche==date('j') AND $mois==date('n') AND $annee==date('Y')){
				$classDuJour=" class='calendrierAujourdhui'";
			}
			else{
				$classDuJour="";
			}
			echo "<td".$classDuJour.">";
				echo "<div class='jourDeLaSemaine'>";
				if($affichageParJour){
					echo "<a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&affichage=calendrier&jour=".$jourAffiche."&mois=".$mois."&annee=".$annee."'>";
				}
				echo ucfirst($jourDeLaSemaine[$k])." ".$jourAffiche;
				if($affichageParJour){
					echo "</a>";
				}
				echo "</div>";

				$premier=true;
				$triageCategorie=" AND (";
				for($j=1;$j<=$categorieCochee['max'];$j++){
					if($categorieCochee[$j]){
						if(!$premier){
							$triageCategorie.=" OR ";
						}
						$triageCategorie.="idCategorie=".$j;
						$premier=false;
					}

				}
				$triageCategorie.=")";

				$requete="SELECT Calendrier_Evenements.id AS idEvent, titre, jourEntier, couleur, heureDebut, heureFin FROM Calendrier_Evenements, Calendrier_Categories WHERE Calendrier_Evenements.idCategorie=Calendrier_Categories.id".$triageCategorie." AND dateDebut<='".$annee."-".$mois."-".$jourAffiche."' AND dateFin>='".$annee."-".$mois."-".$jourAffiche."' AND visible=1 ORDER BY jourEntier DESC, heureDebut, titre";
				$retour=mysql_query($requete);
				while($donnees=mysql_fetch_array($retour)){
					if($donnees['jourEntier']==1){
						echo "<div class='calendrierSemaineEvenement' style='background-color:#".$donnees['couleur']."; text-align:center;'><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&idEvenement=".$donnees['idEvent']."'><span style='color:white'>".$donnees['titre']."</span></a></div>";
					}
					else{
						echo "<div class='calendrierSemaineEvenement'><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&idEvenement=".$donnees['idEvent']."'><span style='color:#".$donnees['couleur']."'>".time_sql2heure($donnees['heureDebut'])."-".time_sql2heure($donnees['heureFin'])." : ".$donnees['titre']."</span></a></div>";
					}
				}
				if(isset($categorieCochee[4])){ // Championnat{
					$requeteChampionnat="SELECT idMatch, equipeA, equipeB, couleur FROM Championnat_Matchs, Calendrier_Categories WHERE id=4 AND dateDebut<='".$annee."-".$mois."-".$jourAffiche."' AND dateFin>='".$annee."-".$mois."-".$jourAffiche."' ORDER BY heureDebut";
					$retourChampionnat=mysql_query($requeteChampionnat);
					while($donneesChampionnat=mysql_fetch_array($retourChampionnat)){
						echo "<div class='calendrierSemaineEvenement'><a href='index.php?lien=22&matchID=".$donneesChampionnat['idMatch']."'><span style='color:#".$donneesChampionnat['couleur']."'>&#149; ".$tableauEquipes[$donneesChampionnat['equipeA']]." - ".$tableauEquipes[$donneesChampionnat['equipeB']]."</span></a></div>";
					}
				}

			echo "</td>";
		echo "</tr>";
	}
	?>
</table>