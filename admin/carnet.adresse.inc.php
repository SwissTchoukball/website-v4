<?php
	statInsererPageSurf(__FILE__);
	$motsRecherches = mysql_escape_string($_POST['motsRecherches']);
?>
<form action="<?php echo"?menuselection=$menuselection&smenuselection=$smenuselection"; ?>" method="post"><br><p align='center'><input type="text" name="motsRecherches" size="35" value='<?php echo $motsRecherches; ?>'>&nbsp;<input type="submit" value="Rechercher"></p></form><p />


<script language="JavaScript">
	function validerSuppression(){
		return confirm("Etes-vous sur de vouloir supprimer ce contact ?");
	}
</script>

<table class="adminTable"><?php

	if($motsRecherches!=""){

		// separation des mots
		$tok = strtok($motsRecherches," ");
		$possibilite="";
		$nbFois=0;
		while ($tok) {
			if($nbFois>0){
				$possibilite .= " OR ";
			}
			$nbFois++;
			$possibilite .= "p.`nom` LIKE '%".$tok."%' OR
											p.`prenom` LIKE '%".$tok."%' OR
											p.`ville` LIKE '%".$tok."%' OR
											p.`email` LIKE '%".$tok."%' OR
											p.`adresse` LIKE '%".$tok."%' OR
											c.`club` LIKE '%".$tok."%' OR
											p.`numPostal` LIKE '%".$tok."%'";
			$tok = strtok(" ");
		}
		$requeteSQL = "SELECT *, p.`email`, p.`adresse`, p.`ville`, p.`id` AS `idPersonne`, p.userLevel FROM `Personne` p, `ClubsFstb` c WHERE (".$possibilite.") AND p.`idClub`=c.`id` ORDER BY `nom`, `prenom`";
	}
	else{
		$requeteSQL = "SELECT *, p.`email`, p.`adresse`, p.`ville`, p.`id` AS `idPersonne`, p.userLevel FROM `Personne` p, `ClubsFstb` c WHERE p.`idClub`=c.`id` ORDER BY `nom`, `prenom`";
	}
	//echo $requeteSQL;
	$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");



	echo "<tr>";
        echo "<th>Personne</th>";
        echo "<th>Téléphone</th>";
        echo "<th>Club</th>";
        echo "<th>Email</th>";
        if($_SESSION["__userLevel__"]<=5){
            echo "<th>Fonctions avancées</th>";
        }
	echo "</tr>";
	while ($record = mysql_fetch_array($recordset))
	{
    echo "<tr>";

		echo "<td>".stripslashes($record["nom"])."&nbsp;".stripslashes($record["prenom"])."<br />".$record["adresse"]."<br />"
											.($record["numPostal"]==0?'':$record["numPostal"])."&nbsp;".$record["ville"]."</td>";

		echo "<td class='center'>".$record["telephone"]."<br />".$record["portable"]."</td>";

		echo "<td>".$record["club"]."</td>";

		echo "<td>";
			if($record["email"]!="") echo email($record["email"]);
			else echo "&nbsp;";
		echo "</td>";

		if($_SESSION["__userLevel__"]<=5){
			echo "<td>";
			if ($record['userLevel'] > 0) {
				echo "<a href='?".VAR_HREF_LIEN_MENU."=2&modificationId=".$record["idPersonne"]."'>modifier</a>";
			}
			if ($record['userLevel'] > 9) {
				echo "<br />";
				echo "<a href='?".VAR_HREF_LIEN_MENU."=2&suppressionId=".$record["idPersonne"]."' onClick='return validerSuppression();'>supprimer</a>";
			}
			echo "</td>";
		}
    echo "</tr>";
    }
?></table>
