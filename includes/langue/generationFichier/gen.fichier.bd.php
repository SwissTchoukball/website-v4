<?php
// option
// * $silentMode      : if is defined as true => no feedback.
// * $embedded        : if is deined as true => no open/close the db connection
// * $initialFilePath : root path for file création : exemple -> mydir/

if(!isset($silentMode))$silentMode=false;
if(!isset($embedded))$embedded=false;
if(!isset($initialFilePath))$initialFilePath="./";


// generation de fichier php (contenant des variables) pour les langues.

if(!isset($VAR_TABLEAU_DES_LANGUES)){include "../tab.langue.inc.php";}
$nbLangue = count($VAR_TABLEAU_DES_LANGUES);

function convertText($text){

	$text = str_replace('à','&agrave;',$text);
	$text = str_replace('é','&eacute;',$text);
	$text = str_replace('è','&egrave;',$text);
	$text = str_replace('ü','&uuml;',$text);
	$text = str_replace('ö','&ouml;',$text);
	$text = str_replace('ä','&auml;',$text);
	$text = str_replace('ç','&ccedil;',$text);

	$text = nl2br($text);
	return str_replace('"','\"',$text);
}

// test les fichiers pour les droits en ecriture : dernier parametre optionel
function testerDroitEcritureFichiers($VAR_TABLEAU_DES_LANGUES, $filePathLocation){
	for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
		if(!fichierDroitEnEcriture($filePathLocation."var.bd.".$VAR_TABLEAU_DES_LANGUES[$i][0].".inc.php")){
			return false;
		}
	}
	return true;
}

// test 1 fichier pour les droits en ecriture
function fichierDroitEnEcriture($nomfichier){
	if (!is_writable($nomfichier)) {
		echo "Le fichier $nomfichier n'est pas accessible en écriture<br>";
		return false;
	}
	return true;
}

// ouvrir tous les fichiers : dernier parametre optionel
function ouvrirLesFichiers($VAR_TABLEAU_DES_LANGUES,$modeOuverture,$filePathLocation){
	$tab_fichiers;
	for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
		$tab_fichiers[$i] = ouvrirFichier($filePathLocation."var.bd.".$VAR_TABLEAU_DES_LANGUES[$i][0].".inc.php",$modeOuverture);
	}
	return $tab_fichiers;
}

// ouvrir un fichier
function ouvrirFichier($nomfichier, $modeOuverture){
	if (!$fichier = fopen($nomfichier,$modeOuverture)) {
    echo "Impossible d'ouvrir le fichier ($nomfichier)<br>";
    exit;
  }
	return $fichier;
}

function ecrireDansTousLesFichiers($tab_fichier,$tab_text,$VAR_TABLEAU_DES_LANGUES,$filePathLocation){
	for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
		ecrireDansFichier($tab_fichier[$i],$tab_text[$i],$filePathLocation."var.bd.".$VAR_TABLEAU_DES_LANGUES[$i][0].".inc.php");
	}
}

function ecrireDansFichier($fichier,$text,$nomfichier){

	// Ecriture dans le fichier
	if (fwrite($fichier, $text) === FALSE) {
		echo "Impossible d'écrire dans le fichier ($nomfichier)<br>";
		exit;
	}
  //echo "L'écriture dans le fichier ($nomfichier) a réussi<br>";
}

// le recordset sur la table personne et le tableau de text en parametre
function insererPersonneEquipeNationale($recordset, $tab_text, $nbLangue, $VAR_TABLEAU_DES_LANGUES){

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		//echo "$nbFois ";
		// nouvel element ==> virgule
		if($nbFois>0){
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i].",\n";
			}
		}
		for($i=0;$i<$nbLangue;$i++){
			$tab_text[$i] = $tab_text[$i]."array(\"".convertText($record["nom"])."\",\"".convertText($record["prenom"])."\",\"".convertText($record["foncEncad".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".convertText($record["club"])."\",\"".convertText($record["experience"])."\")";
		}
		$nbFois++;
	}
	return $tab_text;
}

if(testerDroitEcritureFichiers($VAR_TABLEAU_DES_LANGUES,$initialFilePath)){

	$tab_fichiers = ouvrirLesFichiers($VAR_TABLEAU_DES_LANGUES,"w",$initialFilePath);
	//-----------------------------------------------------------------------------------------------------------
	// Debut
  	//-----------------------------------------------------------------------------------------------------------
	if(!$embedded)@mysql_pconnect("localhost","fstb-dev","fstb-dev") or die ("<H1>Connexion impossible au serveur</H1>");
	if(!$embedded)@mysql_select_db("tchoukball1") or die ("<H1>Connexion impossible à la base de donnée</H1>");

	//************************************************* MENU_WEB : debut
	$requeteSQL = "SELECT * FROM `Menu` WHERE `sousMenuDeId`='-1' ORDER BY `ordre`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau d'objet pour les menus
		for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] ="<"."?\n//MENU_WEB\n".'$VAR_TAB_MENU_WEB = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		if($nbFois>0){
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i].",\n";
			}
		}
		//echo "smenu : ".$record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]]."<br>";
		for($i=0;$i<$nbLangue;$i++){
			$tab_text[$i] = $tab_text[$i]."array(new Menu(\"".convertText($record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".$record["lien"]."\",\"".$record["lien"]."\",\"".$record["lienExterneSite"]."\",\"100\",\"".$record["id"]."\",\"".$record["avecPartieQuick"]."\",\"".$record["idTypeInformation"]."\",\"".$record["actionSpeciale"]."\")";
			// sous menus

			$requeteSQLSousMenus = "SELECT * FROM `Menu` WHERE `sousMenuDeId`='".$record["id"]."' ORDER BY `ordre`";
			//echo "smenu : $requeteSQLSousMenus<br>";
    	$recordsetSousMenus = mysql_query($requeteSQLSousMenus) or die ("<H1>mauvaise requete</H1>");
  		if(mysql_affected_rows() >0){
				$tab_text[$i] = $tab_text[$i].", array(";
				$nbSousMenu = 0;
				while($recordSousMenu = mysql_fetch_array($recordsetSousMenus)){
					if($nbSousMenu>0){
						$tab_text[$i] = $tab_text[$i].",\n";
					}
					$tab_text[$i] = $tab_text[$i]."new Menu(\"".convertText($recordSousMenu["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".$recordSousMenu["lien"]."\",\"".$recordSousMenu["lien"]."\",\"".$recordSousMenu["lienExterneSite"]."\",\"100\",\"".$recordSousMenu["id"]."\",\"".$recordSousMenu["avecPartieQuick"]."\",\"".$recordSousMenu["idTypeInformation"]."\",\"".$recordSousMenu["actionSpeciale"]."\")";
					$nbSousMenu++;
				}
				$tab_text[$i] = $tab_text[$i].")";
			}
			$tab_text[$i] = $tab_text[$i].")";
		}
		$nbFois++;
	}

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}
	if(!$silentMode) echo "MENU_WEB : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* MENU_WEB : fin

	//************************************************* MENU_ADMIN : debut
	$requeteSQL = "SELECT * FROM `MenuAdmin` WHERE `sousMenuDeId`='-1' ORDER BY `ordre`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau d'objet pour les menus
		for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] ="<"."?\n//MENU_ADMIN\n".'$VAR_TAB_MENU_ADMIN = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		if($nbFois>0){
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i].",\n";
			}
		}
		//echo "smenu : ".$record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]]."<br>";
		for($i=0;$i<$nbLangue;$i++){
			$tab_text[$i] = $tab_text[$i]."array(new Menu(\"".convertText($record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".$record["lien"]."\",\"".$record["lien"]."\",\"".$record["lienExterneSite"]."\",\"".$record["userLevel"]."\",\"".$record["id"]."\",\"".$record["avecPartieQuick"]."\",\"".$record["idTypeInformation"]."\",\"".$record["actionSpeciale"]."\")";
			// sous menus

			$requeteSQLSousMenus = "SELECT * FROM `MenuAdmin` WHERE `sousMenuDeId`='".$record["id"]."' ORDER BY `ordre`";
			//echo "smenu : $requeteSQLSousMenus<br>";
    	$recordsetSousMenus = mysql_query($requeteSQLSousMenus) or die ("<H1>mauvaise requete</H1>");
  		if(mysql_affected_rows() >0){
				$tab_text[$i] = $tab_text[$i].", array(";
				$nbSousMenu = 0;
				while($recordSousMenu = mysql_fetch_array($recordsetSousMenus)){
					if($nbSousMenu>0){
						$tab_text[$i] = $tab_text[$i].",\n";
					}
					$tab_text[$i] = $tab_text[$i]."new Menu(\"".convertText($recordSousMenu["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".$recordSousMenu["lien"]."\",\"".$recordSousMenu["lien"]."\",\"".$recordSousMenu["lienExterneSite"]."\",\"".$recordSousMenu["userLevel"]."\",\"".$recordSousMenu["id"]."\",\"".$recordSousMenu["avecPartieQuick"]."\",\"".$recordSousMenu["idTypeInformation"]."\",\"".$recordSousMenu["actionSpeciale"]."\")";
					$nbSousMenu++;
				}
				$tab_text[$i] = $tab_text[$i].")";
			}
			$tab_text[$i] = $tab_text[$i].")";
		}
		$nbFois++;
	}

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}
	if(!$silentMode) echo "MENU_ADMIN : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* MENU_ADMIN : fin

	//************************************************* NEWSLETTER : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='14' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//NEWSLETTER\n".'$VAR_TAB_NEWSLETTER = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "NEWSLETTER : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* NEWSLETTER : fin

	//************************************************* HISTORIQUE : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='4' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//historique\n".'$VAR_TAB_HISTORIQUE = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "HISTORIQUE : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* HISTORIQUE : fin

	//************************************************* REGLES_TCHOUKBALL : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='1' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//REGLES_TCHOUKBALL\n".'$VAR_TAB_REGLES_TCHOUKBALL = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "REGLES_TCHOUKBALL : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* REGLES_TCHOUKBALL : fin

	//************************************************* CHARTE_TCHOUKBALL : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='5' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//CHARTE_TCHOUKBALL\n".'$VAR_TAB_CHARTE_TCHOUKBALL = array('."\n";
	}

	$nbFois=0;
	if(!$silentMode) echo "Attention str_replace bisard";
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.str_replace("<br />","<br><br>",convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]])).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "CHARTE_TCHOUKBALL : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* CHARTE_TCHOUKBALL : fin

	//************************************************* ARBITRE_FEDERATION : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='6' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//ARBITRE_FEDERATION\n".'$VAR_TAB_ARBITRE_FEDERATION = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "ARBITRE_FEDERATION : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* ARBITRE_FEDERATION : fin

	//************************************************* JUNIOR_FEDERATION : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='3' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//JUNIOR_FEDERATION\n".'$VAR_TAB_JUNIOR_FEDERATION = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "JUNIOR_FEDERATION : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* JUNIOR_FEDERATION : fin

	//************************************************* PRESENTATION_TCHOUKBALL : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='2' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//PRESENTATION_TCHOUKBALL\n".'$VAR_TAB_PRESENTATION_TCHOUKBALL = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "PRESENTATION_TCHOUKBALL : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* PRESENTATION_TCHOUKBALL : fin

	//************************************************* FORMATION_ARBITRE : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='7' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//FORMATION_ARBITRE\n".'$VAR_TAB_FORMATION_ARBITRE = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "FORMATION_ARBITRE : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* FORMATION_ARBITRE : fin

	//************************************************* FORMATION_ARBITRE_JUNIOR : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='15' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//FORMATION_ARBITRE_JUNIOR\n".'$FORMATION_ARBITRE_JUNIOR = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "FORMATION_ARBITRE_JUNIOR : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* FORMATION_ARBITRE_JUNIOR : fin

	//************************************************* FORMATION_GESTIONNAIRE_CLUB : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='8' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//FORMATION_GESTIONNAIRE_CLUB\n".'$VAR_TAB_FORMATION_GESTIONNAIRE_CLUB = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "FORMATION_GESTIONNAIRE_CLUB : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* FORMATION_GESTIONNAIRE_CLUB : fin

	//************************************************* FORMATION_JS : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='9' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//FORMATION_JS\n".'$VAR_TAB_FORMATION_JS = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "FORMATION_JS : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* FORMATION_JS : fin

	//************************************************* FORMATION_SWISSOLYMPIC : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='10' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//FORMATION_SWISSOLYMPIC\n".'$VAR_TAB_FORMATION_SWISSOLYMPIC = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "FORMATION_SWISSOLYMPIC : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* FORMATION_SWISSOLYMPIC : fin

	//************************************************* COMISSION_SPONSORING : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='12' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");


	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//COMISSION_SPONSORING\n".'$VAR_TAB_COMISSION_SPONSORING = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "COMISSION_SPONSORING : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* COMISSION_SPONSORING : fin


	//************************************************* INTRO_SPONSORS : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='11' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//INTRO_SPONSORS\n".'$VAR_TAB_INTRO_SPONSORS = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "INTRO_SPONSORS : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* INTRO_SPONSORS : fin

	//************************************************* MEDIA : debut
	$requeteSQL = "SELECT * FROM `TextCorpPage` WHERE `IdTextCorpPage`='13' ORDER BY `paragrapheNum`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	// creer un tableau pour l'historique
	for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES); $i++){
		$tab_text[$i] = "<"."?\n//MEDIA\n".'$VAR_TAB_MEDIA = array('."\n";
	}

	$nbFois=0;
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		for($i=0; $i<$nbLangue; $i++){
			if($nbFois>0){
				$tab_text[$i] = $tab_text[$i].", \n";
			}
			$tab_text[$i] = $tab_text[$i].'"'.convertText($record["paragraphe".$VAR_TABLEAU_DES_LANGUES[$i][0]]).'"';
		}
		$nbFois++;
	}
	for($i=0; $i<$nbLangue; $i++){
		$tab_text[$i] = $tab_text[$i]."); \n?".">\n\n";
	}

	if(!$silentMode) echo "MEDIA : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* MEDIA : fin

	//************************************************* LIENS : debut
	// creer un tableau pour ARBITRE
	$requeteSQL = "SELECT * FROM `Liens`, `LiensGroupe` WHERE `Liens`.`idLiensGroupe`=`LiensGroupe`.`id`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] ="<"."?\n//LIENS\n".'$VAR_TAB_LIENS = array('."\n";
	}

	$nbFois=0;
	$LienRegroupement="";
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		if($nbFois>0 && $LienRegroupement==$record["idLiensGroupe"]){
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i].",\n";
			}
		}

		if($LienRegroupement!=$record["idLiensGroupe"]){
			if($nbFois>0){
				for($i=0;$i<$nbLangue;$i++){
					$tab_text[$i] = $tab_text[$i]."),\n";
				}
			}

			$LienRegroupement=$record["idLiensGroupe"];
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array("."\"".convertText($record["nomGroupe".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",";
			}
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array(\"".$record["source"]."\",\"".convertText($record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\")";
			}
		}
		else{
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array(\"".$record["source"]."\",\"".convertText($record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\")";
			}
		}
		$nbFois++;
	}

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] = $tab_text[$i].")); \n?".">\n\n";
	}

	if(!$silentMode) echo "LIENS : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* LIENS : fin


	//************************************************* VIDEOS : debut
	$requeteSQL = "SELECT * FROM `Videos`, `TypeVideos` WHERE `Videos`.`idTypeVideos` =`TypeVideos`.`id` ORDER BY `TypeVideos`.`id` DESC,`ordre`";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] ="<"."?\n//VIDEOS\n".'$VAR_TAB_VIDEOS = array('."\n";
	}

	$nbFois=0;
	$videoRegroupement="";
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		if($nbFois>0 && $videoRegroupement==$record["id"]){
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i].",\n";
			}
		}

		if($videoRegroupement!=$record["id"]){
			if($nbFois>0){
				for($i=0;$i<$nbLangue;$i++){
					$tab_text[$i] = $tab_text[$i]."),\n";
				}
			}

			$videoRegroupement=$record["id"];
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array("."\"".convertText($record["nomType".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".convertText($record["infoMini".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",";
			}
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array(\"".convertText($record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".convertText($record["fichier"])."\")";
			}
		}
		else{
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array(\"".convertText($record["nom".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".convertText($record["fichier"])."\")";
			}
		}
		$nbFois++;
	}

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] = $tab_text[$i].")); \n?".">\n\n";
	}
	if(!$silentMode) echo "VIDEOS : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* VIDEOS : fin

	//************************************************* SPONSORS : debut
	$requeteSQL = "SELECT * FROM `Sponsors`, `TypeSponsors` WHERE `Sponsors`.`idTypeSponsors` =`TypeSponsors`.`id` ORDER BY `id` DESC";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] ="<"."?\n//SPONSORS\n".'$VAR_TAB_SPONSORS = array('."\n";
	}

	$nbFois=0;
	$Regroupement="";
	while($record = mysql_fetch_array($recordset)){
		// nouvel element ==> virgule
		if($nbFois>0 && $Regroupement==$record["id"]){
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i].",\n";
			}
		}

		if($Regroupement!=$record["id"]){
			if($nbFois>0){
				for($i=0;$i<$nbLangue;$i++){
					$tab_text[$i] = $tab_text[$i]."),\n";
				}
			}

			$Regroupement=$record["id"];
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array("."\"".convertText($record["nomType".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",";
			}
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array(\"".convertText($record["description".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".convertText($record["lienLogo"])."\",\"".convertText($record["lienWeb"])."\")";
			}
		}
		else{
			for($i=0;$i<$nbLangue;$i++){
				$tab_text[$i] = $tab_text[$i]."array(\"".convertText($record["description".$VAR_TABLEAU_DES_LANGUES[$i][0]])."\",\"".convertText($record["lienLogo"])."\",\"".convertText($record["lienWeb"])."\")";
			}
		}
		$nbFois++;
	}

	for($i=0;$i<$nbLangue;$i++){
		$tab_text[$i] = $tab_text[$i].")); \n?".">\n\n";
	}
	if(!$silentMode) echo "SPONSORS : OK<br>";
	ecrireDansTousLesFichiers($tab_fichiers,$tab_text,$VAR_TABLEAU_DES_LANGUES,$initialFilePath);
	//************************************************* SPONSORS : fin



	if(!$embedded)@mysql_close();
	//-----------------------------------------------------------------------------------------------------------
	// Fin
	//-----------------------------------------------------------------------------------------------------------

	for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
		fclose($tab_fichiers[$i]);
	}
}
else{
	echo "Abandon du processus, au moins un des fichiers n'est pas accessible<br>";
}

if(!$silentMode) echo "<h1>Fin du script</h1>";
?>
