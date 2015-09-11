<div class="nouvellePhase">
<?
	statInsererPageSurf(__FILE__);
?>

<?
	if(isset($_POST["action"]) && $_POST["action"]=="insererTour"){
		$participant = $_POST['participant'];
		if(is_array($participant)){ 
			$saison = $_POST["saison"];
			$categorie = $_POST["categorie"];
			if($categorie==0){
				$tour = 2000;
			}
			elseif(isset($_POST["tour"])){
				$tour = $_POST["tour"];
			}
			if(isset($_POST["tourPrecedent"])){
				$tourPrecedent = $_POST["tourPrecedent"];
			}
			else{
				$tourPrecedent = 0;
			}
			if($tour==1){
				$tourPrecedent = 0;
			}
			elseif($tour==2){
				$tourPrecedent = 1;
			}
			elseif($tour==3){
				$tourPrecedent = 2;
			}
			elseif($tour==4){
				$tourPrecedent = 3;
			}
			if(isset($_POST["groupe"])){
				$groupe = $_POST["groupe"];
			}
			elseif($tour==10000 OR $tour==2000 OR $tour==3000 OR $tour==4000 OR $categorie==0){
				$groupe = 0;	
			}
											
			$requete =	"SELECT * FROM Championnat_Tours WHERE ".
										"saison=".$saison." AND idCategorie=".$categorie." AND idTour=".$tour." AND idGroupe=".$groupe."";
			$retour = mysql_query($requete);
			$nbResultat = mysql_affected_rows();

			// continuer l'insertion si cette phase n'existe pas encore
			if($nbResultat<=0){	
				
				// Insertion du tour
				$requete = "INSERT INTO Championnat_Tours VALUES ('', ".$saison.", ".$tour.", ".$categorie.", ".$groupe.")";
				mysql_query($requete);	
				
				//idGroupe ChampionnatGroupe
				while(list(,$val) = each($participant)){
					
					// inserer l'equipe avec ses points initiaux du tour
					$requete = "INSERT INTO Championnat_Equipes_Tours VALUES ('', ".$saison.", ".$categorie.", ".$tour.", ".$tourPrecedent.", ".$groupe.",".$val.",0,0,0,0,0,0,0,0,0,0,0)";
					// echo $requete;
					mysql_query($requete);
				}
				include('championnat.miseajour.equipes.tour.inc.php');
				echo "<h4>Insertion effectuée avec succès</h4>";
			}
			else{
				echo "<h4>Erreur, ce tour de championnat existe déjà</h4>";				
			}				
		}
		else{
			echo "<h4>Rien à inserer</h4>";
		}	
	}	
?>

<script language="javascript">
	var nbCaseCoche = 0;
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
		if(tourChampionnatExistant[insererTour.saison.value+":"+insererTour.categorie.value+":"+insererTour.tour.value+":"+insererTour.groupe.value]){
			alert("Erreur, ce tour de championnat existe deja");
			return false;
		}
		
		if(insererTour.categorie.value!=0 && insererTour.tour.value==2000){
			alert("Vous ne pouvez pas choisir Promotion / Relegation comme tour si la catégorie n'est pas sur Promotion / Relegation.");	
			return false;
		}
		
		if(nbCaseCoche <= 0){
			alert("Erreur, aucune équipe selectionnée");
			return false;
		}
		else if (nbCaseCoche == 1){
			alert("Erreur, selectionné au moins 2 équipes pour faire un championnat");
			return false;
		}
		if(insererTour.tour.value=='none'){
			alert("Vous devez choisir un tour");
			return false;	
		}
		else{
			return true;
		}
	}
	// Si la catégorie est promotion / releégation, on ne peut pas choisir le nombre de groupes et le tour
	function changeEtatCategorie(){
		if(insererTour.categorie.value==0){ 
			insererTour.groupe.disabled = true;
			insererTour.groupe.value=0;
			insererTour.tour.disabled = true;
			insererTour.tour.value=2000;
			insererTour.tourPrecedent.disabled = true;
			insererTour.tourPrecedent.value=0;
		}
		else{
			insererTour.groupe.disabled = false;
			insererTour.tour.disabled = false;
			insererTour.tourPrecedent.disabled = false;
		}
	}
	function changeEtatTour(){
		if(insererTour.tour.value==10000 || insererTour.tour.value==2000 || insererTour.tour.value==3000 || insererTour.tour.value==4000){ 
			insererTour.groupe.disabled = true;
			insererTour.groupe.value=0;
		}
		else insererTour.groupe.disabled = false;
		
		if(insererTour.tour.value==1){
			insererTour.tourPrecedent.disabled = true;
			insererTour.tourPrecedent.value=0;
		}
		else if(insererTour.tour.value==2){
			insererTour.tourPrecedent.disabled = true;
			insererTour.tourPrecedent.value=1;
		}
		else if(insererTour.tour.value==3){
			insererTour.tourPrecedent.disabled = true;
			insererTour.tourPrecedent.value=2;
		}
		else if(insererTour.tour.value==4){
			insererTour.tourPrecedent.disabled = true;
			insererTour.tourPrecedent.value=3;
		}
		else insererTour.tourPrecedent.disabled = false;
	}
</script>

<?
	echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';
	 var couleurValide; couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';
	 </SCRIPT>";
?>

<form name="insererTour" method="post" action="<? echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" onSubmit="return validateForm();">
	<table class="tableauFormInsererPhase">
		<tr>
			<td class="right">Saison :</td>
			<td>
				<select name="saison">
				<?
					if(date("m")>7){ 
						$anneeActuelle = date("Y");
					}
					else{
						$anneeActuelle = date("Y")-1;
					}
					for($i=$anneeActuelle-5;$i<$anneeActuelle+5;$i++){
						if($i==$anneeActuelle){
							echo "<option value='$i' SELECTED>$i-".($i+1)."</option>";						
						}
						else{
							echo "<option value='$i'>$i-".($i+1)."</option>";
						}
					}				
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="right">Catégorie :</td>
			<td>
				<select name="categorie" onChange="changeEtatCategorie();">
				<?
					$requete = "SELECT * FROM Championnat_Categories ORDER BY idCategorie";
					$retour = mysql_query($requete);
					while($donnees = mysql_fetch_array($retour)){
						echo "<option value='".$donnees['idCategorie']."'>".$donnees["categorie".$_SESSION["__langue__"].""]."</option>";	
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="right">Tour :</td>
			<td>
				<select name="tour" onChange="changeEtatTour();">
					<option value='none'>Choisissez un tour</option>
				<?
					$requete = "SELECT * FROM Championnat_Types_Tours ORDER BY idTour";
					$retour = mysql_query($requete);
					while($donnees = mysql_fetch_array($retour)){
						echo "<option value='".$donnees['idTour']."'>".$donnees["tour".$_SESSION["__langue__"].""]."</option>";	
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="right">Tour précédent:</td>
			<td>
				<select name="tourPrecedent">
					<option value='0'>Pas de tour précédent</option>
				<?
					$requete = "SELECT * FROM Championnat_Types_Tours WHERE idTour!=2000 ORDER BY idTour";
					$retour = mysql_query($requete);
					while($donnees = mysql_fetch_array($retour)){
						echo "<option value='".$donnees['idTour']."'>".$donnees["tour".$_SESSION["__langue__"].""]."</option>";	
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="right">Groupe n&#186; :</td>
			<td>
				<select name="groupe">
					<option value='0'>Qualification</option>
				<?
					for($i=1;$i<=4;$i++){
						echo "<option valute='".$i."'>".$i."</option>";	
					}
				?>
				</select>
			</td>
		</tr>
	</table>
	<br /><br />
	<table class="tableauNouvellePhase">
	<?
	echo "<tr>";
	echo "<th>X</th>";
	echo "<th>Equipe</th>";
	echo "<th>Club</th>";
	//echo "<th>Responsable</th>";
	echo "</tr>";
	
	$requete = "SELECT * FROM Championnat_Equipes WHERE equipe!='' AND actif=1 ORDER BY equipe";
 	$retour = mysql_query($requete);
 	while($donnees = mysql_fetch_array($retour)){
		echo "<tr>";
		echo "<td width='20' class='center'><input onclick='checkBoxActive(this);' type='checkbox' name='participant[]' value='".$donnees["idEquipe"]."' class='couleurCheckBox'></td>";
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

	//creation d'un tableau javascript pour aider l'utiliseur a ne pas entrer un tour existant
	$requeteSQL =	"SELECT DISTINCT saison, idTour, idCategorie, idGroupe FROM Championnat_Tours";
	$recordset = mysql_query($requeteSQL);
	echo "<script language='JavaScript'>var tourChampionnatExistant = new Array();";
	while($record = mysql_fetch_array($recordset)){
		echo "tourChampionnatExistant['".$record["saison"].":".$record["idCategorie"].":".$record["idTour"].":".$record["idGroupe"]."']=true;";
	}
	echo "</script>";
				
	?>
	</table><br /><br />
	<input type="hidden" name="action" value="insererTour">
    <p align="center">
					<input name='submit' type='submit' value='<? echo VAR_LANG_INSERER;?>'>
		</p>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</div>