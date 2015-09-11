<?
$tableArrayCache = array();

function getIdValueFromDb($idTable, $idItem , $descriptionName, $chaine, $contextErrorDisplay){

	global $tableArrayCache;

	// table en cache ?
	if(!$tableArrayCache[$idTable]){
		$tableArray = array();

		$requeteSQL = "SELECT * FROM `".$idTable."`";
		$recordset = mysql_query($requeteSQL);
		$indice = 0;
		while($record = mysql_fetch_array($recordset)){
			$tableArray[$indice]= array($record[$idItem], trim($record[$descriptionName]), trim($record[$descriptionName."Fr"]), trim($record[$descriptionName."De"]), trim($record[$descriptionName."En"]), trim($record[$descriptionName."It"]));
			$indice++;
		}
		$tableArrayCache[$idTable] = $tableArray;
	}

	for($i=0;$i<count($tableArrayCache[$idTable]);$i++){
		//echo "<br> ".strtolower($tableArrayCache[$idTable][$i][1])." ".strtolower($tableArrayCache[$idTable][$i][2])." ".strtolower($tableArrayCache[$idTable][$i][3])." ".strtolower($tableArrayCache[$idTable][$i][4])." ";
		$wChaine = trim(strtolower($chaine));
		if($wChaine == strtolower($tableArrayCache[$idTable][$i][1]) ||
				$wChaine == strtolower($tableArrayCache[$idTable][$i][2]) ||
				$wChaine == strtolower($tableArrayCache[$idTable][$i][3]) ||
				$wChaine == strtolower($tableArrayCache[$idTable][$i][4]) ||
				$wChaine == strtolower($tableArrayCache[$idTable][$i][5])){
			return $tableArrayCache[$idTable][$i][0];
		}
	}
	return 1;
}

function splitLigne($ligne){
	return split(";",$ligne);
}

// changement du nbre de colone par ligne
function testNbColonne($splittedLigne){
	$nbCol = count($splittedLigne);
	return $nbCol == 20;
}

// test la validiter d'une chaine "jj mm annee"
function validerDateForBd($chaine){
  // sans valeur
	if($chaine==""){
		return "0000-00-00";
	}
	else if(strlen($chaine)!=10){
		echo "date = '".$chaine."' => <span style='color:#CC3333'>valeur par d&eacute;faut</span> car n'est pas une date (1)<br />";
		return "0000-00-00";
	}

	$annee = substr($chaine,6,4);
	$mois = substr($chaine,3,2);
	$jour = substr($chaine,0,2);

	$date = $annee."-".$mois."-".$jour;

	if(!checkdate($mois,$jour,$annee)){
		echo "date = '".$jour.".".$mois.".".$annee."' => <span style='color:#CC3333'>valeur par d&eacute;faut</span> car n'est pas une date (2)<br />";
		return "0000-00-00";
	}
	else{
		return $date;
	}
}
?>

<br/>
<p align="center" class="titresectiontext">Importation de fichier</p>
<br/>
<form name="importerForm" enctype="multipart/form-data" method="post" action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
	<table class="options">
		<tr>
			<td>
				<p class="titresectiontext">Fichier :</p>
				<p>Fichier CSV : <input type="file" name="fichierCSV"></p>
			</td>
		</tr>
		<tr>	<td>&nbsp;	</td>	</tr>
	</table>
	<p align="center">
	<input type="submit" name="Tester" value="Tester">
	<input type="submit" name="Importer" value="Importer">
	<input type="submit" name="Init" value="Init"> </p>

<?

// un peu de cosmétique
if($_POST["Tester"]=="Tester"){
	$texte_mode = "test";
} else if($_POST["Importer"]=="Importer"){
	$texte_mode = "import";
}

// si on teste ou on execute
if($_POST["Importer"]=="Importer" || $_POST["Tester"]=="Tester"){
	echo "<br/>";
	echo "<p align='center' class='titresectiontext'>Trace - ".$texte_mode."</p>";
	echo "<table>";

	echo "<tr class=\"row\"> <td> </td>";
	echo "	<td class=\"row_club\">APRÈS </td>";
	echo "	<td class=\"row_adresse\"> Nouveau Contact / Contact modifi&eacute; </td>";
	echo "	<td class=\"row_sep\"> <br>&nbsp; </td>";
	echo "	<td class=\"row_club\">AVANT </td>";
	echo "	<td class=\"row_adresse\"> Ancien Contact / Erreur </td>";
	echo "</tr>";

/*
	$nomFichier  = $_FILES['fichierCSV']['name'];
	$tailleFichier  = $_FILES['fichierCSV']['size'];
	$nomTmp      = $_FILES['fichierCSV']['tmp_name'];
	$typeFichier   = $_FILES['fichierCSV']['type'];
	$error           = $_FILES['fichierCSV']['error'];

	echo "<br>".$_FILES['fichierCSV']['name'];
	echo "<br>".$_FILES['fichierCSV']['size'] ;
	echo "<br>".$_FILES['fichierCSV']['tmp_name'] ;
	echo "<br>".$_FILES['fichierCSV']['type'] ;
	echo "<br>".$_FILES['fichierCSV']['error'];
*/

	// en mode test/import, on regarde le fichier uploader
	if (($_POST["Tester"]=="Tester" || $_POST["Importer"]=="Importer") && $_FILES['fichierCSV']['error']) {
     switch ($_FILES['fichierCSV']['error']){
                   case 1: // UPLOAD_ERR_INI_SIZE
                   echo"<p class='titresectiontext'>Le fichier d&eacute;passe la limite autoris&eacute;e par le serveur !<p>";
                   break;
                   case 2: // UPLOAD_ERR_FORM_SIZE
                   echo "<p class='titresectiontext'>Le fichier d&eacute;passe la limite autoris&eacute;e dans le formulaire HTML !<p>";
                   break;
                   case 3: // UPLOAD_ERR_PARTIAL
                   echo "<p class='titresectiontext'>L'envoi du fichier a &eacute;t&eacute; interrompu pendant le transfert !<p>";
                   break;
                   case 4: // UPLOAD_ERR_NO_FILE
                   echo "<p class='titresectiontext'>Le fichier que vous avez envoy&eacute; a une taille nulle !<p>";
                   break;
          }
	}
	// pas d'erreur, on continue
	else {


		// ouverture du fichier, "uploadé" si Test, "temporaire" si Import
		if($_POST["Tester"]=="Tester" || $_POST["Importer"]=="Importer"){
			$fichier = fopen($_FILES['fichierCSV']['tmp_name'],'r');
		}
		$nbColValide = true;
		$premiereLecture = true;
		$ligneNumero = 0;

		// passer la premiere ligne, mais controler le nombre de colonne
		if(!feof($fichier)){
			$ligne = fgets($fichier);
			$ligneNumero++;

			$cols = splitLigne($ligne);
			$nbColValide = testNbColonne($cols);
		}

		// fichier vide
		if(feof($fichier) && $premiereLecture){
			echo "<br/><p class='titresectiontext' align='center'>Le fichier envoy&eacute; est vide !</p>";
		}
		else {
			// pour savoir si on autorise l'importation
			$test_error = 0;

			// la boucle sur les lignes
			while(!feof($fichier) && $nbColValide){
				$ligne = fgets($fichier);

				// stop if EOF
				if(feof($fichier)){
					break;
				}

				$ligneNumero++;

				// fichier vide
				if(feof($fichier) && $premiereLecture){
					echo "<br/><p class='titresectiontext' align='center'>Le fichier envoy&eacute; est vide !</p>";
					break;
				}
				$premiereLecture = false;

				$cols = splitLigne($ligne);
				$nbColValide = testNbColonne($cols);

				if($nbColValide){

					$derniereModification=date("Y-m-d H:i:s");
					$modificationPar=$_SESSION["__nom__"]." ".$_SESSION["__prenom__"];

					/*			Ordre des colones
						OrigineAdresse;			Club;
						Raison Sociale;			Civilite;						Nom;				Prenom;
						Adresse;						CP/rue option;			NPA;				Ville;		Pays;
						Tel.Prive;					Tel.Prof;						Portable;		Fax;			Email;
						Date de naissance;	Langue;
						Status;							Tchouk Up
					*/

					$origineAdresse=htmlspecialchars(validiteInsertionTextBd($cols[0]));
					$idOrigineAdresse = getIdValueFromDb("DBDOrigineAdresse","idOrigineAdresse","descriptionOrigineAdresse",$cols[0],"origine");

					$club=htmlspecialchars(validiteInsertionTextBd($cols[1]));
					$idClub = getIdValueFromDb("ClubsFstb","nbIdClub","club",$cols[1],"club");

					$raisonSociale=htmlspecialchars(validiteInsertionTextBd($cols[2]));
					$idCivilite = getIdValueFromDb("DBDCivilite","idCivilite","descriptionCivilite",$cols[3],"civili&eacute");
					$nom=ucwordspecific(strtolower(htmlspecialchars(validiteInsertionTextBd($cols[4]))),'-');
					$prenom=ucwordspecific(strtolower(htmlspecialchars(validiteInsertionTextBd($cols[5]))),'-');
					$adresse=htmlspecialchars(validiteInsertionTextBd($cols[6]));
					$cp=htmlspecialchars(validiteInsertionTextBd($cols[7]));
					$npa=htmlspecialchars(validiteInsertionTextBd($cols[8]));
					$ville=htmlspecialchars(validiteInsertionTextBd($cols[9]));
					$pays = htmlspecialchars(validiteInsertionTextBd($cols[10]));

					if($pays) { $idPays = getIdValueFromDb("DBDPays","idPays","descriptionPays",$cols[10],"pays");
					} else { $idPays=42;}

					$telPrive=htmlspecialchars(validiteInsertionTextBd($cols[11]));
					$telProf=htmlspecialchars(validiteInsertionTextBd($cols[12]));
					$portable=htmlspecialchars(validiteInsertionTextBd($cols[13]));
					$fax=htmlspecialchars(validiteInsertionTextBd($cols[14]));
					$email=htmlspecialchars(validiteInsertionTextBd($cols[15]));
					$dateNaissance=validerDateForBd($cols[16]);
					$langue=htmlspecialchars(validiteInsertionTextBd($cols[17]));
					$idLangue = getIdValueFromDb("DBDLangue","idLangue","descriptionLangue",$cols[17],"langue");

					$statut=htmlspecialchars(validiteInsertionTextBd($cols[18]));
					$idStatus = getIdValueFromDb("DBDStatus","idStatus","descriptionStatus",$cols[18],"status");
					$CHTB = $cols[19];
					$idCHTB= getIdValueFromDb("DBDCHTB","idCHTB","descriptionCHTB",$cols[19], "tchoukup");

					// test s'il existe deja qqun de m?me nom pour avertir l'utilisateur d'un potentiel doublon
					$requeteSQL = "SELECT * FROM `DBDPersonne` WHERE `nom` LIKE '$nom' AND `prenom` LIKE '$prenom'";
					$recordSet = mysql_query($requeteSQL);
	//echo "<br>$requeteSQL<br>";

					echo "<tr class=\"row\"> <td> ".$texte_mode.": ligne $ligneNumero :  </td>";

					// l'origine est pas fix?e, on arr?te
					if($idOrigineAdresse==1){
						echo "<td  class=\"row_club\" style=\"color:red\"> Erreur d'origine : <br>".$origineAdresse." </td>";
						$test_error+=1;
					} else
					// new contact
					if(mysql_affected_rows() == 0){

						echo "<td  class=\"row_club\" style=\"color:green\">Origine: ".$origineAdresse."<br>".$club."<br>Tchouk Up: ".$CHTB."<br />Statut: ".$statut."</td>";
						echo "<td class=\"row_adresse\" > <span style=\"color:green;font-weight:bold\">".$nom." ".$prenom."</span> <br> <span style=\"color:green\">".$adresse." <br> ".$npa." ".$ville." </span> </td>";

									$requeteSQL = "INSERT INTO `DBDPersonne` (".
													"`idStatus`,".
													"`derniereModification`,".
													"`modificationPar`,".
													"`editor_id`".
													"`idCivilite`,".
													"`nom`,".
													"`prenom`,".
													"`adresse`,".
													"`npa`,".
													"`cp`,".
													"`ville`,".
													"`telPrive`,".
													"`telProf`,".
													"`portable`,".
													"`fax`,".
													"`email`,".
													"`dateNaissance`,".
													"`raisonSociale`,".
													"`idLangue`,".
													"`idOrigineAdresse`,".
													"`idClub`,".
													"`idPays`,".
													"`idCHTB`".
										") VALUES (".
													"'$idStatus',".
													"'$derniereModification',".
													"'$modificationPar',".
													$_SESSION['__idUser__'].",".
													"'$idCivilite',".
													"'$nom',".
													"'$prenom',".
													"'$adresse',".
													"'$npa',".
													"'$cp',".
													"'$ville',".
													"'$telPrive',".
													"'$telProf',".
													"'$portable',".
													"'$fax',".
													"'$email',".
													"'$dateNaissance',".
													"'$raisonSociale',".
													"'$idLangue',".
													"'$idOrigineAdresse',".
													"'$idClub',".
													"'$idPays',".
													"'$idCHTB'".
										")";
	//echo "<br>$requeteSQL<br>";
						if($_POST["Importer"]=="Importer"){
							if(mysql_query($requeteSQL)===false){
								printErrorMessage("Erreur d'insertion : les donn&eacute;es sont peut-&ecirc;tre non valide.");
							}
							else{
								echo "<td class=\"row_sep\"> &nbsp; </td>";
								echo "<td class=\"row_club\" style=\"color:blue\">ins&eacute;r&eacute;</td>";
								echo "<td class=\"row_adresse\"> &nbsp; </td>";
							}
						} //end if importation
						elseif($_POST["Tester"]=="Tester"){
								echo "<td class=\"row_sep\"> &nbsp; </td>";
								echo "<td class=\"row_club\"> &nbsp; </td>";
								echo "<td class=\"row_adresse\"> &nbsp; </td>";
						}
					} // end new contact
					// update
					else if(mysql_affected_rows() == 1){

						echo "<td class=\"row_club\" style=\"color:blue\">Origine: ".$origineAdresse."<br />".$club."<br />Tchouk Up: ".$CHTB."<br />Statut: ".$statut."</td>";
						echo "<td class=\"row_adresse\" > <span style=\"color:blue;font-weight:bold\">".$nom." ".$prenom."</span> <br> <span style=\"color:blue\">".$adresse." <br> ".$npa." ".$ville." </span> </td>";

						$record = mysql_fetch_array($recordSet);
						$idDbdPersonne = $record["idDbdPersonne"];

						/*

						TROP DE REQUETES, TOUT PEUT ETRE REGROUPER !!!

						*/

						// récupère la description des champs club, chtb, origine pour l'affichage
						$requeteClub = "SELECT club FROM ClubsFstb WHERE NbIdClub=\"".$record["idClub"]."\"";
						$recordsetClub = mysql_query($requeteClub);
						$recordClub = mysql_fetch_array($recordsetClub);

						$requeteCHTB = "SELECT descriptionCHTBFr FROM DBDCHTB WHERE idCHTB=\"".$record["idCHTB"]."\"";
						$recordsetCHTB = mysql_query($requeteCHTB);
						$recordCHTB = mysql_fetch_array($recordsetCHTB);

						$requeteOrigine = "SELECT descriptionOrigineAdresseFr FROM DBDOrigineAdresse WHERE IDORIGINEADRESSE=\"".$record["idOrigineAdresse"]."\"";
						$recordsetOrigine = mysql_query($requeteOrigine);
						$recordOrigine = mysql_fetch_array($recordsetOrigine);

						$requeteStatut = "SELECT descriptionStatusFr AS statut FROM DBDStatus WHERE idStatus=".$record['idStatus'];
						$recordsetStatut = mysql_query($requeteStatut);
						$recordStatut = mysql_fetch_array($recordsetStatut);
		                 // si CHTB passage de "non" ou "refusé" a "oui"
						/*if(($record["idCHTB"]==3 OR $record["idCHTB"]==4) AND $idCHTB==2){
							echo "<td class=\"row_sep\"> &nbsp; </td>";
							echo "<td class=\"row_club\" style=\"color:red;font-weight:bold;\">Choix Tchouk Up diff&eacute;rent<br />".$recordCHTB["descriptionCHTBFr"]."</td>";
							$test_error += 1;
						} //Différence club. Deux personnes avec le même nom et prénom peuvent être dans deux clubs différents.
						else*/if($idClub!=$record["idClub"] AND $record["idClub"]!="15"){ // 15 = aucun club
							echo "<td class=\"row_sep\"> &nbsp; </td>";
							echo "<td class=\"row_club\" style=\"color:red;font-weight:bold;\">Diff&eacute;rent club<br />".$recordClub["club"]." (".$record["idClub"].")<br />Statut: ".$recordStatut['statut']."</td>";
							$test_error += 1;
						} // Différence d'origine, stop, faire vérifier PLUS BESOIN Car mtn on le voit en orange et en général on modifie
						/*elseif($idOrigineAdresse!=$record["idOrigineAdresse"]){
							echo "<td class=\"row_sep\"> &nbsp; </td>";
							echo "<td class=\"row_club\" style=\"color:red;font-weight:bold;\"> Diff&eacute;rence Origine <br />".$recordOrigine["descriptionOrigineAdresseFr"]."</td>";
							$test_error += 1;
						// si arbitre, on ne l'enlève pas!! On garde pour éviter de remettre plus tard  PLUS BESOIN Car si rien dans données nécaissaire à l'arbitre dans ce que donne la feuille, ça ne supprime pas les données.
						else if($record["idArbitre"]>1){
							$test_error +=1;
							echo "<td class\"row_sep\"> &nbsp; </td>";
							echo "<td class=\"row_club\" style=\"color:red;font-weight:bold;\"> Arbitre!! </td>";
				        }*/ // si CHTB indéfini
						elseif($idCHTB==1){
							echo "<td class=\"row_sep\"> &nbsp; </td>";
							echo "<td class=\"row_club\" style=\"color:red;font-weight:bold;\">Choix Tchouk Up ind&eacute;fini<br />".$recordCHTB["descriptionCHTBFr"]."</td>";
						} // la mise à jour semble ok... continue.
						else {
							// affiche le contact à modifier
							echo "<td class=\"row_sep\"> &nbsp; </td>";
							if($_POST["Tester"]=="Tester"){

                                $requeteClub = "SELECT club FROM ClubsFstb WHERE NbIdClub=\"".$record["idClub"]."\"";
                                $recordsetClub = mysql_query($requeteClub);
                                $recordClub = mysql_fetch_array($recordsetClub);

                                if($recordClub["club"]==""){
                                    $nomClub="Aucun club";
                                }
                                else{
                                    $nomClub=$recordClub["club"];
                                }

                                $couleurOrigine="blue";
                                $couleurClub="blue";
                                $couleurTchoukUp="blue";
                                // Différence d'origine, met en orange mais modifie quand même
                                if($idOrigineAdresse!=$record["idOrigineAdresse"]){
                                    $couleurOrigine="orange";
                                } // Changement de club, met en orange mais modifie quand même N'ARRIVERA PAS Car empêchement de la modification ci-dessus.
                                if($idClub!=$record["idClub"]){
                                    $couleurClub="orange";
                                } // Changement de Tchouk Up de "oui" à "non" ou "refusé", met en orange mais modifie quand même
                                if(($idCHTB==3 OR $idCHTB==4) AND $record["idCHTB"]==2){
                                    $couleurTchoukUp="orange";
                                } // Changement de Tchouk Up de "non" ou "refusé" à "oui", met en rouge mais modifie quand même
                                if(($record["idCHTB"]==3 OR $record["idCHTB"]==4) AND $idCHTB==2){
                                    $couleurTchoukUp="red";
                                }
								echo "<td class=\"row_club\">
								        <span style=\"color:".$couleurOrigine.";\"> Origine: ".$recordOrigine["descriptionOrigineAdresseFr"]."</span><br />
								        <span style=\"color:".$couleurClub.";\">".$nomClub."</span><br />
								        <span style=\"color:".$couleurTchoukUp.";\">Tchouk Up: ".$recordCHTB["descriptionCHTBFr"]."</span>
								    </td>";
							}


							// requete de modif, certains champs ne changent que si renseignés
							// id's ont des valeurs par défaut, les autres si nécessaire
							$requeteSQL="UPDATE DBDPersonne SET ".
														"`idStatus`='$idStatus',".
														"`derniereModification`='$derniereModification',".
														"`modificationPar`='$modificationPar',".
														"`editor_id`=".$_SESSION["__idUser__"].",";
							if($raisonSociale)$requeteSQL=$requeteSQL.
														"`raisonSociale`='$raisonSociale',";
							$requeteSQL=$requeteSQL.
														"`idCivilite`='$idCivilite',".
														"`nom`='$nom',".
														"`prenom`='$prenom',";
							if($adresse)$requeteSQL=$requeteSQL.
														"`adresse`='$adresse',";
							if($npa)$requeteSQL=$requeteSQL.
														"`npa`='$npa',";
							if($cp)$requeteSQL=$requeteSQL.
														"`cp`='$cp',";
							if($ville)$requeteSQL=$requeteSQL.
														"`ville`='$ville',";
							if($telPrive)$requeteSQL=$requeteSQL.
														"`telPrive`='$telPrive',";
							if($telProf)$requeteSQL=$requeteSQL.
														"`telProf`='$telProf',";
							if($portable)$requeteSQL=$requeteSQL.
														"`portable`='$portable',";
							if($fax)$requeteSQL=$requeteSQL.
														"`fax`='$fax',";
							if($email)$requeteSQL=$requeteSQL.
														"`email`='$email',";
							if($pays) { $requeteSQL=$requeteSQL.
														"`idPays`='$idPays',";
							} else { $requeteSQL=$requeteSQL.
														"`idPays`='42',";} // 42 = Suisse
							$requeteSQL=$requeteSQL.
														"`dateNaissance`='$dateNaissance',".
														"`idLangue`='$idLangue',".
														"`idOrigineAdresse`='$idOrigineAdresse',".
														"`idClub`='$idClub',".
														"`idCHTB`='$idCHTB'".
													" WHERE idDbdPersonne=$idDbdPersonne";
	//echo "<br>$requeteSQL<br>";
// 					echo "<td class=\"row_sep\"> <br>&nbsp; </td>";
							if($_POST["Importer"]=="Importer"){
								if(mysql_query($requeteSQL)===false){
									echo "<td class=\"row_club\" style=\"color:blue\">Erreur de modification, les donnees sont peut-être non valide.</td>";
								}
								else{
									echo "<td class=\"row_club\" style=\"color:blue\">modifi&eacute;</td>";
								}
							}//end if importation
						}//end else
                        if($_POST["Tester"]=="Tester"){
                            echo "<td class=\"row_adresse\" >
                                    <span style=\"color:blue;font-weight:bold\">".$record["nom"]." ".$record["prenom"]."</span><br />
                                    <span style=\"color:blue\"> ".$record["adresse"]." <br /> ".$record["npa"]." ".$record["ville"]."</span>
                                </td>
                            </tr>";
                        }//end if tester
                        else{
                            echo "<td class=\"row_adresse\" >&nbsp;</td>
                            </tr>";
                        }
					} // end update
					// ne sais pas quoi faire... trop de source
					else{
						$test_error += 1;

						//indique le contact, et msg d'erreur
						echo "<td  class=\"row_club\" style=\"color:red\">".$club."<br>Tchouk Up: ".$CHTB." </td>";
						echo "<td class=\"row_adresse\" > <span style=\"color:red;font-weight:bold\">".$nom." ".$prenom."</span> <br> <span style=\"color:red\">".$adresse." <br> ".$npa." ".$ville." </span> </td>";
						echo "<td class=\"row_sep\"> <br>&nbsp; </td>";
						echo "<td  class=\"row_club\" style=\"color:red\"></td>";
						echo "<td class=\"row_adresse\" style=\"color:red\"> Trop de contact avec le meme nom </td>";
					}
				} // end of if nbColValid
				else{
					$test_error +=1;
					echo "<p><span class='titresectiontext'>La ligne $ligneNumero &agrave; pos&eacute; un probl&egrave;me : </span>".$ligne."</p>";
					echo "<p class='titresectiontext'>Tous ce qui se trouvent avant cette ligne &agrave; &eacute;t&eacute; correctement enregistr&eacute;.</p>";
					echo "<p class='titresectiontext'>Causes probables : le nombre de colonne n'est pas valide, il y a des espaces en fin de fichier ou il y a un point virgule dans les donn&eacute;es.</p>";
					echo "<br/><br/>";
					// end of task
					$nbColValide = false;
				} // end of else nbColValid
			}// end of while
			echo "</table>";
			echo '<br/><p class="titresectiontext" align="center">Fin du traitement</p><br/>';
			// si on a pas d'erreurs lors du test, on affiche le bouton d'importation
			// et on copie le fichier dans /tmp/tmpfile
			if(!$test_error){

			}
		}
	}
}
?>
</form>
<table class="options">
		<tr>
			<td>
				<p class="titresectiontext">Format :</p>
				<p>Le fichier CVS doit &ecirc;tre format&eacute; avec des '<span class="titresectiontext">;</span>' comme s&eacute;parateur de colonne et puis directement la valeur. <br/>Exemple :
				Monsieur;Dupont;Jean;...
				</p>
				<br>
				<p class="titresectiontext">Voici l'ent&ecirc;te du fichier CSV :</p>
				<p><span style="color:red;font-weight:bold;">Attention l'orde est tr&egrave;s important</span>, sinon vous risquez d'avoir des donn&eacute;es crois&eacute;es : exemple ville a la place du pr&eacute;nom.</p>
				<p>Ent&ecirc;te : </p>
				<p>OrigineAdresse;Club;Raison Sociale;Civilite;Nom;Prenom;Adresse;CP/rue option;NPA;Ville;Pays;Tel.Prive;Tel.Prof;Portable;Fax;Email;Date de naissance;Langue;Status;tchoukup
</p>
			</td>
		</tr>
	</table>