<?
	function getTeamQuery($teamID) {
		$query = "SELECT p.idDbdPersonne, p.nom, p.prenom, p.idFITBMatchDB, p.idSexe, cnm.numero, cnm.idFonction, cnf.titreH".$_SESSION['__langue__']." AS titreH, cnf.titreF".$_SESSION['__langue__']." AS titreF, clubs.club
				  FROM CadreNational_Membres cnm
				  LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = cnm.idPersonne
				  LEFT OUTER JOIN CadreNational_Fonctions cnf ON cnf.id = cnm.idFonction
				  LEFT OUTER JOIN ClubsFstb clubs ON clubs.nbIdClub = p.idClub
				  WHERE cnm.idEquipe=".$teamID."
				  AND (ISNULL(cnm.dateFin) OR cnm.dateFin >= CURDATE())
				  ORDER BY cnm.idFonction, p.nom, p.prenom";
		return $query;
	}

	function afficherPersonneTeam($record, $extensionPhotos, $afficherNumero){
		echo "<div class='joueur-es'>";
			if($record["idFonction"] == 1){
				$extensionPhotos = "_coach".$extensionPhotos;
			}
			$nomFichierPhoto = nomPhotoValide($record["nom"],$record["prenom"],$extensionPhotos, "jpg");
			// le fichier existe ?
			if(is_file($_ENV["DOCUMENT_ROOT"].$nomFichierPhoto)){
				echo "<div class='photo-joueur-es'><img src='".$nomFichierPhoto."' alt='Photo de ".$record["prenom"]." ".$record["nom"]."'></div>";

			}
			else{
				echo "<div class='photo-joueur-es'><img src='".VAR_REP_IMAGE_EQUIPE_SUISSE."pas-de-photo_port.jpg' alt='".$record["prenom"]." ".$record["nom"]." n'a pas de photo (".$nomFichierPhoto.")' /></div>";
			}

			echo "<div class='infos-joueur-es'>";
			echo "<span class='nom-joueur-es'>".$record["prenom"]."<br />".$record["nom"]."</span><br />";
			if($record['idFonction'] != 4){
				if ($record['idSexe'] == 3) {
					echo $record['titreF']."<br />";
				} else {
					echo $record['titreH']."<br />";
				}
			}
			echo $record["club"]."<br />";
			if($record["idFITBMatchDB"]!=0){
				echo "<a href='http://www.fitbcompetitions.org/index.php?detail=player&id=".$record["idFITBMatchDB"]."' target='_blank'>Fiche FITB</a><br />";
			}
			echo "</div>";
			echo "<div class='numero-joueur-es'>";
			if($afficherNumero && $record['numero'] != null){
				echo $record['numero'];
			}
			echo "</div>";

		echo "</div>";
	}
?>
