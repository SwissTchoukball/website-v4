<div id="statsClubs">
	<?php
	echo "<h4>Nombre de clubs</h4>";
	$requeteNombreClubs="SELECT COUNT(*) AS nbClubsFSTB FROM ClubsFstb WHERE actif=1";
	$retour=mysql_query($requeteNombreClubs);
	$donnee=mysql_fetch_assoc($retour);
	$nbClubsFSTB=$donnee['nbClubsFSTB'];
	echo "<p>Il y a ".$nbClubsFSTB." clubs membres de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".</p>";

	echo "<h4>Nombre de membres par club</h4>";
	$requeteNombreMembresParClub="SELECT ClubsFstb.club, ClubsFstb.nbIdClub AS id, COUNT(if(idStatus=3,1,NULL)) AS nbMembresActifs, COUNT(if(idStatus=6,1,NULL)) AS nbMembresJuniors, COUNT(if(idStatus=5,1,NULL)) AS nbMembresSoutiens, COUNT(if(idStatus=4,1,NULL)) AS nbMembresPassifs, COUNT(if(idStatus=23,1,NULL)) AS nbMembresVIP, COUNT(if(idStatus!=3 AND idStatus!=4 AND idStatus!=5 AND idStatus!=6 AND idStatus!=23,1,NULL)) AS nbMembresAutres, COUNT(idDbdPersonne) AS nbMembresTotal FROM ClubsFstb LEFT OUTER JOIN DBDPersonne ON ClubsFstb.nbIdClub=DBDPersonne.idClub WHERE actif=1 GROUP BY DBDPersonne.idClub ";
	if(isset($_GET['ordre'])){
		$ordre=$_GET['ordre'];
		if($ordre=="ID"){
			$requeteNombreMembresParClub.="ORDER BY id DESC";
		}
		elseif($ordre=="club"){
			$requeteNombreMembresParClub.="ORDER BY club ASC";
		}
		elseif($ordre=="actifs"){
			$requeteNombreMembresParClub.="ORDER BY nbMembresActifs DESC";
		}
		elseif($ordre=="juniors"){
			$requeteNombreMembresParClub.="ORDER BY nbMembresJuniors DESC";
		}
		elseif($ordre=="soutiens"){
			$requeteNombreMembresParClub.="ORDER BY nbMembresSoutiens DESC";
		}
		elseif($ordre=="passifs"){
			$requeteNombreMembresParClub.="ORDER BY nbMembresPassifs DESC";
		}
		elseif($ordre=="VIP"){
			$requeteNombreMembresParClub.="ORDER BY nbMembresVIP DESC";
		}
		elseif($ordre=="autres"){
			$requeteNombreMembresParClub.="ORDER BY nbMembresAutres DESC";
		}
		elseif($ordre=="total"){
			$requeteNombreMembresParClub.="ORDER BY nbMembresTotal DESC";
		}
		$requeteNombreMembresParClub.=", club ASC";
	}
	else{
		$requeteNombreMembresParClub.="ORDER BY ClubsFstb.nomPourTri ASC";
	}

	//echo $requeteNombreMembresParClub;
	$retour=mysql_query($requeteNombreMembresParClub);
	echo "<table>";
	echo "<tr>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=id'>ID</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=club'>Club</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=actifs'>Actifs</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=juniors'>Juniors</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=soutiens'>Soutiens</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=passifs'>Passifs</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=VIP'>VIP</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=autres'>Autres</a></th>";
	echo "<th><a href='?menuselection=".$menuselection."&smenuselection=".$smenuselection."&ordre=total'>Total</a></th>";
	echo "</tr>";
	$totalMembresActifs=0;
	$totalMembresJuniors=0;
	$totalMembresSoutiens=0;
	$totalMembresPassifs=0;
	$totalMembresVIP=0;
	$totalMembresAutre=0;

	while($donnees=mysql_fetch_assoc($retour)){
		echo "<tr>";
		echo "<td>".$donnees['id']."</td>";
		echo "<td>".$donnees['club']."</td>";
		echo "<td>".$donnees['nbMembresActifs']."</td>";
		echo "<td>".$donnees['nbMembresJuniors']."</td>";
		echo "<td>".$donnees['nbMembresSoutiens']."</td>";
		echo "<td>".$donnees['nbMembresPassifs']."</td>";
		echo "<td>".$donnees['nbMembresVIP']."</td>";
		echo "<td>".$donnees['nbMembresAutres']."</td>";
		echo "<td>".$donnees['nbMembresTotal']."</td>";
		echo "</tr>";
		$totalMembresActifs+=$donnees['nbMembresActifs'];
		$totalMembresJuniors+=$donnees['nbMembresJuniors'];
		$totalMembresSoutiens+=$donnees['nbMembresSoutiens'];
		$totalMembresPassifs+=$donnees['nbMembresPassifs'];
		$totalMembresVIP+=$donnees['nbMembresVIP'];
		$totalMembresAutre+=$donnees['nbMembresAutres'];

		/*if(isset($_GET['save']) && $_SESSION['__userLevel__'] == 0) {
			$saveStatisticsQuery = "INSERT INTO DBDStatsClubs (idClub, date, nbMembresActifs, nbMembresJuniors, nbMembresSoutiens, nbMembresPassifs, nbMembresVIP, nbMembresAutres) VALUES (".$donnees['id'].", '".date('Y-m-d')."', ".$donnees['nbMembresActifs'].", ".$donnees['nbMembresJuniors'].", ".$donnees['nbMembresSoutiens'].", ".$donnees['nbMembresPassifs'].", ".$donnees['nbMembresVIP'].", ".$donnees['nbMembresAutres'].")";
			//echo $saveStatisticsQuery."<br />";
			if (mysql_query($saveStatisticsQuery)) {
				echo "<p class='success'>Statistiques de \"".$donnees['club']."\" enregistr�es.</p>";
			} else {
				echo "<p class='error'>Erreur lors de l'enregistrement des statistiques pour \"".$donnees['club']."\".<br />Les statistiques ont peut-�tre d�j� �t� enregistr�es aujourd'hui.</p>";
			}
		}*/
	}
	echo "<tr>";
	echo "<th colspan='2'>TOTAUX</th>";
	echo "<th>".$totalMembresActifs."</th>";
	echo "<th>".$totalMembresJuniors."</th>";
	echo "<th>".$totalMembresSoutiens."</th>";
	echo "<th>".$totalMembresPassifs."</th>";
	echo "<th>".$totalMembresVIP."</th>";
	echo "<th>".$totalMembresAutre."</th>";
	$totalMembres=$totalMembresActifs+$totalMembresJuniors+$totalMembresSoutiens+$totalMembresPassifs+$totalMembresVIP+$totalMembresAutre;
	echo "<th id='totalMembresFSTB' rowspan='2'>".$totalMembres."</th>";
	echo "</tr>";

	echo "<tr>";
	echo "<th colspan='2'>Actifs | Inactifs</th>";
	$totalVraisMembres=$totalMembresActifs+$totalMembresJuniors;
	echo "<th colspan='2'>".$totalVraisMembres."</th>";
	$totalMembresInactifs=$totalMembresSoutiens+$totalMembresPassifs+$totalMembresVIP+$totalMembresAutre;
	echo "<th colspan='4'>".$totalMembresInactifs."</th>";
	echo "</tr>";
	echo "</table>";


	if ($_SESSION['__userLevel__'] == 0) {
		?>
		<p><a href="crons/save-clubs-stats.php" target="_blank">Enregistrer les statistiques actuelles</a> (action automatiquement effectu�e tous les 1er janvier)</p>
		<?php
	}


	echo "<h4>R�partition Hommes/Femmes</h4>";
	$requeteRepartitionSexes = "SELECT COUNT(if(idStatus=3 && idSexe=2,1,NULL)) AS nbHActifs, COUNT(if(idStatus=3 && idSexe=3,1,NULL)) AS nbFActifs, COUNT(if(idStatus=3 && idSexe=1,1,NULL)) AS nbIActifs, COUNT(if(idStatus=6 && idSexe=2,1,NULL)) AS nbHJuniors, COUNT(if(idStatus=6 && idSexe=3,1,NULL)) AS nbFJuniors, COUNT(if(idStatus=6 && idSexe=1,1,NULL)) AS nbIJuniors FROM DBDPersonne, ClubsFstb WHERE (idStatus=3 OR idStatus=6) AND ClubsFstb.nbIdClub=DBDPersonne.idClub AND ClubsFstb.actif=1";
	$retourRepartitionSexes = mysql_query($requeteRepartitionSexes);
	$donneesRepSexes = mysql_fetch_assoc($retourRepartitionSexes);

	$nbHActifs = $donneesRepSexes['nbHActifs'];
	$nbFActifs = $donneesRepSexes['nbFActifs'];
	$nbIActifs = $donneesRepSexes['nbIActifs'];
	$totalActifs = $nbHActifs + $nbFActifs + $nbIActifs;

	$nbHJuniors = $donneesRepSexes['nbHJuniors'];
	$nbFJuniors = $donneesRepSexes['nbFJuniors'];
	$nbIJuniors = $donneesRepSexes['nbIJuniors'];
	$totalJuniors = $nbHJuniors + $nbFJuniors + $nbIJuniors;

	$nbHActifsEtJuniors = $nbHActifs + $nbHJuniors;
	$nbFActifsEtJuniors = $nbFActifs + $nbFJuniors;
	$nbIActifsEtJuniors = $nbIActifs + $nbIJuniors;
	$totalActifsEtJuniors = $totalActifs + $totalJuniors;

	$pourcHActifs = round(($nbHActifs / $totalActifs) * 100,2);
	$pourcFActifs = round(($nbFActifs / $totalActifs) * 100,2);
	$pourcIActifs = round(($nbIActifs / $totalActifs) * 100,2);

	$pourcHJuniors = round(($nbHJuniors / $totalJuniors) * 100,2);
	$pourcFJuniors = round(($nbFJuniors / $totalJuniors) * 100,2);
	$pourcIJuniors = round(($nbIJuniors / $totalJuniors) * 100,2);

	$pourcHActifsEtJuniors = round(($nbHActifsEtJuniors / $totalActifsEtJuniors) * 100,2);
	$pourcFActifsEtJuniors = round(($nbFActifsEtJuniors / $totalActifsEtJuniors) * 100,2);
	$pourcIActifsEtJuniors = round(($nbIActifsEtJuniors / $totalActifsEtJuniors) * 100,2);

	?>
	<table>
		<tr>
			<th></th>
			<th><img src="admin/images/male.png" alt="hommes" /></th>
			<th><img src="admin/images/female.png" alt="femmes" /></th>
			<th><img src="admin/images/question.png" alt="ind�fini" /></th>
		</tr>
		<tr>
			<th>Actifs</th>
			<td><?php echo $nbHActifs; ?> (<?php echo $pourcHActifs; ?>%)</td>
			<td><?php echo $nbFActifs; ?> (<?php echo $pourcFActifs; ?>%)</td>
			<td><?php echo $nbIActifs; ?> (<?php echo $pourcIActifs; ?>%)</td>
		</tr>
		<tr>
			<th>Juniors</th>
			<td><?php echo $nbHJuniors; ?> (<?php echo $pourcHJuniors; ?>%)</td>
			<td><?php echo $nbFJuniors; ?> (<?php echo $pourcFJuniors; ?>%)</td>
			<td><?php echo $nbIJuniors; ?> (<?php echo $pourcIJuniors; ?>%)</td>
		</tr>
		<tr>
			<th>Actifs + juniors</th>
			<td><?php echo $nbHActifsEtJuniors; ?> (<?php echo $pourcHActifsEtJuniors; ?>%)</td>
			<td><?php echo $nbFActifsEtJuniors; ?> (<?php echo $pourcFActifsEtJuniors; ?>%)</td>
			<td><?php echo $nbIActifsEtJuniors; ?> (<?php echo $pourcIActifsEtJuniors; ?>%)</td>
		</tr>
	</table>

</div>
