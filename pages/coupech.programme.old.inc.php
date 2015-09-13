<?php
	include "includes/agenda.utility.inc.php";
?>

<?php
if(isset($_POST['annee'])){
    if($_POST['annee']=="" OR $_POST['annee']=="Avenir"){
        $annee="Avenir";
    }
    else{
        $annee=$_POST['annee'];
    }
}
?>

<form name="affichage" method="post" action="">
<table class="formagenda">
	<tr>
		<td align="right" width="50%"><p><?php echo VAR_LANG_ANNEE;?> :</p></td>
		<td align="left"> <select name="annee" id="select" onChange="affichage.submit();">
         <?php
						// recherche de la premiere date
						$requeteAnnee = "SELECT MIN( CoupeCH_Categories_Par_Annee.annee ) AS min FROM CoupeCH_Categories_Par_Annee";
						$recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
						$anneeMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");

						$anneeDebutCoupeCH=$anneeMin['min'];

						if($annee=="") $annee = "Avenir";

						if($annee=="Avenir") echo "<option selected value='Avenir'>".VAR_LANG_RENCONTRES_A_VENIR."</option>";
						else echo "<option value='Avenir'>".VAR_LANG_RENCONTRES_A_VENIR."</option>";

						$fin = date('Y')+1;
						for($i=$anneeDebutCoupeCH;$i<=$fin;$i++){
							if($annee == $i){
								echo "<option selected value='".$i."'>".$i."</option>";
				            }
							else{
								echo "<option value='".$i."'>".$i."</option>";
				            }
						}
				?></select>
		</td>
	</tr>
	<?php
	if($annee=="Avenir"){
	   $anneeRecherche="annee>=".date('Y');
	}
	else{
	   $anneeRecherche="annee=".$annee;
    }
    $requeteAnnee = "SELECT COUNT(*) AS nbCat FROM CoupeCH_Categories_Par_Annee WHERE annee=".$anneeRecherche."";
    $retourAnnee = mysql_query($requeteAnnee);
    $donneesAnnee = mysql_fetch_array($retourAnnee);
    $nbCategories = $donneesAnnee['nbCat'];
    if($nbCategories<=1){
        $titre = VAR_LANG_EQUIPE;
    }
    else{
        $titre = VAR_LANG_CATEGORIE." / ".VAR_LANG_EQUIPE;
    }
	?>
	<tr>
		<td align="right" width="50%"><p><?php echo $titre; ?> :</p></td>
		<td align="left">
			<select name="recherche" onChange="affichage.submit();">
				<option value="tout">Tout</option>
				<?php
				if($nbCategories>1){
                    $requeteCategorie = "SELECT CoupeCH_Categories.nom".$_SESSION['__langue__'].", CoupeCH_Categories.idCategorie FROM CoupeCH_Categories, CoupeCH_Categories_Par_Annee WHERE CoupeCH_Categories_Par_Annee.idCategorie=CoupeCH_Categories.idCategorie ORDER BY CoupeCH_Categories.idCategorie";
                    $retourCategorie = mysql_query($requeteCategorie);
                    echo "<optgroup label='".VAR_LANG_CATEGORIE."'>";
                    while($donneesCategorie = mysql_fetch_array($retourCategorie)){
                        if($_POST['recherche']=="cat".$donneesCategorie['idCategorie']){
                            $selected="selected='selected'";
                        }
                        else{
                            $selected="";
                        }
                        echo "<option value='cat".$donneesCategorie['idCategorie']."' ".$selected.">".$donneesCategorie['nom'.$_SESSION['__langue__']]."</option>";
                    }
                    echo "</optgroup>";
				}

				$requeteEquipes = "SELECT DISTINCT CoupeCH_Equipes.idEquipe, CoupeCH_Equipes.nomEquipe, CoupeCH_Categories.idCategorie, CoupeCH_Categories.nom".$_SESSION['__langue__']." FROM CoupeCH_Equipes, CoupeCH_Categories, CoupeCH_Categories_Par_Annee WHERE ".$anneeRecherche."  AND CoupeCH_Equipes.idCategorie=CoupeCH_Categories.idCategorie ORDER BY CoupeCH_Categories.idCategorie, CoupeCH_Equipes.nomEquipe";
				$retourEquipes = mysql_query($requeteEquipes);
				$idCategorie="nothing";
				while($donneesEquipes = mysql_fetch_array($retourEquipes)){
					if($idCategorie!=$donneesEquipes['idCategorie'] AND $nbCategories>1){
						if($idCategorie!="nothing"){
							echo "</optgroup>";
						}
						echo "<optgroup label='".$donneesEquipes['categorie'.$_SESSION['__langue__']]."'>";
						$idCategorie=$donneesEquipes['idCategorie'];
					}
					if($_POST['recherche']==$donneesEquipes['idEquipe']){
						$selected="selected='selected'";
					}
					else{
						$selected="";
					}
					echo "<option value='".$donneesEquipes['idEquipe']."' ".$selected.">".$donneesEquipes['nomEquipe']."</option>";
				}
				?>
			</select>
			<?php
				// echo $requeteEquipes;
			?>
		</td>
	</tr>
</table>
</form>

<?php
	// affichage des dates
	if($annee == "Avenir"){
		$annee = date_actuelle();
		$finAffichage = '';
	}
	else{
		$annee = $annee;
		$jusqua = $annee;
		$jusqua .= "-12-31";
		$finAffichage = "AND (dateDebut<='".$jusqua."' OR dateFin<='".$jusqua."')";
	}
	?>

	<table class="agenda">
		<tr>
			<th width="60px"><?php echo $agenda_date;?></th>
			<th><?php echo $agenda_description;?></th>
			<th><?php echo $agenda_lieu;?></th>
			<th width="40px"><?php echo $agenda_debut;?></th>
			<th width="40px"><?php echo $agenda_fin;?></th>
		</tr>
	<?php
	if(isset($_POST['recherche'])){
		if($_POST['recherche']=="tout"){
			$recherche="";
		}
		elseif(preg_match("#^cat#", $_POST['recherche'])){
			$rechercheCat = preg_replace("#cat(.+)#", "$1", $_POST['recherche']);
			$recherche = "AND idCategorie=".$rechercheCat."";
		}
		else{
			$recherche = "AND (equipeA=".$_POST['recherche']." OR equipeB=".$_POST['recherche'].")";
		}
	}
	else{
		$recherche="";
	}
	$requete = "SELECT * FROM CoupeCH_Matchs WHERE (equipeA!=0 AND equipeB!=0) AND (dateDebut>='".$annee."' OR dateFin>='".$annee."') ".$finAffichage." ".$recherche." ORDER BY dateDebut, heureDebut";
	// echo $requete;
	$retour = mysql_query($requete);
	while($donnees = mysql_fetch_array($retour)){
        ?>
		<tr>
			<td class="center"><?php echo date_sql2date($donnees['dateDebut']); ?></td>
			<td>
				<?php
				$requeteA = "SELECT * FROM CoupeCH_Equipes WHERE idEquipe=".$donnees['equipeA']."";
				$retourA = mysql_query($requeteA);
				$donneesA = mysql_fetch_array($retourA);
				echo $donneesA['nomEquipe'];
				echo " - ";
				$requeteA = "SELECT * FROM CoupeCH_Equipes WHERE idEquipe=".$donnees['equipeB']."";
				$retourA = mysql_query($requeteA);
				$donneesA = mysql_fetch_array($retourA);
				echo $donneesA['nomEquipe'];
				if($donnees['dateReportDebut']!='0000-00-00' AND $donnees['dateReportFin']!='0000-00-00' AND $donnees['heureReportDebut']!='00:00:00' AND $donnees['heureReportFin']!='00:00:00'){
				    echo " : Match reporté au ".date_sql2date($donnees['dateReportDebut']);
				}
				?>
			</td>
			<td>
                <?php
                $requeteJournee = "SELECT * FROM CoupeCH_Journees WHERE idJournee=".$donnees['idJournee']."";
                $retourJournee = mysql_query($requeteJournee);
                $donneesJournee = mysql_fetch_array($retourJournee);

                echo $donneesJournee['salle'].", ".$donneesJournee['ville'];
                ?>
            </td>
			<td class="center"><?php echo heure($donnees['heureDebut']); ?>:<?php echo minute($donnees['heureDebut']); ?></td>
			<td class="center"><?php echo heure($donnees['heureFin']); ?>:<?php echo minute($donnees['heureFin']); ?></td>
		</tr>
        <?php
	}
	?>
		<tr>
			<th width="60px"><?php echo $agenda_date;?></th>
			<th><?php echo $agenda_description;?></th>
			<th><?php echo $agenda_lieu;?></th>
			<th width="40px"><?php echo $agenda_debut;?></th>
			<th width="40px"><?php echo $agenda_fin;?></th>
		</tr>
	</table>




