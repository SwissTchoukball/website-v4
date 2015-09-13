<div class="agendaOutlook">
<?php
//include "../includes/date.inc.php";
$cheminFichierAgenda = "temp/agendaFSTB.CSV";

// test 1 fichier pour les droits en ecriture
function fichierDroitEnEcriture($nomfichier){
	if (!is_writable($nomfichier)) {
		return false;
	}
	return true;
}
// ouvrir un fichier
function ouvrirFichier($nomfichier, $modeOuverture){
	if (!$fichier = fopen($nomfichier,$modeOuverture)) {
    echo "Impossible d'ouvrir le fichier ($nomfichier)<br>";
    exit;
  }
	return $fichier;
}
function ecrireDansFichier($fichier,$text,$nomfichier){
	// Ecriture dans le fichier
	if (fwrite($fichier, $text) === FALSE) {
		echo "Impossible d'écrire dans le fichier ($nomfichier)<br>";
		exit;
	}
}

function formatageLigne($record){
	$dateRappel = date("d-m-Y", mktime (0,0,0,mois($record["dateDebut"]),jour($record["dateDebut"])-1,annee($record["dateDebut"])));
	return "\"".$record["nomType"]."\",\"".date_sql2date($record["dateDebut"])."\",\"".$record["heureDebut"]."\",\"".date_sql2date($record["dateFin"])."\",\"".$record["heureFin"]."\",\"Faux\",\"Vrai\",\"".$dateRappel."\",\"".$record["heureDebut"]."\",,,,,,\"Tchoukball\",\"Normale\",\"".$record["description"]."\",\"".$record["lieu"]."\",,,\"Normale\",\"Faux\"".chr(13).chr(10);
}

if(!file_exists($cheminFichierAgenda) ||  fichierDroitEnEcriture($cheminFichierAgenda)){
	$fichier = ouvrirFichier($cheminFichierAgenda,"w");

	$enteteFichier = '"Objet","Début","Début","Fin","Fin","Journée entière","Rappel actif/inactif","Date de rappel","Heure du rappel","Organisateur d\'une réunion","Participants obligatoires","Participants facultatifs","Ressources de la réunion","Afficher la disponibilité","Catégories","Critère de diffusion","Description","Emplacement","Informations facturation","Kilométrage","Priorité","Privé"'.chr(13).chr(10);
	ecrireDansFichier($fichier,$enteteFichier,$cheminFichierAgenda);

	$requeteSQL = "SELECT * FROM `Agenda_Evenement`, `Agenda_TypeEvent` WHERE
						`Agenda_Evenement`.`id_TypeEve` = `Agenda_TypeEvent`.`id_TypeEve` ORDER BY `dateDebut` ASC";
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	while($record = mysql_fetch_array($recordset)){
		ecrireDansFichier($fichier,formatageLigne($record),$cheminFichierAgenda);
	}
	fclose($fichier);
	?>
<p align="justify">Gr&acirc;ce à ce fichier, vous aurez toutes les dates de l'agenda
  de la FSTB dans votre calendrier outlook. Télécharger le fichier *.CSV pour
  outlook <a href='<?php echo $cheminFichierAgenda; ?>'>ici (<?php echo tailleFichier($cheminFichierAgenda);?>).</a>
  Le fichier est <strong>toujours à jour</strong> puisqu'il est généré à l'appel
  de cette page. Malheureusement, outlook est d&eacute;pendant des param&egrave;tres
  r&eacute;gionaux. Cela implique que ce fichier fonctionne parfaitement avec
  une version fran&ccedil;aise, mais je ne garanti pas le succ&egrave;s avec d'autres
  param&egrave;tres r&eacute;gionaux.</p>
<br>
<table width="100%" border="0">
  <tr>
    <td><h4>Mode d'emploi</h4></td>
  </tr>
  <tr>
    <td><div align="center"><img src="/pictures/installation_aganda_outlook/phase_1.gif" width="270" height="266"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><img src="/pictures/installation_aganda_outlook/phase_2.gif" width="597" height="334"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><img src="/pictures/installation_aganda_outlook/phase_3.gif" width="597" height="334"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><img src="/pictures/installation_aganda_outlook/phase_4.gif" width="597" height="334"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><img src="/pictures/installation_aganda_outlook/phase_5.gif" width="597" height="334"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><img src="/pictures/installation_aganda_outlook/phase_6.gif" width="597" height="334"></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>
  <?php
}
else{
	echo "<h4>Erreur à la génération du fichier</h4>";
}
?>
</p>
</div>

