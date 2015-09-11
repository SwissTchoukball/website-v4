<? ?>
<h3>
<? echo VAR_LANG_ETAPE_2; ?>
</h3>
<script language="Javascript">
	<?
	echo "var couleurErreur='#".VAR_LOOK_COULEUR_ERREUR_SAISIE."';";
	echo "var couleurValide='#".VAR_LOOK_COULEUR_SAISIE_VALIDE."';"
	?>

	function testAutoQualif(){
		var etape2 = document.getElementById("formulaireInsertionCoupeCH");
		<?
        for($k=1;$k<=$_POST['nbEquipes'];$k++){
        	if($k%2==0){
        		$j=$k-1;
        		$autoqualif=false;
        	}
        	else{
        		$j=$k+1;
        		$autoqualif=true;
        	}
			?>
			var autoqualifieA = document.getElementById("autoqualifie<? echo $k; ?>");
			var autoqualifieB = document.getElementById("autoqualifie<? echo $j; ?>");
			if(etape2.equipe<? echo $k; ?>.value!=0){
				if(etape2.equipe<? echo $j; ?>.value==0){
					<?
					if($autoqualif){
						echo "autoqualifieA.style.visibility = \"visible\";";
						echo "autoqualifieB.style.visibility = \"hidden\";";
					}
					else{
						echo "alert(\"Seule l'équipe du haut peut être autoqualifiée\");";
						echo "etape2.equipe".$k.".value=0;";
						echo "autoqualifieA.style.visibility = \"hidden\";";
						echo "autoqualifieB.style.visibility = \"hidden\";";
					}
					?>
				}
				else{
					autoqualifieA.style.visibility = "hidden";
					autoqualifieB.style.visibility = "hidden";
				}
			}
			else{
				if(etape2.equipe<? echo $j; ?>.value==0){
					autoqualifieA.style.visibility = "hidden";
					autoqualifieB.style.visibility = "hidden";
				}
				else{
					<?
					if(!$autoqualif){
						echo "autoqualifieA.style.visibility = \"hidden\";";
						echo "autoqualifieB.style.visibility = \"visible\";";
					}
					else{
						echo "alert(\"Seule l'équipe du haut peut être autoqualifiée\");";
						echo "etape2.equipe".$j.".value=0;";
						echo "autoqualifieA.style.visibility = \"hidden\";";
						echo "autoqualifieB.style.visibility = \"hidden\";";
					}
					?>
				}
			}
			<?
        } // fin boucle for
		?>
	}
	
	function testDoublons(listeEquipe){
		var etape2 = document.getElementById("formulaireInsertionCoupeCH");
		var erreur = 0;
		<?
		for($k=1;$k<=$_POST['nbEquipes'];$k++){
			?>
			if(listeEquipe.value==etape2.equipe<? echo $k; ?>.value && listeEquipe.value!=0){
				erreur++;
			}
			<?
		}
		?>
		if(erreur>1){
			alert("Cette équipe est déjà dans le tableau de départ !");
			listeEquipe.value=0;
			testAutoQualif();
		}
	}
	
	function checkSaisie(){
		var etape2 = document.getElementById("formulaireInsertionCoupeCH");
		var counter = 0;
		<?
		for($k=1;$k<=$_POST['nbEquipes'];$k++){
			?>
			if(etape2.equipe<? echo $k; ?>.value==0){
				counter++;
			}
			<?
		}
		?>
		if(counter > <? echo $_POST['nbEquipes']-($_POST['nbEquipes']/2)-1; ?>){
			alert("Trop d'équipes indéfinies. ("+counter+")");
			return false;
		}
		else{
			return true;
		}
	}
</script>
<form method="post" action="" id="formulaireInsertionCoupeCH" onSubmit="return checkSaisie();">
    <fieldset>
        <legend><? echo VAR_LANG_ETAPE_1; ?></legend>
        <label for="annee">Année : </label> <? echo $_POST['annee']; ?>
        <input type="hidden" name="annee" value="<? echo $_POST['annee']; ?>" /><br /><br />
        <?
        $requeteCat="SELECT nom".$_SESSION['__langue__']." FROM CoupeCH_Categories ORDER BY idCategorie";
        $retourCat=mysql_query($requeteCat);
        $donneesCat=mysql_fetch_array($retourCat)
        ?>
        <label for="categorie">Catégorie : </label> <? echo $donneesCat['nom'.$_SESSION['__langue__']]; ?>
        <input type="hidden" name="categorie" value="<? echo $_POST['categorie']; ?>" /><br /><br />
        <label for="nbEquipes">Nombre d'équipes : </label> <? echo $_POST['nbEquipes']; ?>
        <input type="hidden" name="nbEquipes" value="<? echo $_POST['nbEquipes']; ?>" /><br /><br />
        <?
        if($_POST['sets']=="oui"){
            ?>
            Les matchs se joueront en 
            <select name="nbSetsGagnants" id="nbSetsGagnants">
            	<option>1</option>
            	<option>2</option>
            	<option selected="selected">3</option> <? /* max 3 car la BDD peut pas + au niveau des colonnes */ ?>
            </select> 
            sets gagnants.
            <?
            $setOuMatch="chaque set";
        }
        else{
        	?>
        	<input type="hidden" name="nbSetsGagnants" id="nbSetsGagnants" value="0" />
        	<?
            $setOuMatch="le match";
        }
        ?><br />
        Si une équipe déclare forfait ou se fait disqualifier, son adversaire gagne <? echo $setOuMatch; ?> 
        <select name="scoreGagnantParForfait" id="scoreGagnantParForfait" value="15">
        <?
            for($i=0;$i<=120;$i++){
                if($i==15){ // score par défaut pour les sets
                    $selected = "selected='selected'";
                }
                else{
                    $selected = "";
                }
                echo "<option value='".$i."' ".$selected.">$i</option>";
            }
        ?>
        </select> 
        à 0.
        <br /><br />
    </fieldset>
    <fieldset>
        <legend><? echo VAR_LANG_ETAPE_2; ?></legend>
        <?
        for($k=1;$k<=$_POST['nbEquipes'];$k++){
            ?>
            <label for="equipe<? echo $k; ?>">Equipe <? echo $k; ?> : </label>
            <select name="equipe<? echo $k; ?>" id="equipe<? echo $k; ?>" onChange="testAutoQualif();testDoublons(this);">
                <option value="0">Aucune</option>
                <?
                $requeteEquipes="SELECT * FROM CoupeCH_Equipes ORDER BY nomEquipe";
                $retourEquipes=mysql_query($requeteEquipes);
                while($donneesEquipes=mysql_fetch_array($retourEquipes)){
                    ?>
                    <option value="<? echo $donneesEquipes['idEquipe']; ?>"><? echo $donneesEquipes['nomEquipe']?></option>
                    <?
                }
                ?>
            </select>
            <span id="autoqualifie<? echo $k; ?>" style="visibility:hidden;"><strong>Autoqualifié !!!</strong></span>
            <br />
            <?
            if($k%2==0){
                echo "<br />";
            }
        }
        ?>
    </fieldset>
    
    <p>
        <input type="hidden" name="prochaineEtape" value="etape3" />
        <!-- FAIRE VERIFICATION PAS DOUBLONS EQUIPE + AVERTISSEMENT AUTO QUALIF -->
        <input type="submit" value="<? echo VAR_LANG_ETAPE_3; ?>"/>
    </p>
</form>