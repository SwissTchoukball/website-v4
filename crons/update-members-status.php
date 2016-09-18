<?php
include('../config.php');

date_default_timezone_set('Europe/Zurich');


// Updating status from "Junior" to "Actif"
$query = "UPDATE DBDPersonne
		  SET idStatus = 3
		  WHERE idStatus = 6
		  AND " . date('Y') . " - YEAR(dateNaissance) >= 21";
//echo $query;
mysql_query($query);

// Updating Tchoukup status from "Papier + E-mail" to "E-mail" for newly "Actif" members who are not member of TBC Genève
$queryTchoukup = "UPDATE DBDPersonne
				  SET idCHTB = 5
				  WHERE idCHTB = 2
				  AND idStatus = 3
				  AND " . date('Y') . " - YEAR(dateNaissance) = 21
				  AND idClub != 4";
//echo $queryTchoukup;
mysql_query($queryTchoukup);

// TODO avertir automatiquement les clubs par e-mails des membres qui recoivent maintenant le tchoukup par e-mail uniquement et pour il est donc nécessaire d'indiquer une adresse e-mail.

mysql_close();
?>