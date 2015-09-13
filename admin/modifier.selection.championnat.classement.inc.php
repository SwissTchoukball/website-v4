<form name="modifClassementChampionnat" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" onSubmit="return testQqchAModifier();"><table class="tableauModifierClassement">
<?php
	statInsererPageSurf(__FILE__);
	echo "<tr>";
	   echo "<th>X</th>";
	   echo "<th>".VAR_LANG_CHAMPIONNAT."</th>";
	   echo "<th>".VAR_LANG_TOUR."</th>";
	   echo "<th>".VAR_LANG_GROUPE."</th>";
    echo "</tr>";

	$aujourdhui = date_actuelle();

	$requeteSQL =	"SELECT DISTINCT saison, tour, groupe, nomGroupe".$_SESSION["__langue__"]." FROM ChampionnatPtClassementReel, ChampionnatGroupe ".
										"WHERE groupe=idGroupe ".
										"ORDER BY saison DESC, tour, groupe";

	$recordset = mysql_query($requeteSQL);

	echo "<script language='JavaScript'>var nbTourChampionnatAfficher=".mysql_affected_rows()."</script>";
	?>
<script language="JavaScript">
	function testQqchAModifier(){
		var TourChampionnatCoche=false;
		var saison;
		var tour;
		var groupe;
		for(var i=0;i<nbTourChampionnatAfficher && !TourChampionnatCoche;i++){
			if(modifClassementChampionnat.elements[i].checked){
				TourChampionnatCoche=true;
				var reg=new RegExp("[:]+", "g");
				var tableauVal=modifClassementChampionnat.elements[i].value.split(reg);
				saison = tableauVal[0];
				tour = tableauVal[1];
				groupe = tableauVal[2];
			}
		}
		if(!TourChampionnatCoche)alert("Rien à modifier");

		modifClassementChampionnat.saison.value = saison;
		modifClassementChampionnat.tour.value = tour;
		modifClassementChampionnat.idGroupe.value = groupe;
		return TourChampionnatCoche;
	}
</script>
	<?php

	while($record = mysql_fetch_array($recordset)){
        echo "<tr>";
            echo "<td class='center'><input class='couleurRadio' type='radio' name='tour[]' value='".$record['saison'].":".$record['tour'].":".$record['groupe']."' class='couleurCheckBox'></td>";
            echo "<td class='center'>".VAR_LANG_CHAMPIONNAT." ".$record['saison']."-".($record['saison']+1)."</td>";
            if($record["tour"]==1000){
                echo "<td class='center'>".VAR_LANG_TOUR_FINAL."</td>";
                echo "<td class='center'>".$record["nomGroupe".$_SESSION["__langue__"]]."</td>";
            }
            elseif($record["tour"]==500){
                echo "<td colspan='2' class='center'>".VAR_LANG_TOUR_PROMOTION_RELEGATION."</td>";
            }
            else{
                echo "<td class='center'>".VAR_LANG_TOUR." ".$record["tour"]."</td>";
                echo "<td class='center'>".$record["nomGroupe".$_SESSION["__langue__"]]."</td>";
            }
        echo "</tr>";
	}
?>
</table><br>
	<input type="hidden" name="saison" value="">
	<input type="hidden" name="tour" value="">
	<input type="hidden" name="idGroupe" value="">
	<input type="hidden" name="action" value="modifier">
  <p align="center"><input type="submit" name="modifier" value="<?php echo VAR_LANG_MODIFIER;?>"></p>
</form>

