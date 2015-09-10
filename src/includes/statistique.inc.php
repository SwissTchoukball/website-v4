<?
function statInsererPageSurf($nomFichierSurf){

	$aujourdhui = getdate();
	$mois = $aujourdhui['mon'];
	$annee = $aujourdhui['year'];
	
	$requete = "SELECT * FROM `StatistiqueSurf` WHERE mois='".$mois."' AND annee ='".$annee."' AND page='".$nomFichierSurf."'";
	//echo $requete."<BR>";
	$recordset = mysql_query($requete) or die ("<H3>Erreur statistique langue (A)</H3><br />".$requete."<br />".mysql_error());
	$resultat = mysql_fetch_array($recordset);

	// si le mois et l'année n'existe pas, on créer une entrée dans la base de données.
	if($resultat==null){
		$chaine_toutes_langues="";
		$chaine_valeur_toutes_langues="";				

		$requete = "INSERT INTO `StatistiqueSurf` (`page`,`mois`,`annee`,`nbFois`) VALUES ('".$nomFichierSurf."', '".$mois."', '".$annee."','0')";
		@mysql_query($requete);		
	}
	
	$requete = "UPDATE `StatistiqueSurf` SET `nbFois` = (nbFois+1) WHERE `mois` = '".$mois."' AND `annee` = '".$annee."' AND `page` = '".$nomFichierSurf."'";			
	@mysql_query($requete);
}
?>