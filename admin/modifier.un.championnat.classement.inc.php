<?
	statInsererPageSurf(__FILE__);
?>

<script language="javascript">
	
	function validateForm(){
		
		var nbErreur=0;
		for(i=0;i<nbParticipant;i++){
				var textbox = document.getElementById("joue_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
				textbox = document.getElementById("gagne_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
				textbox = document.getElementById("nul_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
				textbox = document.getElementById("perdu_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
				textbox = document.getElementById("forfait_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
				textbox = document.getElementById("marque_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
				textbox = document.getElementById("encaisse_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
				textbox = document.getElementById("point_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;	
				textbox = document.getElementById("position_"+i);
				if(isNaN(textbox.value)){
					textbox.style.background=couleurErreur;
				 	nbErreur++;
				}
				else textbox.style.background=couleurValide;
		}
		
		if(nbErreur > 0){
			alert("Certaines valeurs saisies ne sont pas des nombres valides");
			return false;
		}
		else{
			return true;
		}
	}
</script>

<?
	echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';
	 var couleurValide; couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';
	 </SCRIPT>";
?>

<form name="modifierClassement" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
    <table width="100%" border="0" align="center" id="test">
    <tr>
      <td><p>&nbsp;</p>
						
		<table class="tableauTourChampionnat" id="tableClassement">
			<tr>
			  <th>Equipes</th>
			  <th>Jou&eacute;</th>
			  <th>Gagn&eacute;</th>
			  <th>Nul</th>
			  <th>Perdu</th>
			  <th>Forfait</th>
			  <th>Marqu&eacute;</th>
			  <th>Encaiss&eacute;</th>
			  <th>Point</th>
			  <th>Position</th>
			</tr>
			<?
				$saison = $_POST["saison"];
				$tour = $_POST["tour"];
				$groupe = $_POST["idGroupe"];		
						
				$requeteSQL = "SELECT *, nbPointMarque-nbPointRecu goolaverage FROM ChampionnatPtClassementReel , ChampionnatParticipant WHERE ".
											"saison='$saison' AND tour='$tour' AND groupe='$groupe' AND ChampionnatPtClassementReel.idParticipant=ChampionnatParticipant.idParticipant ".
											"ORDER BY nbPointClassement DESC, positionClassement, goolaverage DESC";
 				$recordset = mysql_query($requeteSQL);
				echo "<script language='JavaScript'>var nbParticipant=".mysql_affected_rows()."</script>";
				echo "<input type='hidden' name='nbParticipant' value='".mysql_affected_rows()."'>";
				$idIndex = 0;
				while($record = mysql_fetch_array($recordset)){

								echo "<tr name='valeurClassement'>".
										"<td witdh='250px'><p>".$record["nomParticipant"]."</p><input type='hidden' name='idParticipant[]' value='".$record['idParticipant']."'></td>".										
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbMatchJoue"]."' name='joue[]' id='joue_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbMatchGagne"]."' name='gagne[]' id='gagne_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbMatchNul"]."' name='nul[]' id='nul_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbMatchPerdu"]."' name='perdu[]' id='perdu_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbMatchForfait"]."' name='forfait[]' id='forfait_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbPointMarque"]."' name='marque[]' id='marque_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbPointRecu"]."' name='encaisse[]' id='encaisse_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["nbPointClassement"]."' name='point[]' id='point_$idIndex' maxlength='3' size='3'></td>".
										"<td align='center'><input type='text' style='text-align:right' value='".$record["positionClassement"]."' name='position[]' id='position_$idIndex' maxlength='3' size='3'></td>".
							 "</tr>";
							 $idIndex++;
				}		
			?>			
		</table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<input type="hidden" name="saison" value="<? echo $_POST["saison"]; ?>">
		<input type="hidden" name="tour" value="<? echo $_POST["tour"]; ?>">
		<input type="hidden" name="idGroupe" value="<? echo $_POST["idGroupe"]; ?>">
		<input type="hidden" name="action" value="modifierClassement">
				
    <p align="center">
          <input type="submit" name="modifier_classement" value="<? echo VAR_LANG_MODIFIER; ?>" onclick="return validateForm();">
		</p>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
