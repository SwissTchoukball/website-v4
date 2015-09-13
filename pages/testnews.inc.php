<form name="chtbSaison" action="" method="post"><table border="0" align="center">
  <tr>
	<td><p><?php echo $agenda_annee; ?> :</p></td>
	<td><select name="annee" id="select" onChange="chtbSaison.submit();">
	 <?php
					// recherche de la premiere date
					$requeteAnnee = "SELECT MIN( Download.date ) FROM `Download` WHERE idType='8' AND date <> '0000-00-00'";
					$recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
					$dateMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");
					$anneeMin = annee($dateMin[0]);

					$anneeMinAffichee = $anneeMin - annee(date_actuelle());

					// championnat de aout à aout => deux date de différence => il y a deux années.
					$premiereParution = -$anneeMinAffichee;

					// si on est en aout, on peut afficher une option en plus
					if(mois(date_actuelle())>8){
						$premiereParution++;
					}

					$annePremiereParution=$anneeMin;
					if($annee == ""){
						$annee = annee(date_actuelle());
						if(mois(date_actuelle())<9){
							$annee--;
						}
					}

					for($i=0;$i<$premiereParution;$i++){
						if($annee == $annePremiereParution)
							echo "<option selected value='$annePremiereParution'>".VAR_LANG_SAISON." $annePremiereParution-".($annePremiereParution+1)."</option>";
						else
							echo "<option value='$annePremiereParution'>".VAR_LANG_SAISON." $annePremiereParution-".($annePremiereParution+1)."</option>";
						$annePremiereParution++;
					}
			?></select></td>
  </tr>
</table></form>

<?php
	$dateMin = $annee."-08-01";
	$dateMax = ($annee+1)."-08-01";
	$requete = "SELECT * FROM `Download` WHERE idType='8' AND date > '$dateMin ' AND date < '$dateMax'";

	$recordset = mysql_query($requete) or die ("<H3>Aucune date existe</H3>");
	//encadrerHaut();


	$nbMaxCol = 3;
	$nbCol = 0;

	echo "<table border='0' cellpadding='0' cellspacing='0' align='center'>";
	insererEncadrementHaut($nbMaxCol,true);
	while($record = mysql_fetch_array($recordset)){
		$imageFile = PATH_DOCUMENTS.$_SESSION["__langue__"]."_".substr($record["fichier"],0,strlen($record["fichier"])-3)."jpg";
		$pdfFile = PATH_DOCUMENTS.$_SESSION["__langue__"]."_".$record["fichier"];

		if($nbCol % $nbMaxCol == 0){

			if($nbCol>0){
				insererSeparationEncadermentHorinzotale($nbMaxCol,true);
			}

			insererEncadrementGauche();
			echo "<td>";
			echo "<p align='center' class='titresectiontext'>".$record["titre".$_SESSION["__langue__"]]."</p>";
			echo "<a href='$pdfFile'><img border='0' src='".$imageFile."'/></a>";
			echo "<p align='center'>".date_sql2MonthYear($record["date"],$VAR_G_MOIS)."</p>";
			echo "<p align='center'><a href='$pdfFile'>".tailleFichier($pdfFile)."</a></p>";
			echo "</td>";
			insererSeparationEncadermentVerticale();
		}
		else if($nbCol % $nbMaxCol == $nbMaxCol-1){
			echo "<td>";
			echo "<p align='center' class='titresectiontext'>".$record["titre".$_SESSION["__langue__"]]."</p>";
			echo "<a href='$pdfFile'><img border='0' src='".$imageFile."'/></a>";
			echo "<p align='center'>".date_sql2MonthYear($record["date"],$VAR_G_MOIS)."</p>";
			echo "<p align='center'><a href='$pdfFile'>".tailleFichier($pdfFile)."</a></p>";
			echo "</td>";
			insererEncadrementDroit();
		}
		else{
			echo "<td>";
			echo "<p align='center' class='titresectiontext'>".$record["titre".$_SESSION["__langue__"]]."</p>";
			echo "<a href='$pdfFile'><img border='0' src='".$imageFile."'/></a>";
			echo "<p align='center'>".date_sql2MonthYear($record["date"],$VAR_G_MOIS)."</p>";
			echo "<p align='center'><a href='$pdfFile'>".tailleFichier($pdfFile)."</a></p>";
			echo "</td>";
			insererSeparationEncadermentVerticale();
		}
		$nbCol++;
	}

	if($nbCol % $nbMaxCol == $nbMaxCol-1){
		echo "<td/>";
		insererEncadrementDroit();
	}
	else if($nbCol % $nbMaxCol > 0){
		while($nbCol % $nbMaxCol < $nbMaxCol-1){
			echo "<td/>";
			insererSeparationEncadermentVerticale();
			$nbCol++;
		}
		echo "<td/>";
		insererEncadrementDroit();
	}

	insererEncadrementBas($nbMaxCol,true);
	echo "</table>";


	/*
	while($record = mysql_fetch_array($recordset)){
		$imageFile = PATH_DOCUMENTS.$_SESSION["__langue__"]."_".substr($record["fichier"],0,strlen($record["fichier"])-3)."jpg";
		$pdfFile = PATH_DOCUMENTS.$_SESSION["__langue__"]."_".$record["fichier"];
		echo "<table  border='0' align='center'><tr><td>";
			echo "<img src='".$imageFile."'/>";
		echo "</td><td>";
			echo "<p>".$record["titre".$_SESSION["__langue__"]]."</p>";
			echo "<p>".date_sql2MonthYear($record["date"],$VAR_G_MOIS)."</p>";
			echo "<p>".tailleFichier($pdfFile)."</p>";
		echo "</td></tr></table>";


	}

	*/

	//encadrerBas();
?>
