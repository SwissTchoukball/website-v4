<?
?>
<h3>
<? echo VAR_LANG_ETAPE_3; ?>
</h3>
<?
if(!isset($_GET['nbMatchs']) OR !isset($_GET['saison']) OR !isset($_GET['idCat']) OR !isset($_GET['idTour']) OR !isset($_GET['idGroupe'])){
	echo "Erreur: il manque des informations.";
	$nbMatchs=0;
}
else{
	$nbMatchs = $_GET['nbMatchs'];
	$saison = $_GET['saison'];
	$idCategorie = $_GET['idCat'];
	$idTour = $_GET['idTour'];
	$idGroupe = $_GET['idGroupe'];

    function optionsParticipant($saison, $idCategorie, $idTour, $idGroupe){
        if($saison=='' OR $idCategorie=='' OR $idTour=='' OR $idGroupe==''){
            $requete = "SELECT * FROM Championnat_Equipes ORDER BY equipe";
            $retour = mysql_query($requete);
            $nbLigne = mysql_affected_rows();
            while($donnees = mysql_fetch_array($retour)){
                echo "<option value='".$donnees["idEquipe"]."'>".$donnees["equipe"]."</option>";
            }
        }
        elseif($saison!='' AND $idCategorie!='' AND $idTour!='' AND $idGroupe!=''){
            $requete = "SELECT equipe, idEquipe FROM Championnat_Equipes ORDER BY equipe";
            $retour = mysql_query($requete);
            $nbLigne = mysql_affected_rows();
            while($donnees=mysql_fetch_array($retour)){
                $requeteA = "SELECT idEquipe FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$idGroupe."";
                // echo $requeteA;
                $retourA = mysql_query($requeteA);
                while($donneesA=mysql_fetch_array($retourA)){
                    if($donnees['idEquipe']==$donneesA['idEquipe']){
                        echo "<option value='".$donnees["idEquipe"]."'>".$donnees["equipe"]."</option>";
                    }
                }
            }
        }
        else{
            echo "<option>ERREUR</option>";
        }

	}
	?>
    <script language="javascript">
        function validateForm(){
        	var insererMatchForm = document.getElementById("insererMatchForm");
            <?
            for($k=1;$k<=$nbMatchs;$k++){
                if($k==1){
                    ?>
                    if(insererMatchForm.equipeA<? echo $k; ?>.options[insererMatchForm.equipeA<? echo $k; ?>.selectedIndex].text == insererMatchForm.equipeB<? echo $k; ?>.options[insererMatchForm.equipeB<? echo $k; ?>.selectedIndex].text){
                        alert("Erreur, un match doit avoir des participants différents");
                        return false;
                    }
                    <?
                }
                else{
                    ?>
                    else if(insererMatchForm.equipeA<? echo $k; ?>.options[insererMatchForm.equipeA<? echo $k; ?>.selectedIndex].text == insererMatchForm.equipeB<? echo $k; ?>.options[insererMatchForm.equipeB<? echo $k; ?>.selectedIndex].text){
                        alert("Erreur, un match doit avoir des participants différents");
                        return false;
                    }
                    <?
                }
            }
            ?>
        }
		$(function(){
	        $("[name=journee_all]").change(function() {
		        $("[name^=journee]").val($("[name=journee_all]").val());
		    });

	        $("[name=typeMatch_all]").change(function() {
		        $("[name^=typeMatch]").val($("[name=typeMatch_all]").val());
		    });

	        $("[name=idLieu_all]").change(function() {
		        $("[name^=idLieu]").val($("[name=idLieu_all]").val());
		    });

	        $("[name=debutJour_all]").change(function() {
		        $("[name^=debutJour]").val($("[name=debutJour_all]").val());
		        $("[name^=finJour]").val($("[name=debutJour_all]").val());
		    });
	        $("[name=debutMois_all]").change(function() {
		        $("[name^=debutMois]").val($("[name=debutMois_all]").val());
		        $("[name^=finMois]").val($("[name=debutMois_all]").val());
		    });
	        $("[name=debutAnnee_all]").change(function() {
		        $("[name^=debutAnnee]").val($("[name=debutAnnee_all]").val());
		        $("[name^=finAnnee]").val($("[name=debutAnnee_all]").val());
		    });
	        $("[name=debutHeure_all]").change(function() {
		        $("[name^=debutHeure]").val($("[name=debutHeure_all]").val());
		        $("[name^=finHeure]").val(parseInt($("[name=debutHeure_all]").val()) + 1);
		    });
	        $("[name=debutMinute_all]").change(function() {
		        $("[name^=debutMinute]").val($("[name=debutMinute_all]").val());
		        $("[name^=finMinute]").val($("[name=debutMinute_all]").val());
		    });

	        $("[name=finJour_all]").change(function() {
		        $("[name^=finJour]").val($("[name=finJour_all]").val());
		    });
	        $("[name=finMois_all]").change(function() {
		        $("[name^=finMois]").val($("[name=finMois_all]").val());
		    });
	        $("[name=finAnnee_all]").change(function() {
		        $("[name^=finAnnee]").val($("[name=finAnnee_all]").val());
		    });
	        $("[name=finHeure_all]").change(function() {
		        $("[name^=finHeure]").val($("[name=finHeure_all]").val());
		    });
	        $("[name=finMinute_all]").change(function() {
		        $("[name^=finMinute]").val($("[name=finMinute_all]").val());
		    });
	    })

	<?
	for($k=1;$k<=$nbMatchs;$k++){
		?>
			function selectionTypeMatch<? echo $k; ?>(){
				var idTour = <? echo $idTour; ?>;
        		var insererMatchForm = document.getElementById("insererMatchForm");
				if(idTour==1 || idTour==2 || idTour==3 || idTour==4){
				    insererMatchForm.typeMatch<? echo $k; ?>.value = 0;
					insererMatchForm.typeMatch<? echo $k; ?>.disabled = true;
				}
				else if(idTour==2000){
				    insererMatchForm.typeMatch<? echo $k; ?>.value = 1000;
					insererMatchForm.typeMatch<? echo $k; ?>.disabled = true;
				}
				else{
					insererMatchForm.typeMatch<? echo $k; ?>.disabled = false;
				}
			}

			function selectionAutomatiqueAnnee<? echo $k; ?>(){
				insererMatchForm.finAnnee<? echo $k; ?>.value = insererMatchForm.debutAnnee<? echo $k; ?>.value;
			}
			function selectionAutomatiqueMois<? echo $k; ?>(){
				insererMatchForm.finMois<? echo $k; ?>.value = insererMatchForm.debutMois<? echo $k; ?>.value;
			}
			function selectionAutomatiqueJour<? echo $k; ?>(){
				insererMatchForm.finJour<? echo $k; ?>.value = insererMatchForm.debutJour<? echo $k; ?>.value;
			}
			function selectionAutomatiqueHeure<? echo $k; ?>(){
				insererMatchForm.finHeure<? echo $k; ?>.value = parseInt(insererMatchForm.debutHeure<? echo $k; ?>.value) + 1;
			}
			function selectionAutomatiqueMinute<? echo $k; ?>(){
				insererMatchForm.finMinute<? echo $k; ?>.value = insererMatchForm.debutMinute<? echo $k; ?>.value;
			}
		<?
	}
	?>
	</script>
	<form id="insererMatchForm" method="post" action="<? echo "?menuselection=".$menuselection."&smenuselection=".$smenuselection.""; ?>" onSubmit="return validateForm();">
	<?
	echo "<table class='tableauFormInsererMatch'>";
		echo "<tr>";
			echo "<th>";
				echo VAR_LANG_EQUIPES;
			echo "</th>";
			echo "<th>";
				echo VAR_LANG_JOURNEE;
			echo "</th>";
			echo "<th>";
				echo VAR_LANG_TYPE_MATCH;
			echo "</th>";
			echo "<th>";
				echo VAR_LANG_DATE;
			echo "</th>";
		echo "</tr>";
		/* INDICATION POUR TOUS LES MATCHS À AJOUTER */
		echo "<tr>";
			echo "<td>";
				echo "<strong>Modifier pour<br />tous les matchs</strong>";
			echo "</td>";
			echo "<td>";
				echo "<select name='journee_all'>";
					for ($i=1; $i<50; $i++) {
						echo "<option value='$i'>$i</option>";
					}
				echo "</select>";
			echo "</td>";
			echo "<td>";
				if($idCategorie==0){
					$bloquage="disabled='disabled'";
				}
				echo "<select name='typeMatch_all' ".$bloquage.">";
					$requete = "SELECT * FROM Championnat_Types_Matchs ORDER BY idTypeMatch";
					$retour = mysql_query($requete);
					while($donnees = mysql_fetch_array($retour)){
							if($idCategorie==0 AND $donnees['idTypeMatch']==1000){
								$selectionAuto="selected='selected'";
							}
							else{
								$selectionAuto="";
							}
							echo "<option value='".$donnees['idTypeMatch']."' ".$selectionAuto.">".$donnees["type".$_SESSION["__langue__"].""]."</option>";
					}
				echo "</select>";
				echo "<br />";
				echo "<input type='checkbox' name='necessiteDefraiementArbitre' for='necessiteDefraiementArbitre' checked='checked'/><label id='necessiteDefraiementArbitre'>Nécessite le défraiement<br />des arbitres</label>";
			echo "</td>";
			echo "<td>";
				?>
					<table class="tableauLieuDateChampionnat">
						<tr>
			                <td><p><? echo $agenda_lieu; ?></p></td>
			                <td colspan="3">
			                    <select name="idLieu_all">
				                    <option value="NULL">Non défini</option>
			                        <?
			                            $requete = "SELECT * FROM Lieux ORDER BY nomCourt";
			                            $retour = mysql_query($requete);
			                            while ($donnees = mysql_fetch_array($retour)) {
			                                echo "<option value='".$donnees['id']."'>".$donnees["nomCourt"].", ".$donnees['ville']."</option>";
			                            }
			                        ?>
			                    </select>
			                </td>
			            </tr>
						<tr>
							<td><p><? echo $agenda_debut;?></p></td>
							<td colspan="3">
								<p>
									<? echo $agenda_date;?> :
									<select name="debutJour_all" id="debutJour">
										<? echo creation_liste_jour(); ?>
									</select>
									<select name="debutMois_all" id="debutMois">
										<? echo creation_liste_mois(); ?>
									</select>
									<select name="debutAnnee_all" id="debutAnnee">
										<?
										$anneeActuelle = date('Y');
										for($i=$saison;$i<=$saison+1;$i++){
											if($i==$anneeActuelle){
												echo "<option value=".$i." SELECTED>".$i."</option>";
											}
											else{
												echo "<option value=".$i.">".$i."</option>";
											}
										}
										?>
									</select>
									<? echo $agenda_heure;?> :
									<select name="debutHeure_all" id="debutHeure">
										<? echo modif_liste_heure("20"); ?>
									</select>
									<select name="debutMinute_all" id="debutMinute">
										<? echo modif_liste_minute("45"); ?>
									</select>
								</p>
							</td>
						</tr>
						<tr>
							<td><p><? echo $agenda_fin;?></p></td>
							<td colspan="3">
								<p>
									<? echo $agenda_date;?> :
									<select name="finJour_all" id="finJour">
										<? echo creation_liste_jour(); ?>
									</select>
									<select name="finMois_all" id="finMois">
										<? echo creation_liste_mois(); ?>
									</select>
									<select name="finAnnee_all" id="finAnnee">
										<?
										$anneeActuelle = date('Y');
										for($i=$saison;$i<=$saison+1;$i++){
											if($i==$anneeActuelle){
												echo "<option value=".$i." SELECTED>".$i."</option>";
											}
											else{
												echo "<option value=".$i.">".$i."</option>";
											}
										}
										?>
									</select>
									<? echo $agenda_heure;?> :
									<select name="finHeure_all" id="finHeure">
										<? echo modif_liste_heure("21"); ?>
									</select>
									<select name="finMinute_all" id="finMinute">
										<? echo modif_liste_minute("45"); ?>
									</select>
								</p>
							</td>
						</tr>
					</table>
				<?
			echo "</td>";
	/* INDICATION SPÉCIFIQUE À CHAQUE MATCH */
	for($k=1;$k<=$nbMatchs;$k++){
			echo "</tr>";
			echo "<tr>";
				echo "<td class='center'>";
					echo "<select name='equipeA".$k."'>";
						optionsParticipant($saison, $idCategorie, $idTour, $idGroupe);
					echo "</select>";
					echo "<br /><br />";
					echo VAR_LANG_JOUERA_AVEC;
					echo "<br /><br />";
					echo "<select name='equipeB".$k."'>";
						optionsParticipant($saison, $idCategorie, $idTour, $idGroupe);
					echo "</select>";
				echo "</td>";
				echo "<td>";
					echo "<select name='journee".$k."'>";
						$anneeActuelle = date("Y");
						for($i=1;$i<50;$i++){
							echo "<option value='$i'>$i</option>";
						}
					echo "</select>";
				echo "</td>";
				echo "<td>";
					if($idCategorie==0){
						$bloquage="disabled='disabled'";
					}
					echo "<select name='typeMatch".$k."' ".$bloquage.">";
						$requete = "SELECT * FROM Championnat_Types_Matchs ORDER BY idTypeMatch";
						$retour = mysql_query($requete);
						while($donnees = mysql_fetch_array($retour)){
								if($idCategorie==0 AND $donnees['idTypeMatch']==1000){
									$selectionAuto="selected='selected'";
								}
								else{
									$selectionAuto="";
								}
								echo "<option value='".$donnees['idTypeMatch']."' ".$selectionAuto.">".$donnees["type".$_SESSION["__langue__"].""]."</option>";
						}
					echo "</select>";
				echo "</td>";
				echo "<td>";
				?>
					<table class="tableauLieuDateChampionnat">
						<!--<tr>
							<td><p><? echo VAR_LANG_SALLE; ?></p></td>
							<td><input name="salle<? echo $k; ?>" type="text" size="20" ></td>
							<td><p><? echo VAR_LANG_VILLE; ?></p></td>
							<td><input name="ville<? echo $k; ?>" type="text" size="20" ></td>
						</tr>-->
						<tr>
			                <td><p><? echo $agenda_lieu; ?></p></td>
			                <td colspan="3">
			                    <select name="idLieu<? echo $k; ?>">
				                    <option value="NULL">Non défini</option>
			                        <?
			                            $requete = "SELECT * FROM Lieux ORDER BY nomCourt";
			                            $retour = mysql_query($requete);
			                            while ($donnees = mysql_fetch_array($retour)) {
			                                echo "<option value='".$donnees['id']."'>".$donnees["nomCourt"].", ".$donnees['ville']."</option>";
			                            }
			                        ?>
			                    </select>
			                </td>
			            </tr>
						<tr>
							<td><p><? echo $agenda_debut;?></p></td>
							<td colspan="3">
								<p>
									<? echo $agenda_date;?> :
									<select name="debutJour<? echo $k; ?>" id="debutJour" onChange="selectionAutomatiqueJour<? echo $k; ?>()">
										<? echo creation_liste_jour(); ?>
									</select>
									<select name="debutMois<? echo $k; ?>" id="debutMois" onChange="selectionAutomatiqueMois<? echo $k; ?>()">
										<? echo creation_liste_mois(); ?>
									</select>
									<select name="debutAnnee<? echo $k; ?>" id="debutAnnee" onChange="selectionAutomatiqueAnnee<? echo $k; ?>()">
										<?
										$anneeActuelle = date('Y');
										for($i=$saison;$i<=$saison+1;$i++){
											if($i==$anneeActuelle){
												echo "<option value=".$i." SELECTED>".$i."</option>";
											}
											else{
												echo "<option value=".$i.">".$i."</option>";
											}
										}
										?>
									</select>
									<? echo $agenda_heure;?> :
									<select name="debutHeure<? echo $k; ?>" id="debutHeure" onChange="selectionAutomatiqueHeure<? echo $k; ?>()">
										<? echo modif_liste_heure("20"); ?>
									</select>
									<select name="debutMinute<? echo $k; ?>" id="debutMinute" onChange="selectionAutomatiqueMinute<? echo $k; ?>()">
										<? echo modif_liste_minute("45"); ?>
									</select>
								</p>
							</td>
						</tr>
						<tr>
							<td><p><? echo $agenda_fin;?></p></td>
							<td colspan="3">
								<p>
									<? echo $agenda_date;?> :
									<select name="finJour<? echo $k; ?>" id="finJour">
										<? echo creation_liste_jour(); ?>
									</select>
									<select name="finMois<? echo $k; ?>" id="finMois">
										<? echo creation_liste_mois(); ?>
									</select>
									<select name="finAnnee<? echo $k; ?>" id="finAnnee">
										<?
										$anneeActuelle = date('Y');
										for($i=$saison;$i<=$saison+1;$i++){
											if($i==$anneeActuelle){
												echo "<option value=".$i." SELECTED>".$i."</option>";
											}
											else{
												echo "<option value=".$i.">".$i."</option>";
											}
										}
										?>
									</select>
									<? echo $agenda_heure;?> :
									<select name="finHeure<? echo $k; ?>" id="finHeure">
										<? echo modif_liste_heure("21"); ?>
									</select>
									<select name="finMinute<? echo $k; ?>" id="finMinute">
										<? echo modif_liste_minute("45"); ?>
									</select>
								</p>
							</td>
						</tr>
					</table>
				<?
				echo "</td>";
			echo "</tr>";
			?>
            <script language="javascript">
                selectionTypeMatch<? echo $k; ?>();
            </script>
	<?
	}
	echo "</table><br /><br />";
	?>
	<input type="hidden" name="nbMatchs" value="<? echo $nbMatchs; ?>">
	<input type="hidden" name="saison" value="<? echo $saison; ?>">
	<input type="hidden" name="idCategorie" value="<? echo $idCategorie; ?>">
	<input type="hidden" name="idTour" value="<? echo $idTour; ?>">
	<input type="hidden" name="idGroupe" value="<? echo $idGroupe; ?>">
	<input type="hidden" name="action" value="insererMatchs2">
	<input type="submit" name="submit" value="<? echo VAR_LANG_INSERER;?>">
	</form>
<?
}
?>