<?php
require('config.php'); // Le fichier config doit exister ( include + puissant ) pour se connecter � MySQL

	function date_sql2RFC_2822($date){
	   $annee = substr($date,0,4);
	   $mois = substr($date,5,2);
	   $jour = substr($date,8,2);
	   $timestamp = mktime(0,0,0,$mois,$jour,$annee);
	   $date_RFC_2822 = date('r',$timestamp);
	   return $date_RFC_2822;
	}

$query1 = 'SELECT id, titreFr, titreIt, date, corpsFr, corpsIt FROM News ORDER BY date DESC LIMIT 0, 10'; // r�cup�rations des 10 derni�res donn�es de la table 'News'
$result1 = mysql_query($query1);

echo '<?xml version="1.0" encoding="iso-8859-1" ?>
<rss version="2.0">
<channel>
<title>Federazione Svizzera di Tchoukball</title>
<link>http://www.tchoukball.ch/index.php?lien=51</link>
<description>Federazione Svizzera di Tchoukball</description>
<language>it-IT</language>'; // ici on remplit les informations

for ($i = 0; $i < 10 ; $i++) { // on veut 10 afficher news
@$row = mysql_fetch_array($result1); // fonction pour la boucle
if ($row["titreIt"]==""){$titre=$row["titreFr"];} //si le titre anglais n'existe pas
else {$titre=$row["titreIt"];}
if ($row["corpsIt"]==""){$corps="<strong>Questa notizia non � disponibile in italiano</strong><br />".$row["corpsFr"];} //si le contenu anglais n'existe pas
else {$corps=$row["corpsIt"];}
$titre = htmlspecialchars($titre); // fonction pour le titre
$auteur = "info@tchoukball.ch"; // fonction pour l'auteur
$lien = "http://www.tchoukball.ch/index.php?lien=51&amp;newsIdSelection=".$row["id"]; // fonction pour le lien
$description = htmlspecialchars($corps); // fonction pour le contenu de la news
$description = str_replace('<', '&lt;', $description);
$description = str_replace('>', '&gt;', $description);
$date = date_sql2RFC_2822($row["date"]); // fonction pour la date de publication de la news

$id = $row["id"];

if (!$id){
echo '';} // si il y a moins de 10 news, afficher que le nombre de news disponibles

else{ // sinon afficher les 10 derni�res news
echo '<item><title>'.$titre.'</title>';
echo '<link>'.$lien.'</link>';
echo '<guid isPermaLink="true">http://www.tchoukball.ch/index.php?lien=51&amp;newsIdSelection='.$id.'</guid>';
echo '<description>'.$description.'</description>';
echo '<author>'.$auteur.'</author>';
echo '<pubDate>'.$date.'</pubDate></item>';

}}

echo '</channel></rss>'; // fin de la page rss

mysql_close(); // D�connexion de MySQL
?>