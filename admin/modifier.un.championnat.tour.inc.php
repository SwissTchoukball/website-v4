<?php
	statInsererPageSurf(__FILE__);
?>

<script language="javascript">

	var arrayOfSelectionned = new Array();

	function checkBoxActive(chkBox){
		var equipe = document.getElementById("equipe_"+chkBox.value);
		if(chkBox.checked){
			nbCaseCoche++;
		}
		else{
			nbCaseCoche--;
		}

		// save wich item is selected
		arrayOfSelectionned[chkBox.value] = chkBox.checked;
	}

	function validateForm(){

		if(nbCaseCoche <= 0){
			alert("Erreur, aucune équipe selectionnée");
			return false;
		}
		else if (nbCaseCoche == 1){
			alert("Erreur, selectionné au moins 2 équipes pour faire un championnat");
			return false;
		}
		else{
			return true;
		}
	}

</script>

<?php
	echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';
	 var couleurValide; couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';
	 </SCRIPT>";
?>

<form name="modifierTour" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">

						<table class="tableauNouvellePhase">
            <?php
				echo "<tr>";
				echo "<th>X</th>";
				echo "<th>Equipe</th>";
				echo "<th>Club</th>";
				//echo "<th>Responsable</th>";
				echo "</tr>";

				$saison = $_POST["saison"];
				$categorie = $_POST["categorie"];
				$tour = $_POST["tour"];
				$groupe = $_POST["idGroupe"];

				$requete = "SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$categorie." AND idTour=".$tour." AND noGroupe=".$groupe."";
				// echo $requete;
				$retour = mysql_query($requete);
				echo "<script language='JavaScript'>var nbCaseCoche = ".mysql_affected_rows().";</script>";

				$tabParticipantCoche = null;
				$tabParticipantTourPrecedent = null;
				$tabIndex = 0;
				while($donnees = mysql_fetch_array($retour)){
					$tabParticipantCoche[$donnees["idEquipe"]] = true;
					$tourPrecedent = $donnees['idTourPrecedent'];
					$tabIndex++;
				}

				$requete = "SELECT * FROM Championnat_Equipes WHERE equipe!='' AND actif=1 ORDER BY equipe";
			 	$retour = mysql_query($requete);
			 	while($donnees = mysql_fetch_array($retour)){
					echo "<tr>";
					if($tabParticipantCoche[$donnees["idEquipe"]]){
						$critereChecked = "checked='checked'";
					}
					else{
						$critereChecked = "";
					}
					echo "<td width='20' class='center'><input onclick='checkBoxActive(this);' type='checkbox' name='participant[]' value='".$donnees["idEquipe"]."' class='couleurCheckBox' ".$critereChecked."></td>";
					echo "<td class='center'>".$donnees["equipe"]."</td>";
					$requeteA = "SELECT * FROM ClubsFstb WHERE id=".$donnees['idClub']."";
					$retourA = mysql_query($requeteA);
					$donneesA = mysql_fetch_array($retourA);
					echo "<td>".$donneesA['club']."</td>";
					/*$requeteA = "SELECT * FROM Personne WHERE id=".$donnees['idResponsable']."";
					$retourA = mysql_query($requeteA);
					$donneesA = mysql_fetch_array($retourA);
					echo "<td>".$donneesA['prenom']." ".$donneesA['nom']."</td>";*/
					echo "<script language='JavaScript'>arrayOfSelectionned[".$donnees["idEquipe"]."]=false;</script>";
					echo "</tr>";
				}
				/*
				$requeteSQL = "SELECT * FROM ChampionnatParticipant WHERE nomParticipant<>'' ORDER BY nomParticipant";
 				$recordset = mysql_query($requeteSQL);
				$indexArray = 0;
				$nbLigne = mysql_affected_rows();
				$nbParticipantJS = $nbLigne+1; // les indexes mysql commence a 1 et non a zero
				echo "<script language='JavaScript'>var nbParticipant=$nbParticipantJS</script>";
				while($record = mysql_fetch_array($recordset)){
					echo '<tr>';
					if($tabParticipantCoche[$record["idParticipant"]]) echo "<td width='20'><input onclick='checkBoxActive(this);' type='checkbox' name='participant[]' value='".$record["idParticipant"]."' class='couleurCheckBox' CHECKED></td>";
					else echo "<td width='20'><input onclick='checkBoxActive(this);' type='checkbox' name='participant[]' value='".$record["idParticipant"]."' class='couleurCheckBox'></td>";
					echo "<td width='200'><P>".$record["nomParticipant"]."</P></td>";
					if($tabParticipantCoche[$record["idParticipant"]]) echo "<script language='JavaScript'>arrayOfSelectionned[".$record["idParticipant"]."]=true;</script>";
					else echo "<script language='JavaScript'>arrayOfSelectionned[".$record["idParticipant"]."]=false;</script>";
					echo '</tr>';
					$indexArray++;
				}			*/
			?>
          </table><br /><br />

		<input type="hidden" name="saison" value="<?php echo $_POST['saison']; ?>">
		<input type="hidden" name="categorie" value="<?php echo $_POST['categorie']; ?>">
		<input type="hidden" name="tour" value="<?php echo $_POST['tour']; ?>">
		<input type="hidden" name="tourPrecedent" value="<?php echo $tourPrecedent; ?>">
		<input type="hidden" name="idGroupe" value="<?php echo $_POST['idGroupe']; ?>">
		<input type="hidden" name="action" value="modifierTour">

    <p align="center">
          <input type="submit" name="modifier_tour" value="<?php echo VAR_LANG_MODIFIER; ?>" onclick="return validateForm();">
		</p>
</form>
