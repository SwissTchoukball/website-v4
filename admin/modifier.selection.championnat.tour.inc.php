<form name="modifTourChampionnat" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" onSubmit="return testQqchAModifier();"><table class="tableauModifierPhase">
<?
	statInsererPageSurf(__FILE__);
	echo "<tr>";
	   echo "<th>X</th>";
	   echo "<th>".VAR_LANG_CHAMPIONNAT."</th>";
	   echo "<th>".VAR_LANG_CATEGORIE."</th>";
	   echo "<th>".VAR_LANG_TOUR."</th>";
	   echo "<th>".VAR_LANG_GROUPE."</th>";
    echo "</tr>";
		
	$aujourdhui = date_actuelle();

	$requete =	"SELECT * FROM Championnat_Tours ORDER BY saison DESC, idCategorie, idTour DESC, idGroupe DESC";
		
	$retour = mysql_query($requete);
	
	echo "<script language='JavaScript'>var nbTourChampionnatAfficher=".mysql_affected_rows()."</script>";
	?>
<script language="JavaScript">
	function testQqchAModifier(){
		var TourChampionnatCoche=false;
		var saison;
		var categorie;
		var tour;
		var groupe;				
		for(var i=0;i<nbTourChampionnatAfficher && !TourChampionnatCoche;i++){
			if(modifTourChampionnat.elements[i].checked){
				TourChampionnatCoche=true;
				var reg=new RegExp("[:]+", "g");
				var tableauVal=modifTourChampionnat.elements[i].value.split(reg);
				saison = tableauVal[0];
				categorie = tableauVal[1];
				tour = tableauVal[2];
				groupe = tableauVal[3];
			}
		}
		if(!TourChampionnatCoche)alert("Rien à modifier");
		
		modifTourChampionnat.saison.value = saison;
		modifTourChampionnat.categorie.value = categorie;
		modifTourChampionnat.tour.value = tour;
		modifTourChampionnat.idGroupe.value = groupe;		
		return TourChampionnatCoche;
	}
</script>	
	<?
	
	while($donnees = mysql_fetch_array($retour)){
		echo "<tr>";
		echo "<td class='center'><input class='couleurRadio' type='radio' name='tour[]' value='".$donnees['saison'].":".$donnees['idCategorie'].":".$donnees['idTour'].":".$donnees['idGroupe']."' class='couleurCheckBox'></td>";
		echo "<td class='center'>".VAR_LANG_CHAMPIONNAT." ".$donnees['saison']."-".($donnees['saison']+1)."</td>";
		$requeteA = "SELECT categorie".$_SESSION["__langue__"]." FROM Championnat_Categories WHERE idCategorie=".$donnees['idCategorie']."";
		$retourA = mysql_query($requeteA);
		$donneesA = mysql_fetch_array($retourA);
		if($donnees['idCategorie']==0){ // Promotion / Relegation
			echo "<td colspan='3' class='center'>".$donneesA["categorie".$_SESSION["__langue__"]]."</td>";
		}
		else{
			echo "<td class='center'>".$donneesA["categorie".$_SESSION["__langue__"]]."</td>";
			$requeteB = "SELECT tour".$_SESSION["__langue__"]." FROM Championnat_Types_Tours WHERE idTour=".$donnees['idTour']."";
			$retourB = mysql_query($requeteB);
			$donneesB = mysql_fetch_array($retourB);
			if($donnees["idTour"]==10000 OR $donnees["idTour"]==2000 OR $donnees["idTour"]==3000 OR $donnees["idTour"]==4000){
				echo "<td colspan='2' class='center'>".$donneesB["tour".$_SESSION["__langue__"]]."</td>";
			}
			else{
				echo "<td class='center'>".$donneesB["tour".$_SESSION["__langue__"]]."</td>";
				if($donnees["idGroupe"]==0){
					echo "<td class='center'>Qualifications</td>";
				}		
				else{
					echo "<td class='center'>".VAR_LANG_GROUPE." ".$donnees["idGroupe"]."</td>";	
				}
			}
		}
	}	
?>
</table><br />
	<input type="hidden" name="saison" value="">
	<input type="hidden" name="categorie" value="">
	<input type="hidden" name="tour" value="">
	<input type="hidden" name="idGroupe" value="">	
	<input type="hidden" name="action" value="modifier">
  <p class="center"><input type="submit" name="modifier" value="<? echo VAR_LANG_MODIFIER;?>"></p>
</form>