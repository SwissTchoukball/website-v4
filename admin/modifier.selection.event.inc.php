<form name="modifNews" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" onSubmit="return testQqchAModifier();">
<p align="center"><input type="submit" name="Supprimer" value="<? echo VAR_LANG_MODIFIER;?>"></p><br />
<table class="tableauModifierAgenda">
<?
	echo "<tr>";
	   echo "<th>X</th>";
	   echo "<th>Date début</th>";
	   echo "<th>Date fin</th>";
	   echo "<th>Type</th>";
	   echo "<th>Description</th>";
	   echo "<th>Lieu</th>";
	   echo "<th>Début</th>";
	   echo "<th>Fin</th>";
    echo "</tr>";
		
	$aujourdhui = date_actuelle();

	$requeteSQL = "SELECT * FROM `Agenda_Evenement`, `Agenda_TypeEvent` WHERE 
							 `Agenda_Evenement`.`id_TypeEve` = `Agenda_TypeEvent`.`id_TypeEve` AND
							 `dateDebut` >= '".$aujourdhui."' AND `Agenda_Evenement`.`id_TypeEve`<>'5000' ORDER BY `dateDebut` ASC";
		
	$recordset = mysql_query($requeteSQL);
	
	echo "<script language='JavaScript'>var nbEventAfficher=".mysql_affected_rows()."</script>";
	?>
<script language="JavaScript">
	function testQqchAModifier(){
		var eventCoche=false;
		for(var i=0;i<=nbEventAfficher && !eventCoche;i++){
			if(modifNews.elements[i].checked){
				eventCoche=true;
			}
		}
		if(!eventCoche)alert("Rien à modifier");
		return eventCoche;
	}
</script>	
	<?
	
	while($record = mysql_fetch_array($recordset)){
        if($record["affiche"]==0){
            $style = " style='color:#ffffff;'";
        }
        else{
            $style = "";
        }
        echo "<tr bgcolor='".$record["couleur"]."'".$style.">";
            echo "<td class='center'><input class='couleurRadio' type='radio' name='event[]' value='".$record['NumeroEvenement']."' class='couleurCheckBox'></td>";
            echo "<td class='center'>".date_sql2date($record["dateDebut"])."</td>";
            echo "<td class='center'>".date_sql2date($record["dateFin"])."</td>";
            echo "<td>".$record["nomType"]."</td>";
            echo "<td>".$record["description"]."</td>";
            echo "<td>".$record["lieu"]."</td>";
            echo "<td class='center'>".$record["heureDebut"]."</td>";
            echo "<td class='center'>".$record["heureFin"]."</td>";
		echo "</tr>";
	}
?>
</table><br />
	<input type="hidden" name="action" value="modifier">
  <p align="center"><input type="submit" name="Supprimer" value="<? echo VAR_LANG_MODIFIER;?>"></p>
</form>