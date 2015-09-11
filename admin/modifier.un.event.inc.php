<?		statInsererPageSurf(__FILE__); 

	// prendre l'evenement a modifier
	list(,$val) = each($event);
	$idNewsCourrante = $val;
	$aujourdhui = date_actuelle();
	
	$requeteSQL = "SELECT * FROM `Agenda_Evenement` WHERE `dateDebut` >= '".$aujourdhui."' AND `Agenda_Evenement`.`NumeroEvenement`='$val' ORDER BY `dateDebut` ASC";
							 
	$recordset = mysql_query($requeteSQL);
	$record = mysql_fetch_array($recordset);
	
	$affiche = $record["affiche"];
	$idTypeEve = $record["id_TypeEve"];	
	$idEvent = $val;
?>

<SCRIPT language='JavaScript'>
// java script pour lier les menus debut-fin

function selectionAutomatiqueAnne(){
	insertion.finAnnee.value = insertion.debutAnnee.value;
}
function selectionAutomatiqueMois(){
	insertion.finMois.value = insertion.debutMois.value;
}
function selectionAutomatiqueJour(){
	insertion.finJour.value = insertion.debutJour.value;
}
function selectionAutomatiqueHeure(){
	insertion.finHeure.value = insertion.debutHeure.value;
}
function selectionAutomatiqueMinute(){
	insertion.finMinute.value = insertion.debutMinute.value;
}
function chronologieDate(){
	dateActuelle = new Date();
	dateDebut = new Date(insertion.debutAnnee.value,
					     insertion.debutMois.value,
						 insertion.debutJour.value,
						 insertion.debutHeure.value,
  					     insertion.debutMinute.value,
						 00);
	dateFin = new Date(insertion.finAnnee.value,
					   insertion.finMois.value,
					   insertion.finJour.value,
					   insertion.finHeure.value,
					   insertion.finMinute.value,
					   00);
	
	if(dateDebut.getTime() > dateFin.getTime()){
		alert('Erreur dans les dates : \n\n' +
			  'début : '+ dateDebut.toLocaleString()+'\n' +
  			  'fin : ' + dateFin.toLocaleString()+'\n');
		return false;
	}
	if(dateActuelle.getTime() > dateDebut.getTime()){
		alert('Erreur dans les dates, vous ne pouvez pas introduire une date dans le passé');
		return false;
	}	
	
	return true;
}

</SCRIPT>
<form name="insertion" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" onSubmit="return chronologieDate();">
  <table width="400" border="0" align="center">
    <tr> 
      <td><p><? echo $agenda_description;?></p></td>
      <td><input name="description" type="text" id="description3" size="70" maxlength="70" value="<? echo $record["description"];?>"></td>
    </tr>
    <tr> 
      <td><p><? echo $agenda_lieu;?></p></td>
      <td><input name="lieu" type="text" id="lieu4" size="50" maxlength="50" value="<? echo $record["lieu"];?>"></td>
    </tr>
    <tr> 
      <td><p><? echo $agenda_debut;?></p></td>
      <td><p><? echo $agenda_date;?> : 
        <select name="debutJour" id="debutJour" onChange="selectionAutomatiqueJour()">
          <? echo modif_liste_jour(jour($record["dateDebut"])); ?> </select> <select name="debutMois" id="debutMois" onChange="selectionAutomatiqueMois()">
          <? echo modif_liste_mois(mois($record["dateDebut"])); ?> </select> <select name="debutAnnee" id="debutAnnee" onChange="selectionAutomatiqueAnne()">
          <? echo modif_liste_annee(0,5,annee($record["dateDebut"])); ?> 
        </select>
        <? echo $agenda_heure;?> : 
        <select name="debutHeure" id="debutHeure" onChange="selectionAutomatiqueHeure()">
          <? echo modif_liste_heure(heure($record["heureDebut"])); ?> </select> <select name="debutMinute" id="debutMinute">
          <? echo modif_liste_minute(minute($record["heureDebut"])); ?> </select></p></td>
    </tr>
    <tr> 
      <td><p><? echo $agenda_fin;?></p></td>
      <td><p><? echo $agenda_date;?> : 
        <select name="finJour" id="finJour">
          <? echo modif_liste_jour(jour($record["dateFin"])); ?> </select> <select name="finMois" id="finMois">
          <? echo modif_liste_mois(mois($record["dateFin"])); ?> </select> <select name="finAnnee" id="finAnnee">
          <? echo modif_liste_annee(0,5,annee($record["dateFin"])); ?> 
        </select>
        <? echo $agenda_heure;?> : 
        <select name="finHeure" id="finHeure">
          <? echo modif_liste_heure(heure($record["heureFin"])); ?> </select> <select name="finMinute" id="finMinute">
          <? echo modif_liste_minute(minute($record["heureFin"])); ?> </select></p></td>
    </tr>
    <tr> 
      <td><p>Type</p></td>
      <td><select name="type" id="type">
 <?
					$requeteSelect = "SELECT * FROM `Agenda_TypeEvent` ORDER BY `nomType` ASC";
					$recordset = mysql_query($requeteSelect) or die ("<H1>mauvaise requete</H1>");					
					while($record = mysql_fetch_array($recordset)){
						$nomType = $record['nomType'];
						$idType = $record['id_TypeEve'];
						//option selectionnee ?
						if($idType == $idTypeEve){
							echo "<option selected  value='$idType'>$nomType</option>";
						}
						else{
							echo "<option value='$idType'>$nomType</option>"; 
						}
					}				  
				  ?>
        </select></td>
    </tr>
    <tr>
        <td><p>Affiché</p></td>
        <td>
            <?
            if($affiche==1){
                $checked = "checked='checked' ";
            }
            else{
                $checked = "";
            }
            ?>
            <input type="checkbox" name="affiche" <? echo $checked; ?>/>
        </td>
    </tr>
  </table>
	 
  <p align="center"> 
    <input name="action" type="hidden" id="action" value="modifierEvent">
		<input name="idEvent" type="hidden" id="idEvent" value="<? echo $idEvent;?>">
    <input type="submit" name="Submit" value="<? echo VAR_LANG_MODIFIER;?>">
  </p>
</form>