<div class="pageMesInfos"><?php
	statInsererPageSurf(__FILE__);

	$requeteSQL = "SELECT *, p.adresse, p.ville, p.email, p.telephone, c.club FROM `Personne` p,`ClubsFstb` c WHERE p.`nom`='".addslashes($_SESSION["__nom__"])."' AND p.`prenom`='".addslashes($_SESSION["__prenom__"])."' AND p.`idClub`=c.`id`";

	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

	$record = mysql_fetch_array($recordset);

	echo "<h4>".VAR_LANG_USERNAME."</h4>";
	echo "<div class='mesInfos'>";
		echo $record["username"]."<br />";
    echo "</div>";
	echo "<h4>Informations personnelles</h4>";
	echo "<div class='mesInfos'>";
		echo $record["nom"]."&nbsp;".$record["prenom"]."<br />";
		echo $record["adresse"]."<br />";
		echo $record["numPostal"]."&nbsp;".$record["ville"]."<br /><br />";
		echo "Date de naissance"." : ".date_sql2date($record["dateNaissance"]);
    echo "</div>";
	echo "<h4>Informations de contact</h4>";
	echo "<div class='mesInfos'>";
		echo "Email"." : "; echo email($record["email"]); echo "<br />";
		echo "Téléphone"." : ".$record["telephone"]."<br />";
		echo "Portable"." : ".$record["portable"]."<br />";
		echo "Club"." : ".$record["club"]."<br />";
	echo "</div>";

	// personne avec experience
	if($record["experience"]!=""){
		echo "<h4>Exéprience</h4>";
		echo "<div class='mesInfos'>";
			echo nl2br($record["experience"])."<br />";
		echo "</div>";

	}
?></div>
