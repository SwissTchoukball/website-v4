<?php
session_start();
//header("Content-type: application/x-gzip");
//header("Content-type: application/zip");
header("Content-type: text/x-csv");
header('Content-Disposition: attachement; filename="membres-FSTB.csv"');

include "../includes/date.inc.php";

// connexion a la bd
@mysql_pconnect("localhost","website","Uk5g#84*Nk") or die ("<H1>Connexion impossible au serveur</H1>");
@mysql_select_db("tchoukball1") or die ("<H1>Connexion impossible à la base de donnée</H1>");

// the right SQL request
if($_POST["action"]=="fullExport"){

	//$tableRequises = "`DBDPersonne`, `DBDStatus`, `DBDOrigineAdresse`, `ClubsFstb`, `DBDLangue`, `DBDRaisonSociale`, `DBDCivilite`, `DBDPays`, `DBDCHTB`, `DBDArbitre`, `DBDMediaType`, `DBDMediaCanton`";
	$tableRequises = "`DBDPersonne`, `DBDStatus`, `DBDOrigineAdresse`, `ClubsFstb`, `DBDLangue`, `DBDCivilite`, `DBDPays`, `DBDCHTB`, `DBDArbitre`, `DBDMediaType`, `DBDMediaCanton`";
	$jointure = "`DBDPersonne`.`idStatus`=`DBDStatus`.`idStatus`"." AND ".
							"`DBDPersonne`.`idOrigineAdresse`=`DBDOrigineAdresse`.`idOrigineAdresse`"." AND ".
							"`DBDPersonne`.`idClub`=`ClubsFstb`.`nbIdClub`"." AND ".
							"`DBDPersonne`.`idLangue`=`DBDLangue`.`idLangue`"." AND ".
							"`DBDPersonne`.`idCivilite`=`DBDCivilite`.`idCivilite`"." AND ".
							"`DBDPersonne`.`idPays`=`DBDPays`.`idPays`"." AND ".
							"`DBDPersonne`.`idCHTB`=`DBDCHTB`.`idCHTB`"." AND ".
							//"`DBDPersonne`.`idRaisonSociale`=`DBDRaisonSociale`.`idRaisonSociale`"." AND ".							
							"`DBDPersonne`.`idArbitre`=`DBDArbitre`.`idArbitre`"." AND ".									
							"`DBDPersonne`.`idMediaType`=`DBDMediaType`.`idMediaType`"." AND ".	
							"`DBDPersonne`.`idMediaCanton`=`DBDMediaCanton`.`idMediaCanton`";																						

  $requeteSQL = "SELECT * FROM ".$tableRequises." WHERE ".$jointure." ORDER BY `nom`, `prenom`";						
}
else if($_POST["action"]=="screenExport"){
	$requeteSQL=$_SESSION["__requetePourExportation__"];
}

$recordSet=mysql_query ($requeteSQL);

$retourLigne = "\n";

echo "Civilite;Nom;Prenom;Adresse;CP/rue option;NPA;Ville;Pays;Tel.Prive;Tel.Prof;Portable;Fax;Email;Date de naissance;remarque;Club;Status;Origine de l'adresse;Langue;Raison Sociale;Suisse tchoukball;Date modification;Heure modification;Media Type; Media Canton;".$retourLigne;


// remplace le ; par :
function fromaterPourExportation($chaine){
	return str_replace(";",":",$chaine);
}

while($record=mysql_fetch_array($recordSet))
{
	// attention au ";" => remplacer par :
	$civilite=fromaterPourExportation($record['descriptionCivilite'.$_SESSION["__langue__"]]);
	$nom=fromaterPourExportation($record['nom']);
	$prenom=fromaterPourExportation($record['prenom']);
	$adresse=fromaterPourExportation($record['adresse']);
	$cp=fromaterPourExportation($record['cp']);
	$npa=fromaterPourExportation($record['npa']);		
	$ville=fromaterPourExportation($record['ville']);
	$pays=fromaterPourExportation($record['descriptionPays'.$_SESSION["__langue__"]]);
	$telPrive=fromaterPourExportation($record['telPrive']);
	$telProf=fromaterPourExportation($record['telProf']);
	$portable=fromaterPourExportation($record['portable']);
	$fax=fromaterPourExportation($record['fax']);
	$email=fromaterPourExportation($record['email']);
	$dateNaissance=fromaterPourExportation($record['dateNaissance']);
	$remarque=fromaterPourExportation($record['remarque']);
	$club=fromaterPourExportation($record['club']);
	$status=fromaterPourExportation($record['descriptionStatus'.$_SESSION["__langue__"]]);
	$origineAdresse=fromaterPourExportation($record['descriptionOrigineAdresse'.$_SESSION["__langue__"]]);
	$langue=fromaterPourExportation($record['descriptionLangue'.$_SESSION["__langue__"]]);
	//$raisonSociale=fromaterPourExportation($record['descriptionRaisonSociale'.$_SESSION["__langue__"]]);
	$raisonSociale=fromaterPourExportation($record['raisonSociale']);
	$CHTB=fromaterPourExportation($record['descriptionCHTB'.$_SESSION["__langue__"]]);
	$derniereModificationDate=fromaterPourExportation(date_sql2date($record['derniereModification']));
	$derniereModificationHeure=fromaterPourExportation(substr($record['derniereModification'],11));
	//$raisonSociale=fromaterPourExportation($record['descriptionRaisonSociale'.$_SESSION["__langue__"]]);
	$mediaType=fromaterPourExportation($record['descriptionMediaType'.$_SESSION["__langue__"]]);
	$mediaCanton=fromaterPourExportation($record['descriptionMediaCanton'.$_SESSION["__langue__"]]);
	echo "$civilite;$nom;$prenom;$adresse;$cp;$npa;$ville;$pays;$telPrive;$telProf;$portable;$fax;$email;$dateNaissance;$remarque;$club;$status;$origineAdresse;$langue;$raisonSociale;$CHTB;$derniereModificationDate;$derniereModificationHeure;$mediaType;$mediaCanton".$retourLigne;
}
//echo gzencode($liste);
//echo zip_entry_read($liste);
?>