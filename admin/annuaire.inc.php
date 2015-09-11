<form action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" method="post">
<table width="600px" align='center' cellpadding="0" cellspacing="0" border="0"><tr><td><?php
	statInsererPageSurf(__FILE__);
	$lettreA = 65;
	$modeInterMots = $_POST['modeInterMots'];
	$optionRechercheAvancee = $_GET['optionRechercheAvancee'];
	$motsRecherches = mysql_escape_string($_POST['motsRecherches']);
	$lettre = mysql_escape_string($_GET['lettre']);
	echo "<p align='center'>";
	for($i=$lettreA;$i<$lettreA+26;$i++){
		echo "<a href='?lettre=".chr($i)."&menuselection=$menuselection&smenuselection=$smenuselection'>".chr($i)."</a>-";
	}
	echo "<a href='?menuselection=$menuselection&smenuselection=$smenuselection&lettre=all'>Tout</a>";
	echo "</p>";
?></td></tr>
<tr><td>
					<br><p align='center'>Recherche sur le nom, pr&eacute;nom, adresse, npa, ville, email, club et remarque (s&eacute;par&eacute; par des espaces)<br/>Mode entre les mots de recherches : ET <input type="radio" name="modeInterMots" value="AND" class="couleurCheckBox" <?php if($modeInterMots!="OR")echo "checked"; ?> /> OU <input type="radio" name="modeInterMots" value="OR" class="couleurCheckBox" <?php if($modeInterMots=="OR")echo "checked"; ?> /> : <input type="text" name="motsRecherches" size="35" value='<? echo $motsRecherches; ?>'>&nbsp;<input type="submit" value="Rechercher">
					</p><p align="right">
					<?
						// switch search option
						if(isset($optionRechercheAvancee) && $optionRechercheAvancee=="true"){
							$_SESSION["__rechercheAvancee__"]=true;
						}
						else if(isset($optionRechercheAvancee) && $optionRechercheAvancee=="false"){
							$_SESSION["__rechercheAvancee__"]=false;
						}
						// default : none
						else if(!isset($_SESSION["__rechercheAvancee__"])){
							$_SESSION["__rechercheAvancee__"]=false;
						}

						if($_SESSION["__rechercheAvancee__"]){
							echo "<a href='?menuselection=$menuselection&smenuselection=$smenuselection&optionRechercheAvancee=false'>Masquer la recherche avancée</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						}
						else{
							echo "<a href='?menuselection=$menuselection&smenuselection=$smenuselection&optionRechercheAvancee=true'>Recherche avancée</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						}
					?>
					</p>
</td></tr></table>
<?
	$nbCondition = 5;

	if($_SESSION["__rechercheAvancee__"]){
		// table des listes : noms, id table BD, id col, nom description (non langue).
		$tabListe = array(array(VAR_LANG_DBD_ARBITRE,"DBDArbitre","idArbitre","descriptionArbitre"),
											array(VAR_LANG_DBD_AUTRE_FONCTION,"DBDAutreFonction","idAutreFonction","descriptionAutreFonction"),
											array(VAR_LANG_DBD_CHTB,"DBDCHTB","idCHTB","descriptionCHTB"),
											array(VAR_LANG_DBD_CIVILITE,"DBDCivilite","idCivilite","descriptionCivilite"),
											array(VAR_LANG_DBD_FORMATION,"DBDFormation","idFormation","descriptionFormation"),
											array(VAR_LANG_DBD_LANGUE,"DBDLangue","idLangue","descriptionLangue"),
											//array(VAR_LANG_DBD_RAISON_SOCIALE,"DBDRaisonSociale","idRaisonSociale","descriptionRaisonSociale"),
											array(VAR_LANG_DBD_ORIGINE_ADRESSE,"DBDOrigineAdresse","idOrigineAdresse","descriptionOrigineAdresse"),
											array(VAR_LANG_DBD_STATUS,"DBDStatus","idStatus","descriptionStatus"),
											array(VAR_LANG_DBD_PAYS,"DBDPays","idPays","descriptionPays"),
											array(VAR_LANG_DBD_MEDIA_TYPE,"DBDMediaType","idMediaType","descriptionMediaType"),
											array(VAR_LANG_DBD_MEDIA_CANTON,"DBDMediaCanton","idMediaCanton","descriptionMediaCanton")
													);

		$tabListeNonLanguee = array(array(VAR_LANG_CLUB_FSTB,"ClubsFstb","nbIdClub","club"));
/*
possible avec le distinct

		echo "<script language='javascript'>";
			echo "function checkOperator(index){
							var wSelection = document.getElementById('conditionChamp'+index);
							var wOperator = document.getElementById('conditionOperateur'+index);
							if(wSelection.value=='DBDFormation' ||
														wSelection.value=='DBDAutreFonction'){
								wOperator.value = 'like';
								wOperator.disabled = true;
							}
							else{
								wOperator.disabled = false;
							}

						}";
		echo "</script>";*/

		echo "<table align='center' width='70%'>";
			for($i=1;$i<=$nbCondition;$i++){
				echo "<tr>";
					echo "<td align='center'>";
						afficherConditionRecherce($i, $tabListe, $tabListeNonLanguee);
					echo "</td>";
				echo "</tr>";
			}
			echo "<tr>";
				echo "<td>";
					echo "<p>Le text ins&eacute;rer dans un champ d'une recherche avanc&eacute;e peut-être une partie de la valeur. La recherche ne s'effectue pas sur plusieurs mots. Les filtres s'additionnent.</p>";
				echo "</td>";
			echo "</tr>";
		echo "</table>";

		echo "<input type='hidden' value='true' name='rechercheAvancee'/>";
	}
?>
</form>
<br>
<script language="JavaScript">
	function validerSuppression(){
		return confirm("Etes-vous sur de vouloir supprimer ce contact ?");
	}
	function validerModifier(){
		return confirm("Etes-vous sur de vouloir modifier ce contact ?");
	}
</script>

<?

//	$tableRequises = "`DBDPersonne`, `DBDStatus`, `DBDOrigineAdresse`, `ClubsFstb`, `DBDLangue`, `DBDRaisonSociale`, `DBDCivilite`, `DBDPays`, `DBDCHTB`, `DBDArbitre`, `DBDMediaType`, `DBDMediaCanton`";
	$tableRequises = "`DBDPersonne`, `DBDStatus`, `DBDOrigineAdresse`, `ClubsFstb`, `DBDLangue`, `DBDCivilite`, `DBDPays`, `DBDCHTB`, `DBDArbitre`, `DBDTypeCompte`, `DBDMediaType`, `DBDMediaCanton`";
	$jointure = "`DBDPersonne`.`idStatus`=`DBDStatus`.`idStatus`"." AND ".
							"`DBDPersonne`.`idOrigineAdresse`=`DBDOrigineAdresse`.`idOrigineAdresse`"." AND ".
							"`DBDPersonne`.`idClub`=`ClubsFstb`.`nbIdClub`"." AND ".
							"`DBDPersonne`.`idLangue`=`DBDLangue`.`idLangue`"." AND ".
							"`DBDPersonne`.`idCivilite`=`DBDCivilite`.`idCivilite`"." AND ".
							"`DBDPersonne`.`idPays`=`DBDPays`.`idPays`"." AND ".
							"`DBDPersonne`.`idCHTB`=`DBDCHTB`.`idCHTB`"." AND ".
							//"`DBDPersonne`.`idRaisonSociale`=`DBDRaisonSociale`.`idRaisonSociale`"." AND ".
							"`DBDPersonne`.`idArbitre`=`DBDArbitre`.`idArbitre`"." AND ".
							"`DBDPersonne`.`typeCompte`=`DBDTypeCompte`.`idTypeCompte`"." AND ".
							"`DBDPersonne`.`idMediaType`=`DBDMediaType`.`idMediaType`"." AND ".
							"`DBDPersonne`.`idMediaCanton`=`DBDMediaCanton`.`idMediaCanton`";

	if($lettre=="all"){
		  $requeteSQL = "SELECT * FROM ".$tableRequises." WHERE ".$jointure." ORDER BY nom, prenom, raisonSociale";
	}
	else if($lettre!=""){
		$requeteSQL = "SELECT * FROM ".$tableRequises." WHERE `nom` LIKE '".$lettre."%' AND ".$jointure." ORDER BY nom, prenom";
	}
	else if($motsRecherches!="" || isset($_POST["rechercheAvancee"]) && $_POST["rechercheAvancee"]=="true"){

		// separation des mots
		$tok = strtok($motsRecherches," ");
		$possibilite="";
		$nbFois=0;

		// pour tous les mots
		while ($tok) {
			if($nbFois>0){
				$possibilite .= " $modeInterMots ";
			}
			$nbFois++;
			$possibilite .= "(`nom` LIKE '%".$tok."%' OR
											`prenom` LIKE '%".$tok."%' OR
											`raisonSociale` LIKE '%".$tok."%' OR
											`ville` LIKE '%".$tok."%' OR
											`email` LIKE '%".$tok."%' OR
											`adresse` LIKE '%".$tok."%' OR
											`club` LIKE '%".$tok."%' OR
											`remarque` LIKE '%".$tok."%' OR
											`npa` LIKE '%".$tok."%')";


			$tok = strtok(" ");
		}

		$optionRechercheAvancee = "";
		$jointureAutreFonctionAjoutee = false;
		$jointureFormationAjoutee = false;

		// mode avance
		if(isset($_POST["rechercheAvancee"]) && $_POST["rechercheAvancee"]=="true"){
			for($i=1;$i<=$nbCondition;$i++){
				// traiter seulement les options selectionnees.
				if($_POST["conditionChamp".$i]!="null"){

					if($optionRechercheAvancee!=""){
						$optionRechercheAvancee .= " AND";
					}

					// liste multiple
					if($_POST["conditionChamp".$i]=="DBDAutreFonction"){
						if(!$jointureAutreFonctionAjoutee){
							$jointureAutreFonctionAjoutee = true;
							$tableRequises .= " ,`DBDRegroupementAutreFonction`, `DBDAutreFonction`";
							$jointure.= " AND `DBDRegroupementAutreFonction`.`idBDBPersonne`=`DBDPersonne`.`idDbdPersonne` AND `DBDRegroupementAutreFonction`.`idAutreFonction`=`DBDAutreFonction`.`idAutreFonction`";
						}
						//$_POST["conditionOperateur".$i] = "lssike";
					}
					else if($_POST["conditionChamp".$i]=="DBDFormation"){
						if(!$jointureFormationAjoutee){
							$jointureFormationAjoutee = true;
							$tableRequises .= " ,`DBDRegroupementFormation`, `DBDFormation`";
							$jointure.= " AND `DBDRegroupementFormation`.`idBDBPersonne`=`DBDPersonne`.`idDbdPersonne` AND `DBDRegroupementFormation`.`idFormation`=`DBDFormation`.`idFormation`";
						}
						//$_POST["conditionOperateur".$i] = "like";
					}

					// non langue ?
					if(substr($_POST["conditionChamp".$i],0,6)=="__NL__"){
						$wTable = substr($_POST["conditionChamp".$i],6);

						// recherche de la bonne table
						for($j=0;$j<count($tabListeNonLanguee);$j++){
							if($tabListeNonLanguee[$j][1]==$wTable){
								if($_POST["conditionOperateur".$i]=="like"){
									$optionRechercheAvancee .= " `".$tabListeNonLanguee[$j][3]."` LIKE '%".$_POST["conditionValeur".$i]."%'";
								}
								else{
									$optionRechercheAvancee .= " `".$tabListeNonLanguee[$j][3]."` NOT LIKE '%".$_POST["conditionValeur".$i]."%'";
								}
								break;
							}
						}
					}
					else{
						$wTable = $_POST["conditionChamp".$i];

						// recherche de la bonne table
						for($j=0;$j<count($tabListe);$j++){
							if($tabListe[$j][1]==$wTable){
								if($_POST["conditionOperateur".$i]=="like"){
									$optionRechercheAvancee .= " `".$tabListe[$j][3].$_SESSION["__langue__"]."` LIKE '%".$_POST["conditionValeur".$i]."%'";
								}
								else{
									$optionRechercheAvancee .= " `".$tabListe[$j][3].$_SESSION["__langue__"]."` NOT LIKE '%".$_POST["conditionValeur".$i]."%'";
								}
								break;
							}
						}

					}
				}
			}
		}

		// add filter
		if($possibilite==""){
			$filtre = $optionRechercheAvancee;
		}
		else if($optionRechercheAvancee==""){
			$filtre = $possibilite;
		}
		else{
			$filtre = "(".$possibilite.") AND (".$optionRechercheAvancee.")";
		}

		$requeteSQL = "SELECT DISTINCT * FROM ".$tableRequises." WHERE ".$jointure." AND (".$filtre.") ORDER BY `nom`, `prenom`";

	}
	else{
		$lettre = "A";
		$requeteSQL = "SELECT * FROM ".$tableRequises." WHERE `nom` LIKE 'A%' AND ".$jointure." ORDER BY `nom`, `prenom`";
	}

	if($lettre!="" && $lettre!="all"){
		echo "<h4>Tri&eacute; par la lettre : $lettre</h4><br/>";
	}

	// garde la dernière requete dans la session pour le cas de l'exportation
	// SUPPRIMEE DANS PHP 5.4
	//session_register("__requetePourExportation__",$requeteSQL);

	$_SESSION["__requetePourExportation__"]=$requeteSQL;

	//echo $requeteSQL;

	$recordset = mysql_query($requeteSQL) or die ("<H1>Mauvaise requete, contactez le webmaster</H1>");

	if($_SESSION["__userLevel__"]<=5){
		$nbColonne=5;
	}
	else{
		$nbColonne=4;
	}
	$bordure=true;

	echo "<table class='tableauAnnuaire'>";

	echo "<tr>";
        echo "<th>Personne</th>";
        echo "<th>Contact</th>";
        echo "<th>Club</th>";
        echo "<th>Divers</th>";
        if($_SESSION["__userLevel__"]<=5){
            echo "<th>Gestion</th>";
        }
	echo "</tr>";
	$premiereFois=0;
	$listeEmail = array();
	while($record = mysql_fetch_array($recordset))
	{
        echo "<tr>";
            echo "<td>";
            if($record["raisonSociale"]!="") echo $record["raisonSociale"]."<br />";
            echo $record["descriptionCivilite".$_SESSION["__langue__"]]."<br />".$record["nom"]."&nbsp;".$record["prenom"]."<br>".$record["adresse"]."<br />".($record["cp"]!=''?$record["cp"]."<br />":'')
                                                .$record["npa"]."&nbsp;".$record["ville"]."<br />".$record["descriptionPays".$_SESSION["__langue__"]]."</td>";

            echo "<td>Tel. privé : ".$record["telPrive"]."<br />Tel. prof. : ".$record["telProf"]."</p><p>Portable : ".$record["portable"]."</p><p>Fax : ".$record["fax"]."<br />";
                if($record["email"]!=""){
                	echo email($record["email"]);
                	$listeEmail[$premiereFois]=$record["email"];
                }
                else{
                	echo "&nbsp;";
                }
            echo "</td>";

            echo "<td>".$record["club"]."<br />".$record["descriptionStatus".$_SESSION["__langue__"]]."</td>";

            echo "<td>";
                echo "Modifié le : ".date_sql2date($record["derniereModification"]);
                echo "<br />";
                echo "Né le : ".date_sql2date($record["dateNaissance"]);
                echo "<br />";
                echo "Langue : ".$record["descriptionLangue".$_SESSION["__langue__"]];
                echo "<br />";
                echo "Origine : ".$record["descriptionOrigineAdresse".$_SESSION["__langue__"]];


            echo "</td>";

            if($_SESSION["__userLevel__"]<=5){
                echo "<td>";
                echo "<a href='?menuselection=$menuselection&smenuselection=$smenuselection&modificationIdAnnuaire=".$record["idDbdPersonne"]."'>Afficher / Modifier</a>";
                echo "<br />";
                echo "<a href='?menuselection=$menuselection&smenuselection=$smenuselection&suppressionIdAnnuaire=".$record["idDbdPersonne"]."' onClick='return validerSuppression();'>supprimer</a>";
                echo "</td>";
            echo "</tr>";
		}
		$premiereFois++;
	}
?></table>
<table align='center' width="92%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="right"><p>
<?
	echo "Nombre de contact(s) : $premiereFois";
?></p></td></tr></table>

<table align="center">
	<tr>
		<td>
			<form target="_blank" name="fullExport" action="admin/annuaire.exportation.csv.inc.php" method="post">
				<input type="submit" name="Exporter" value="Exporter toute la base">
				<input type="hidden" name="action" value="fullExport">
			</form>
		</td>
		<td>
			<form target="_blank" name="screenExport" action="admin/annuaire.exportation.csv.inc.php" method="post">
				<input type="submit" name="Exporter" value="Exporter ce qui est affiché">
				<input type="hidden" name="action" value="screenExport">
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?
			$listeEmailSeparationVirgules="";
			for($k=0;$k<$premiereFois;$k++){
				if($listeEmail[$k]!=""){
					if($k!=0){
						$listeEmailSeparationVirgules.=",";
					}
					$listeEmailSeparationVirgules.=$listeEmail[$k];
				}
			}
			?>
			<a href="mailto:<? echo $listeEmailSeparationVirgules; ?>">Envoyer un mail aux personnes affichées et ayant une adresse.</a>
	</tr>
</table>