<?
// attribut la langue fr par defaut si aucune n'est selectionnee.
// selection du fichier de variables dans la bonne langue.

if($_SESSION["debug_tracage"])echo __FILE__."<BR>";

include "tab.langue.inc.php";

// si si le premier parametre appartient au tableau des langues.										
function langueValide($langue,$tableauDesLangues){
	for($i=0;$i<count($tableauDesLangues);$i++){
		if($langue == $tableauDesLangues[$i][0]){
			return false;
		}
	}
	return true;
}											

// met a jour la bd pour les stats
function mettreAJourStatistique($langueChoisie,$VAR_TABLEAU_DES_LANGUES){

	$aujourdhui = getdate();
	$mois = $aujourdhui['mon'];
	$annee = $aujourdhui['year'];
	//echo $mois." ".$annee;
	
	$requete = "SELECT * FROM `StatistiqueLangue` WHERE mois='".$mois."' AND annee ='".$annee."'";
	//echo $requete."<BR>";
	$recordset = mysql_query($requete) or die ("<H3>Erreur statistique langue</H3>");
	$resultat = mysql_fetch_array($recordset);

	// si le mois et l'année n'existe pas, on créer une entrée dans la base de données.
	if($resultat==null){
		$chaine_toutes_langues="";
		$chaine_valeur_toutes_langues="";				
		for($i=0; $i<count($VAR_TABLEAU_DES_LANGUES);$i++)	{
			if($i>0){
					$chaine_toutes_langues = $chaine_toutes_langues.",";
					$chaine_valeur_toutes_langues = $chaine_valeur_toutes_langues.",";				
			}			
			$chaine_toutes_langues = $chaine_toutes_langues."`".$VAR_TABLEAU_DES_LANGUES[$i][0]."`";
			$chaine_valeur_toutes_langues = $chaine_valeur_toutes_langues."'0'";
		}
		$requete = "INSERT INTO `StatistiqueLangue` (`mois`,`annee`,".$chaine_toutes_langues.") VALUES ('".$mois."', '".$annee."',".$chaine_valeur_toutes_langues.")";
		@mysql_query($requete);		
	}
	
	$requete = "UPDATE StatistiqueLangue set ".$langueChoisie."= (".$langueChoisie."+1) WHERE `mois` = '".$mois."' AND `annee` = '".$annee."'";		
	@mysql_query($requete);
}
/*
if(!isset($_SESSION["__langue__"])){

	$_SESSION["__langue__"]="Fr";	
}*/

$langchange = $_GET['langchange'];

if($langchange!="" && $langchange!=$_SESSION["__langue__"]){

	// validité du changement de langue, si erreur => premiere langue
	if($langchange=="" || langueValide($langchange,$VAR_TABLEAU_DES_LANGUES)){
		$_SESSION["__langue__"] = $VAR_TABLEAU_DES_LANGUES[0][0];
	}
	else{
		$_SESSION["__langue__"]=$langchange;
		mettreAJourStatistique($_SESSION["__langue__"],$VAR_TABLEAU_DES_LANGUES);
	}
}

// pas de langue au depart, donc surf en francais
if($_SESSION["__langue__"]==""){
  $_SESSION["__langue__"] = $VAR_TABLEAU_DES_LANGUES[0][0];
  
  //mettreAJourStatistique($VAR_TABLEAU_DES_LANGUES[0][0],$VAR_TABLEAU_DES_LANGUES);
}

if($DEBUG)echo "langue = ".$_SESSION["__langue__"]."<BR>";

include "var.".$_SESSION["__langue__"].".inc.php";
?>