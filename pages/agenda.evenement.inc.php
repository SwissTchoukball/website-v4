
<?php
if(preg_match("#^M#",$_GET['idEvenement'])){ //Championnat
	$idMatch=substr($_GET['idEvenement'],1);
	$requete="SELECT equipeA, equipeB, salle, ville, Championnat_Matchs.dateDebut, Championnat_Matchs.dateFin, heureDebut, heureFin, couleur, Calendrier_Categories.nom AS nomCategorie, Calendrier_Vacances.nom AS nomVacances, Calendrier_Cantons.nom AS nomCanton, Championnat_Saisons.saison, Championnat_Categories.categorie".$_SESSION['__langue__'].", Championnat_Types_Tours.tour".$_SESSION['__langue__'].", noGroupe,  Championnat_Types_Matchs.type".$_SESSION['__langue__'].", Championnat_Types_Matchs.idTypeMatch FROM Championnat_Matchs, Championnat_Saisons, Championnat_Categories, Championnat_Types_Tours, Championnat_Types_Matchs, Calendrier_Categories, Calendrier_Vacances, Calendrier_Cantons WHERE idMatch=".$idMatch." AND Calendrier_Categories.id=4 AND Calendrier_Vacances.dateDebut<=Championnat_Matchs.dateDebut AND Calendrier_Vacances.dateFin>=Championnat_Matchs.dateFin AND Calendrier_Vacances.idCanton=Calendrier_Cantons.id AND Championnat_Matchs.saison=Championnat_Saisons.saison AND Championnat_Matchs.idCategorie=Championnat_Categories.idCategorie AND Championnat_Matchs.idTour=Championnat_Types_Tours.idTour AND Championnat_Matchs.idTypeMatch=Championnat_Types_Matchs.idTypeMatch";

	$requeteEquipes="SELECT * FROM Championnat_Equipes WHERE idEquipe!=11 ORDER BY idEquipe";
	$retourEquipes=mysql_query($requeteEquipes);
	$tableauEquipes=array();
	while($donneesEquipes=mysql_fetch_array($retourEquipes)){
		$tableauEquipes[$donneesEquipes['idEquipe']]=$donneesEquipes['equipe'];
	}
	//lieu, description, dates, heures, titre, couleur, vacances, nomCategorie
}
elseif (is_numeric($_GET['idEvenement'])) {
	$requete="SELECT Calendrier_Evenements.id AS idEvent, titre, description, lieu, jourEntier, couleur, Calendrier_Categories.nom AS nomCategorie, Calendrier_Vacances.nom AS nomVacances, Calendrier_Cantons.nom AS nomCanton, heureDebut, heureFin, Calendrier_Evenements.dateDebut, Calendrier_Evenements.dateFin FROM Calendrier_Evenements, Calendrier_Categories, Calendrier_Vacances, Calendrier_Cantons WHERE Calendrier_Evenements.idCategorie=Calendrier_Categories.id AND Calendrier_Evenements.id=".$_GET['idEvenement']." AND Calendrier_Vacances.dateDebut<=Calendrier_Evenements.dateDebut AND Calendrier_Vacances.dateFin>=Calendrier_Evenements.dateFin AND Calendrier_Vacances.idCanton=Calendrier_Cantons.id";
}
$retour=mysql_query($requete);
$nombreDeVacances=mysql_num_rows($retour);
$premier=true;
while($donnees=mysql_fetch_array($retour)){
	if(preg_match("#^M#",$_GET['idEvenement'])){ // Championnat
		$lieu=$donnees['salle'].", ".$donnees['ville'];
		$titre=$tableauEquipes[$donnees['equipeA']]." - ".$tableauEquipes[$donnees['equipeB']];
		$finSaison=$donnees['saison']+1;
		$saison=$donnees['saison']."-".$finSaison;
		$description=VAR_LANG_SAISON." ".$saison.", ".$donnees['categorie'.$_SESSION['__langue__']].", ".$donnees['tour'.$_SESSION['__langue__']];
		if($donnees['noGroupe']!=0){
			$description.=", ".VAR_LANG_GROUPE." ".$donnees['noGroupe'];
		}
		if($donnees['idTypeMatchs']!=0){
			$description.=", ".$donnees['type'.$_SESSION['__langue__']];
		}
	}
	else{
		$titre=$donnees['titre'];
		$lieu=$donnees['lieu'];
		$description=$donnees['description'];
	}
	if($premier){
		echo "<fieldset id='evenementDetails' style='border-color: #".$donnees['couleur']."'>";
		echo "<legend style='color: #".$donnees['couleur']."'>".$titre."</legend>";
		echo "<br /><div class='donneesEvenement'>";
		if($donnees['dateDebut']==$donnees['dateFin']){
			echo ucfirst(date_sql2date_joli($donnees['dateDebut'],$agenda_le,$_SESSION['__langue__']));
			if($donnees['heureDebut']!=$donnees['heureFin'] && $donnees['jourEntier']==0){
				echo " ".$agenda_de." ".time_sql2heure($donnees['heureDebut'])." ".$agenda_a." ".time_sql2heure($donnees['heureFin']);
			}
		}
		else{
			echo ucfirst(date_sql2date_joli($donnees['dateDebut'],$agenda_du,$_SESSION['__langue__']));
			if($donnees['jourEntier']==0){
				echo " ".$agenda_a." ".time_sql2heure($donnees['heureDebut']);
			}
			echo " jusqu'".date_sql2date_joli($donnees['dateFin'],$agenda_au,$_SESSION['__langue__']);
			if($donnees['jourEntier']==0){
				echo " ".$agenda_a." ".time_sql2heure($donnees['heureFin']);
			}
		}
		echo "</div>";
		echo "<div class='libelleEvenement'>".$agenda_date." : </div><br />";
		echo "<div class='donneesEvenement'>".$lieu."</div>";
		echo "<div class='libelleEvenement'>".$agenda_lieu." : </div><br />";
		echo "<div class='donneesEvenement'>".nl2br($description)."</div>";
		echo "<div class='libelleEvenement'>".$agenda_description." : </div><br /><br /><br /><br /><br /><br />";
		$nomCategorie=$donnees['nomCategorie'];
		$couleur=$donnees['couleur'];
	}
	if($donnees['nomVacances']!="false"){
		if(commenceAvecVoyelle($donnees['nomCanton'])){
			$avantCanton=" dans le canton d'";
		}
		elseif($donnees['nomCanton']=="Jura" OR $donnees['nomCanton']=="Tessin" OR $donnees['nomCanton']=="Valais"){
			$avantCanton=" dans le canton du ";
		}
		elseif($donnees['nomCanton']=="Grisons"){
			$avantCanton=" dans le canton des ";
		}
		elseif($donnees['nomCanton']=="Suisse"){
			$avantCanton=" en ";
		}
		else{
			$avantCanton=" dans le canton de ";
		}
		echo "<div class='donneesEvenement'><strong>".$donnees['nomVacances']."</strong>".$avantCanton.$donnees['nomCanton']."</div>";
	}
	if($nombreDeVacances==1){
		echo "<div class='donneesEvenement'>".VAR_LANG_AUCUNE_VACANCE_PENDANT_EVENEMENT."</div>";
	}
	$premier=false;

	//Valeurs pour lien de retour
	$mois=mois($donnees['dateDebut']);
	$annee=annee($donnees['dateDebut']);
}
echo "<div class='libelleEvenement'>".$agenda_vacances." : </div><br />";
echo "<div class='categorieEvenement' style='color: #".$couleur."'>".$nomCategorie."</div>";
echo "</fieldset>";

$lienRetour="/calendrier/".$annee."/".$mois;
?>
<p><a href="<?php echo $lienRetour; ?>"><?php echo VAR_LANG_RETOUR ?></a></p>
