<?php
session_start();
// connexion a la bd
require('../config.php');

include('../includes/fonctions.inc.php');

if (!$_SESSION['__gestionMembresClub__']){
	header("HTTP/1.0 403 Forbidden");
	echo "<h1>Forbidden</h1>";
	echo "You don't have permission to download the member list of any club.";
	echo "<hr />";
	echo "<em>" . VAR_LANG_ASSOCIATION_NAME . " - ";
	echo "<script>document.write('<a href=\"' + document.referrer + '\">Go Back</a>');</script>";
	echo "</em>";
	exit;
}


//Requete SQL
$query = "SELECT `DBDPersonne`.`raisonSociale`,
				 `DBDCivilite`.`descriptionCiviliteFr` AS 'civilite',
				 `DBDPersonne`.`nom`,
				 `DBDPersonne`.`prenom`,
				 `DBDPersonne`.`adresse`,
				 `DBDPersonne`.`cp`,
				 `DBDPersonne`.`npa`,
				 `DBDPersonne`.`ville`,
				 `DBDPays`.`descriptionPaysFr` AS 'pays',
				 `DBDPersonne`.`dateNaissance`,
				 `DBDStatus`.`descriptionStatusFr` AS 'statut',
				 `DBDCHTB`.`descriptionCHTBFr` AS 'tchoukup'
		  FROM `DBDPersonne`, `DBDCivilite`, `DBDPays`, `DBDStatus`, `DBDCHTB`
		  WHERE (DBDPersonne.idStatus=3 OR DBDPersonne.idStatus=4 OR DBDPersonne.idStatus=5 OR DBDPersonne.idStatus=6 OR DBDPersonne.idStatus=11 OR DBDPersonne.idStatus=23)
		  		AND idClub=".$_SESSION['__nbIdClub__']."
		  		AND `DBDPersonne`.`idCivilite`= `DBDCivilite`.`idCivilite`
		  		AND `DBDPersonne`.`idPays`=`DBDPays`.`idPays`
		  		AND `DBDPersonne`.`idStatus`=`DBDStatus`.`idStatus`
		  		AND `DBDPersonne`.`idCHTB`=`DBDCHTB`.`idCHTB`
		  ORDER BY `nom`, `prenom`";
$result = mysql_query($query) or die(mysql_error());

// Entêtes des colones dans le fichier Excel
$excel ="Raison sociale \t Civilité \t Nom \t Prénom \t Adresse (ligne 1) \t Adresse (ligne 2) \t NPA \t Localité \t Pays \t Date de naissance \t Statut \t tchoukup\n";

//Les resultats de la requette
while($row = mysql_fetch_array($result)) {
        //$excel .= "$row[raisonSociale] \t $row[civilite] \t $row[nom] \t $row[prenom] \t $row[adresse] \t $row[cp] \t $row[npa] \t $row[ville] \t $row[pays]  \n";
		$excel .= $row['raisonSociale']." \t ".$row['civilite']." \t ".$row['nom']." \t ".$row['prenom']." \t ".$row['adresse']." \t ".$row['cp']." \t ".$row['npa']." \t ".$row['ville']." \t ".$row['pays']." \t ".date_sql2date($row['dateNaissance'])." \t ".$row['statut']." \t ".$row['tchoukup']." \n";
}

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=club-".slugify($_SESSION['__nbIdClub__']).".xls");
print $excel;
exit;
?>