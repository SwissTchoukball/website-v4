<?php
statInsererPageSurf(__FILE__);

// table des listes : noms, id table BD, id col, nom description (non langue).
$tabListe = array(array(VAR_LANG_DBD_ARBITRE,"DBDArbitre","idArbitre","descriptionArbitre"),
									array(VAR_LANG_DBD_AUTRE_FONCTION,"DBDAutreFonction","idAutreFonction","descriptionAutreFonction"),
									array(VAR_LANG_DBD_CHTB,"DBDCHTB","idCHTB","descriptionCHTB"),
									array(VAR_LANG_DBD_CIVILITE,"DBDCivilite","idCivilite","descriptionCivilite"),
									array(VAR_LANG_DBD_FORMATION,"DBDFormation","idFormation","descriptionFormation"),
									array(VAR_LANG_DBD_LANGUE,"DBDLangue","idLangue","descriptionLangue"),
									//array(VAR_LANG_DBD_RAISON_SOCIALE,"DBDRaisonSociale","idRaisonSociale","descriptionRaisonSociale"),									
									array(VAR_LANG_DBD_ORIGINE_ADRESSE,"DBDOrigineAdresse","idOrigineAdresse","descriptionOrigineAdresse"),											
									array(VAR_LANG_DBD_PAYS,"DBDPays","idPays","descriptionPays"),
									array(VAR_LANG_DBD_STATUS,"DBDStatus","idStatus","descriptionStatus"),
									array(VAR_LANG_DBD_MEDIA_TYPE,"DBDMediaType","idMediaType","descriptionMediaType"),
									array(VAR_LANG_DBD_MEDIA_CANTON,"DBDMediaCanton","idMediaCanton","descriptionMediaCanton")																		
										  );
											
$tabListeNonLanguee = array(array(VAR_LANG_CLUB_FSTB,"ClubsFstb","nbIdClub","club","id")
														);

?>
<table align='center' cellpadding="2" cellspacing="0" border="0">
<?php

	function findIndexTable($array,$idBDTable){
		for($i=0;$i<count($array);$i++){
			if($idBDTable == $array[$i][1]){
				return $i;
			}
		}
		die("error in index db table");
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// gestion de la base : insertion, modification
	////////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($_POST["action"]) && $_POST["action"]=="inserer" && isset($_POST["idTable"])){
		
		// recherche de la table (en bd)
		if($_POST["nonLangue"]==true){ $wTabListe = $tabListeNonLanguee;}
		else {$wTabListe = $tabListe;}
		$tableIndex = findIndexTable($wTabListe, $_POST["idTable"]);
		
		// simple insertion ou multi-langue ?
		if(isset($_POST["nonLangue"]) && $_POST["nonLangue"]){
			
			
			// Attention, fonctionne pour les clubs, modification a faire pour d'autre liste....
			
			$requeteSQL = "SELECT COUNT(*) FROM ".$_POST["idTable"];
			$recordset = mysql_query($requeteSQL);
			$nbEnregistrement = mysql_fetch_array($recordset);
			$requeteSQL = "INSERT INTO `".$_POST["idTable"]."` ( `".$wTabListe[$tableIndex][4]."` , `".$wTabListe[$tableIndex][3]."`) VALUES ('".$nbEnregistrement[0]."', '".$_POST["description"]."');";
		}
		else{
		
			$cols = "";
			$vals = "";			
			for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
				$cols .= "`".$wTabListe[$tableIndex][3].$VAR_TABLEAU_DES_LANGUES[$i][0]."`";
				$vals .= "'".validiteInsertionTextBd($_POST["description".$VAR_TABLEAU_DES_LANGUES[$i][0]])."'";
				// ajoute un separateur sauf pour le dernier cas.
				if($i < count($VAR_TABLEAU_DES_LANGUES) -1){
					$cols .= ",";
					$vals .=  ",";
				}
			}		
			$requeteSQL = "INSERT INTO `".$_POST["idTable"]."` (".$cols.")"." VALUES (".$vals.");";			
		}
		// try to insert
		if(mysql_query($requeteSQL)===false){
			echo "<div><h4>Erreur d'insertion, l'entr&eacute;e existe d&eacute;j&agrave;</h4></div>";
		}
		else{
			echo "<div><h4>Insertion r&eacute;ussie avec succ&egrave;s</h4></div>";			
		}

		// reset var		
		$modificationListe = "";
		$idSelect="";
		$ajouterListe="";
	}
	else if(isset($_POST["action"]) && $_POST["action"]=="modifier" && isset($_POST["idTable"])){
	
		// recherche de la table (en bd)
		if($_POST["nonLangue"]==true){ $wTabListe = $tabListeNonLanguee;}
		else {$wTabListe = $tabListe;}
		$tableIndex = findIndexTable($wTabListe, $_POST["idTable"]);
		
		// simple insertion ou multi-langue ?
		if(isset($_POST["nonLangue"]) && $_POST["nonLangue"]){
				$requeteSQL = "UPDATE `".$_POST["idTable"]."` SET ".$wTabListe[$tableIndex][3]."='".$_POST["description"]."' WHERE ".$wTabListe[$tableIndex][2]."='".$_POST["idModification"]."';";
		}
		else{
		
			$colsVals = "";
			for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
				$colsVals .= "`".$wTabListe[$tableIndex][3].$VAR_TABLEAU_DES_LANGUES[$i][0]."`='".$_POST["description".$VAR_TABLEAU_DES_LANGUES[$i][0]]."'";
				//$vals .= "'".$_POST["description".$VAR_TABLEAU_DES_LANGUES[$i][0]]."'";
				// ajoute un separateur sauf pour le dernier cas.
				if($i < count($VAR_TABLEAU_DES_LANGUES) -1){
					$colsVals .= ",";
				}
			}
			$requeteSQL = "UPDATE `".$_POST["idTable"]."` SET ".$colsVals." WHERE ".$wTabListe[$tableIndex][2]."='".$_POST["idModification"]."';";					
		}

		// try to modify
		if(mysql_query($requeteSQL)===false){
			echo "<div><h4>Erreur de modification</h4></div>";
		}
		else{
			echo "<div><h4>Modification r&eacute;ussie avec succ&egrave;s</h4></div>";			
		}	
		
		// reset var
		$modificationListe = "";
		$idSelect="";
		$ajouterListe="";
	}


	// selection : modifier, ajouter, afficher
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// modifier une entree
	////////////////////////////////////////////////////////////////////////////////////////////////////	
	if($modificationListe != "" && $idSelect!=""){
	
		// recherche de la table (en bd)
		if($nonLangue==true) $wTabListe = $tabListeNonLanguee;
		else $wTabListe = $tabListe;
		$tableIndex = findIndexTable($wTabListe, $modificationListe);

		// formulaire de modification		
		echo "<form name='modifierEntreeListe' onSubmit='return saisieValide();' method='post' action='?menuselection=$menuselection&smenuselection=$smenuselection'>";
		
		echo "<script langage='javascript'>";
			echo "function saisieValide(){";
			for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
				echo "if(modifierEntreeListe.description".$VAR_TABLEAU_DES_LANGUES[$i][0].".value==''){";
					echo "modifierEntreeListe.description".$VAR_TABLEAU_DES_LANGUES[$i][0].".focus();";
					echo "alert('Champ vide impossible');";					
					echo "return false;";
				echo "}";				
			}
			echo "return true;";
			echo "}";
		echo "</script>";
		
		echo "<tr><td class='center'><h4>".$wTabListe[$tableIndex][0]."</h4></td></tr>";
		
		echo "<tr><td class='center'><p>".VAR_LANG_LIST_MODIFIER."</p></td></tr>";		
		
		$requeteSQL = "SELECT * FROM ".$wTabListe[$tableIndex][1]." WHERE ".$wTabListe[$tableIndex][2]."='$idSelect'"; 

		$recordset = mysql_query($requeteSQL);
		$record = mysql_fetch_array($recordset) or die("erreur, url invalide");
		
		// langue => les champs selon les langues définies, sinon un seul champ
		if($nonLangue==true){			
			echo "<tr><td align='center'><input type='text' name='description' value='".$record[$wTabListe[$tableIndex][3]]."'/><input name='nonLangue' type='hidden' value='true'></td></tr>";
		}
		else{
			echo "<tr><td align='center'><table>";
			for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
				echo "<tr>";
					echo "<td ><p align='left'>".$VAR_TABLEAU_DES_LANGUES[$i][1]." : </td>";			
					echo "<td><input type='text' name='description".$VAR_TABLEAU_DES_LANGUES[$i][0]."' value='".$record[$wTabListe[$tableIndex][3].$VAR_TABLEAU_DES_LANGUES[$i][0]]."'/><span style='color:red;font-size:10px'>*Obligatoire</span></td>";
				echo "</tr>";							
			}
			echo "<tr><td colspan='2' align='center'><p>Si vous ne connaissez pas la traduction, copier-coller le text en français</p></td></tr>";			
			echo "</table></tr></td>";
		}
		echo "<input name='idModification' type='hidden' value='$idSelect'>";	
		echo "<input name='idTable' type='hidden' value='".$wTabListe[$tableIndex][1]."'>";	
		echo "<input name='action' type='hidden' value='modifier'>";				
		echo "<tr><td align='center'><input type='submit' value='".VAR_LANG_MODIFIER."'></td></tr>";			
		echo "</form>";
		echo "<tr><td align='center'>&nbsp;</td></tr>";
		echo "<tr><td align='center'><form method='post' action='?menuselection=$menuselection&smenuselection=$smenuselection'><input type='submit' value='".VAR_LANG_ANNULER."'></form></td></tr>";
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// ajouter une entree
	////////////////////////////////////////////////////////////////////////////////////////////////////
	else if($ajouterListe != ""){
	
		// recherche de la table (en bd)
		if($nonLangue==true) $wTabListe = $tabListeNonLanguee;
		else $wTabListe = $tabListe;
		$tableIndex = findIndexTable($wTabListe, $ajouterListe);

		// formulaire de modification
		echo "<form name='insererEntreeListe' onSubmit='return saisieValide();' method='post' action='?menuselection=$menuselection&smenuselection=$smenuselection'>";
		
		echo "<script langage='javascript'>";
			echo "function saisieValide(){";
			for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
				echo "if(insererEntreeListe.description".$VAR_TABLEAU_DES_LANGUES[$i][0].".value==''){";
					echo "insererEntreeListe.description".$VAR_TABLEAU_DES_LANGUES[$i][0].".focus();";
					echo "alert('Champ vide impossible');";					
					echo "return false;";
				echo "}";				
			}
			echo "return true;";
			echo "}";
		echo "</script>";		
		
		
		echo "<tr><td align='center'><h4>".$wTabListe[$tableIndex][0]."</h4></td></tr>";

		echo "<tr><td align='center'><p>".VAR_LANG_LIST_INSERER."</p></td></tr>";
		
		// langue => les champs selon les langues définies, sinon un seul champ
		if($nonLangue==true){			
			echo "<tr><td align='center'><input type='text' name='description' value=''/><input name='nonLangue' type='hidden' value='true'></td></tr>";
		}
		else{
			echo "<tr><td align='center'><table>";	
			for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
				echo "<tr>";			
					echo "<td><p>".$VAR_TABLEAU_DES_LANGUES[$i][1]." : </p></td>";			
					echo "<td><input type='text' name='description".$VAR_TABLEAU_DES_LANGUES[$i][0]."' value=''/><span style='color:red;font-size:10px'>*Obligatoire</span></td>";							
				echo "</tr>";				
			}
			echo "<tr><td colspan='2' align='center'><p>Si vous ne connaissez pas la traduction, copier-coller le text en français</p></td></tr>";
			echo "</table></tr></td>";			
		}
		echo "<input name='action' type='hidden' value='inserer'>";
		echo "<input name='idTable' type='hidden' value='".$wTabListe[$tableIndex][1]."'>";	
		echo "<tr><td align='center'><input type='submit' value='".VAR_LANG_INSERER."'></td></tr>";			
		echo "<tr><td align='center'>&nbsp;</td></tr>";
		echo "</form>";
		echo "<tr><td align='center'><form method='post' action='?menuselection=$menuselection&smenuselection=$smenuselection'><input type='submit' value='".VAR_LANG_ANNULER."'></form></td></tr>";
		
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// afficher les listes	
	////////////////////////////////////////////////////////////////////////////////////////////////////	
	else{
	
		echo "<tr><td colspan='4'><h4>Gestion des listes pour l'annuaire</h4></td></tr>";
	
		// afficher une ligne
		echo "<tr><td colspan='4'><table align='center' cellpadding='0' cellspacing='0' border='0'><tr><td bgcolor=".VAR_LOOK_COULEUR_SEPARATION."><img src=".SPACER." width='770' height='1' border='0'></td></tr></table></td></tr>";					
		
		// tableau de liste
		for($i=0;$i<count($tabListe);$i++){
			echo "<tr>";
				echo "<td align='right'><p>".$tabListe[$i][0]."</p></td>";
				echo "<td>";
				
					echo "<select name='".$tabListe[$i][1]."' id='".$tabListe[$i][1]."'>";
						$requeteSQL = "SELECT * FROM ".$tabListe[$i][1]." WHERE ".$tabListe[$i][3].$_SESSION["__langue__"]."!='' ORDER BY ".$tabListe[$i][3].$_SESSION["__langue__"];
						$recordset = mysql_query($requeteSQL);
						while($record = mysql_fetch_array($recordset)){
							echo "<option value='".$record[$tabListe[$i][2]]."'>".$record[$tabListe[$i][3].$_SESSION["__langue__"]]."</option>\n";
						}
					echo "</select>";
				echo "</td>";
				if(mysql_affected_rows() > 0)
					echo "<td><a basehref='?menuselection=$menuselection&smenuselection=$smenuselection&modificationListe=".$tabListe[$i][1]."' href='?menuselection=$menuselection&smenuselection=$smenuselection&modificationListe=".$tabListe[$i][1]."' onClick='return validerModifier();' onMouseOver='modifierLien(this,document.getElementById(\"".$tabListe[$i][1]."\"))'>".VAR_LANG_MODIFIER."</a></td>";			
				else
					echo "<td><p>".VAR_LANG_MODIFIER."</p></td>";						
				echo "<td><a href='?menuselection=$menuselection&smenuselection=$smenuselection&ajouterListe=".$tabListe[$i][1]."'>".VAR_LANG_INSERER."</a></td>";			
			echo "</tr>";
			echo "<tr><td colspan='4'><table align='center' cellpadding='0' cellspacing='0' border='0'><tr><td bgcolor=".VAR_LOOK_COULEUR_SEPARATION."><img src=".SPACER." width='770' height='1' border='0'></td></tr></table></td></tr>";			
		}
		
		// tableau de liste
		for($i=0;$i<count($tabListeNonLanguee);$i++){
			echo "<tr>";
				echo "<td align='right'><p>".$tabListeNonLanguee[$i][0]."</p></td>";
				echo "<td>";
				
					echo "<select name='".$tabListeNonLanguee[$i][1]."' id='".$tabListeNonLanguee[$i][1]."'>";
						$requeteSQL = "SELECT * FROM ".$tabListeNonLanguee[$i][1]." WHERE ".$tabListeNonLanguee[$i][3]."!='' ORDER BY ".$tabListeNonLanguee[$i][3];
						$recordset = mysql_query($requeteSQL);
						while($record = mysql_fetch_array($recordset)){
							echo "<option value='".$record[$tabListeNonLanguee[$i][2]]."'>".$record[$tabListeNonLanguee[$i][3]]."</option>\n";
						}
					echo "</select>";
				echo "</td>";
				// modification possible s'il y a au moins un element
				if(mysql_affected_rows() > 0)
					echo "<td><a basehref='?menuselection=$menuselection&smenuselection=$smenuselection&nonLangue=true&modificationListe=".$tabListeNonLanguee[$i][1]."' href='?menuselection=$menuselection&smenuselection=$smenuselection&modificationListe=".$tabListeNonLanguee[$i][1]."' onClick='return validerModifier();' onMouseOver='modifierLien(this,document.getElementById(\"".$tabListeNonLanguee[$i][1]."\"))'>".VAR_LANG_MODIFIER."</a></td>";
				else
					echo "<td><p>".VAR_LANG_MODIFIER."</p></td>";			
				echo "<td><a href='?menuselection=$menuselection&smenuselection=$smenuselection&nonLangue=true&ajouterListe=".$tabListeNonLanguee[$i][1]."'>".VAR_LANG_INSERER."</a></td>";			
			echo "</tr>";
			echo "<tr><td colspan='4'><table align='center' cellpadding='0' cellspacing='0' border='0'><tr><td bgcolor=".VAR_LOOK_COULEUR_SEPARATION."><img src=".SPACER." width='770' height='1' border='0'></td></tr></table></td></tr>";				
		}
		
		echo "<tr><td colspan='4' align='center'>";
			echo "<table width='70%'><tr><td><h4>".VAR_LANG_ATTENTION."<h4><p class='justify'>".VAR_LANG_LIST_SUPPRIMER."<p></td></tr></table>";
		echo "</td></tr>";
	}
?>
</table>
<script language="javascript">
	function modifierLien(lien, list){
		lien.href = lien.basehref+"&idSelect="+list.options[list.selectedIndex].value;
	}
</script>