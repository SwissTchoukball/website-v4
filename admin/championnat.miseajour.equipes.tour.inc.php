<?

$debugClassement = false;

if(isset($saison)){
	// S�lection de la politique des points de l'ann�e choisie.
	$retour = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=".$saison."");
	$donnees = mysql_fetch_array($retour);
	$pointsMatchGagne = $donnees['pointsMatchGagne'];
	$pointsMatchNul = $donnees['pointsMatchNul'];
	$pointsMatchPerdu = $donnees['pointsMatchPerdu'];
	$pointsMatchForfait = $donnees['pointsMatchForfait'];
	$nbMatchGagnantPlayoff = $donnees['nbMatchGagnatPlayoff'];
	$nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
	$nbMatchGagnatPromoReleg = $donnees['nbMatchGagnatPromoReleg'];
	$systemePassageTours = $donnees['systemePassageTours'];
	if(isset($categorie)){
		$critereCategorie = "AND idCategorie=".$categorie;
	}
	else{
		$critereCategorie = "";
	}

	if(isset($tour)){
		$critereTour = "AND idTour=".$tour;
	}
	else{
		$critereTour = "";
	}

	if(isset($groupe)){
		$critereGroupe = "AND noGroupe=".$groupe;
	}
	else{
		$critereGroupe = "";
	}
	// Requete et boucle pour faire chaque chaque equipe de chaque tour de la saison.
	$requete = "SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$saison." ".$critereCategorie." ".$critereTour." ".$critereGroupe." ORDER BY idCategorie, idTour";
	if($debugClassement) {
		echo "<p>".$requete."</p>";
	}
	$retour = mysql_query($requete);
	while($donnees = mysql_fetch_array($retour)){
		$saison = $donnees['saison'];
		$idCategorie = $donnees['idCategorie'];
		$idTour = $donnees['idTour'];
		$idGroupe = $donnees['noGroupe'];

		//On chercher combien il y a de groupes ? ce tour.
		$retourZ = mysql_query("SELECT * FROM Championnat_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND idGroupe=".$idGroupe."");
		$donneesZ = mysql_fetch_array($retourZ);

		// Si c'est le premier tour, promo/releg, playout, playoff ou tour final, les compteurs sont ? 0
		if($idTour == 1 OR $idTour == 10000 OR $idTour == 2000 OR $idTour == 3000 OR $idTour == 4000){
			$points = 0;
			$nbPointMarque = 0;
			$nbPointRecu = 0;
			$position = 0;
		}
		// Si c'est le 2�me, 3�me ou 4�me Tour comptabiliser les points du tour pr�c�dent.
		else{
			$retourA = mysql_query("SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$donnees['idTourPrecedent']." AND idEquipe=".$donnees['idEquipe']."");
			$donneesA = mysql_fetch_array($retourA);
			$points = $donneesA['points'];

			// On cherche le nombre d'�quipes au tour pr�c�dent
			$retourB = mysql_query("SELECT COUNT(*) AS nbreEquipesPrecedent FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$donnees['idTourPrecedent']."");
			$donneesB = mysql_fetch_array($retourB);
			$nbreEquipesPrecedent = $donneesB['nbreEquipesPrecedent'];

			// On cherche le nombre d'�quipes ? ce tour
			$retourC = mysql_query("SELECT COUNT(*) AS nbreEquipes FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$idGroupe."");
			$donneesC = mysql_fetch_array($retourC);
			$nbreEquipes = $donneesC['nbreEquipes'];
			if($nbreEquipesPrecedent == $nbreEquipes){
				// On ne fait rien, on garde les valeurs actuelles.
			}
			elseif($nbreEquipesPrecedent < $nbreEquipes){
				echo "ERREUR A: Il ne peut pas y avoir plus d'�quipe ? ce tour qu'au tour pr�c�dent.<br />";
				echo "Concerne saison ".$saison.", idCategorie : ".$idCateogrie.", ce tour : ".$idTour.", tour pr�c�dent".$donnees['idTourPrecedent'].", groupe ".$idGroupe."<br />";
			}
			elseif($nbreEquipesPrecedent > $nbreEquipes){
				$points = 0;
				$nbPointMarque = 0;
				$nbPointRecu = 0;
				$position = 0;
				if($systemePassageTours==1){
					echo "Equipes du groupe ".$idGroupe." du tour ".$idTour." avec l'�quipe ".$donnees['idEquipe']." :<br />";
					$retourD = mysql_query("SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$donnees['idTourPrecedent']."");
					while($donneesD=mysql_fetch_array($retourD)){ // Calcul pour faire chaque �quipe d'un tour
						$retourDbis = mysql_query("SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$idGroupe."");
						while($donneesDbis=mysql_fetch_array($retourDbis)){
							if($donnees['idEquipe'] != $donneesD['idEquipe'] AND $donneesD['idEquipe'] == $donneesDbis['idEquipe']){ // On ne prend que les points du tour pr�c�dent des �quipes qui se trouvent dans le m?me groupe que l'�quipe en cours de traitement de ce tour.
								echo $donneesD['idEquipe']." ";

								// Requete et boucle pour faire chaque equipe d'un tour
								$retourE = mysql_query("SELECT * FROM Championnat_Matchs WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour<".$idTour." AND ((equipeA=".$donnees['idEquipe']." AND equipeB=".$donneesDbis['idEquipe'].") OR (equipeB=".$donnees['idEquipe']." AND equipeA=".$donneesDbis['idEquipe']."))");
								while($donneesE=mysql_fetch_array($retourE)){
										if($donneesE['equipeA']==$donnees['idEquipe']){ // Si l'�quipe s�l�ctionn�e de Championnat_Equipes_Tours = Equipe A
											if($donneesE['pointsA']>$donneesE['pointsB']){ // Match gagn�
												if($donneesE['forfait'] == 0){ // L'autre �quipe n'a pas d�clar� forfait
													$points = $points+$pointsMatchGagne;
												}
												elseif($donneesE['forfait'] == 1){ // L'autre �quipe a d�clar� forfait
													$points = $points+$pointsMatchGagne;
												}
												else{
													echo "ERREUR B1: Le match n'a pas �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
													echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
												}
											}
											elseif($donneesE['pointsA']<$donneesE['pointsB']){ // Match perdu
												if($donneesE['forfait'] == 0){ // L'�quipe n'a pas d�clar� forfait
													$points = $points+$pointsMatchPerdu;
												}
												elseif($donneesE['forfait'] == 1){ // L'�quipe a d�clar� forfait
													$points = $points+$pointsMatchForfait;
												}
												else{
													echo "ERREUR B2: Le match n'a pas �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
													echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
												}
											}
                                            elseif($donneesE['pointsA']==0 && $donneesE['pointsB']==0){
                                                // Match pas encore jou�, ne rien faire.
                                            }
											elseif($donneesE['pointsA']==$donneesE['pointsB'] && ($donnees['idTour']==2000 OR $donnees['idTour']==3000 OR $donnees['idTour']==4000)){ //Match de promo/releg, playoff ou playout
												echo "ERREUR C1: Un match de play-off, play-out, promotion/rel�gation, tour final ne peut pas se terminer sur une �galit�.<br />"; // Ne devrait pas arriver car d�j? v�rifi� avant
												echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
											}
											else{ // Match nul
												$points = $points+$pointsMatchNul;
											}
											$nbPointMarque = $nbPointMarque+$donneesE['pointsA'];
											$nbPointRecu = $nbPointRecu+$donneesE['pointsB'];
										}
										elseif($donneesE['equipeB']==$donnees['idEquipe']){ // Sinon si l'�quipe s�l�ctionn�e de Championnat_Equipes_Tours = Equipe B
											if($donneesE['pointsB']>$donneesE['pointsA']){ // Match gagn�
												if($donneesE['forfait'] == 0){ // L'autre �quipe n'a pas d�clar� forfait
													$points = $points+$pointsMatchGagne;
												}
												elseif($donneesE['forfait'] == 1){ // L'autre �quipe a d�clar� forfait
													$points = $points+$pointsMatchGagne;
												}
												else{
													echo "ERREUR B3: On ne sait pas si le match a �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
													echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
												}
											}
											elseif($donneesE['pointsB']<$donneesE['pointsA']){ // Match perdu
												if($donneesE['forfait'] == 0){ // L'�quipe n'a pas d�clar� forfait
													$points = $points+$pointsMatchPerdu;
												}
												elseif($donneesE['forfait'] == 1){ // L'�quipe a d�clar� forfait
													$points = $points+$pointsMatchForfait;
												}
												else{
													echo "ERREUR B4: On ne sait pas si le match a �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
													echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
												}
											}
                                            elseif($donneesE['pointsA']==0 && $donneesE['pointsB']==0){
                                                // Match pas encore jou�, ne rien faire.
                                            }
											elseif($donneesE['pointsB']==$donneesE['pointsA'] && ($donnees['idTour']==2000 OR $donnees['idTour']==3000 OR $donnees['idTour']==4000)){ //Match de promo/releg, playoff ou playout
												echo "ERREUR C2: Un match de play-off, play-out, promotion/rel�gation, tour final ne peut pas se terminer sur une �galit�.<br />"; // Ne devrait pas arriver car d�j? v�rifi� avant
												echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
											}
											else{ // Match nul
												$points = $points+$pointsMatchNul;
											}
											$nbPointMarque = $nbPointMarque+$donneesE['pointsB'];
											$nbPointRecu = $nbPointRecu+$donneesE['pointsA'];
										}
								} // fin boucle donneesE
								echo ": ".$points." / ";
							}
							else{
								// on ne fait rien car l'�quipe du tour pr�c�dent ne se retrouve pas dans le m?me groupe pour ce tour ou bien l'�quipe du tour pr�c�dent est l'�quipe en cours de traitement. Ce qui ne n�cessite pas de calcul vu qu'une �quipe ne peut pas jouer contre elle-m?me.
							}
						} // fin boucle donneesDbis
					} // fin boucle donnees
					echo "<br />";
				} // fin if systeme de passage de tour 1
				elseif($systemePassageTours == 2){
					$retourD = mysql_query("SELECT * FROM Championnat_Equipes_Tours WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$donnees['idTourPrecedent']." AND idEquipe=".$donnees['idEquipe']."");
					$donneesD=mysql_fetch_array($retourD);
					$points = round($donneesD['points']/2);
					$nbPointMarque = 0;
					$nbPointRecu = 0;

				} // fin if systeme de passage de tour 2
				elseif($systemePassageTours == 0){
					echo "Pas de deuxi�me tour";
				}
			}
		}

		$nbMatchJoue = 0;
		$nbMatchGagne = 0;
		$nbMatchNul = 0;
		$nbMatchPerdu = 0;
		$nbMatchForfait = 0;
		// Requete et boucle pour faire chaque equipe d'un tour
		$selectMatchsQuery = "SELECT * FROM Championnat_Matchs WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND (equipeA=".$donnees['idEquipe']." OR equipeB=".$donnees['idEquipe'].")";
		if($debugClassement) {
			echo "<p>".$selectMatchsQuery."</p>";
		}
		$retourE = mysql_query($selectMatchsQuery);
		while($donneesE=mysql_fetch_array($retourE)){
			if($debugClassement) {
				echo "<p>ID de l'�quipe : ".$donnees['idEquipe']."</p>";
			}
			if($donneesE['equipeA']==$donnees['idEquipe']){ // Si l'�quipe s�l�ctionn�e de Championnat_Equipes_Tours = Equipe A
				if($donneesE['pointsA']>$donneesE['pointsB']){ // Match gagn�
					if($donneesE['forfait'] == 0){ // L'autre �quipe n'a pas d�clar� forfait
						$nbMatchGagne++;
						$points = $points+$pointsMatchGagne;
					}
					elseif($donneesE['forfait'] == 1){ // L'autre �quipe a d�clar� forfait
						$nbMatchGagne++;
						$points = $points+$pointsMatchGagne;
					}
					else{
						echo "ERREUR B5: Le match n'a pas �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
						echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
					}
				}
				elseif($donneesE['pointsA']<$donneesE['pointsB']){ // Match perdu
					if($donneesE['forfait'] == 0){ // L'�quipe n'a pas d�clar� forfait
						$nbMatchPerdu++;
						$points = $points+$pointsMatchPerdu;
					}
					elseif($donneesE['forfait'] == 1){ // L'�quipe a d�clar� forfait
						$nbMatchForfait++;
						$points = $points+$pointsMatchForfait;
					}
					else{
						echo "ERREUR B6: Le match n'a pas �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
						echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
					}
				}
				elseif($donneesE['pointsA']==0 && $donneesE['pointsB']==0){
                    // Match pas encore jou�, ne rien faire.
				}
				elseif($donneesE['pointsA']==$donneesE['pointsB'] && ($donnees['idTour']==2000 OR $donnees['idTour']==3000 OR $donnees['idTour']==4000)){ //Match de promo/releg, playoff ou playout
					echo "ERREUR C3: Un match de play-off, play-out, promotion/rel�gation, tour final ne peut pas se terminer sur une �galit�.<br />"; // Ne devrait pas arriver car d�j? v�rifi� avant
					echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
				}
				else{ // Match nul
					$nbMatchNul++;
					$points = $points+$pointsMatchNul;
				}
				$nbPointMarque = $nbPointMarque+$donneesE['pointsA'];
				$nbPointRecu = $nbPointRecu+$donneesE['pointsB'];

				// D�termination de la position au classement.
				if($idTour == 10000 OR ($idTour == 4000 AND $nbMatchGagnantPlayoff==1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout==1)){ // Si il n'y a qu'un match gagnant, on se base sur le score
					if($donneesE['idTypeMatch'] >= 1 AND $donneesE['idTypeMatch'] < 100 && $donneesE['pointsA']>$donneesE['pointsB']){ // Match pour la X?me place gagn�
						$position = ($donneesE['idTypeMatch']*2)-1;
					}
					elseif($donneesE['idTypeMatch'] >= 1 AND $donneesE['idTypeMatch'] < 100 && $donneesE['pointsA']<$donneesE['pointsB']){ // Match pour la X?me place perdu
						$position = $donneesE['idTypeMatch']*2;
					}
					elseif($donneesE['idTypeMatch'] >= 100 AND $donneesE['idTypeMatch'] < 1000 OR $donneesE['idTypeMatch'] == 0){ // Match de classement ou match normal
						$position = $donneesE['idTypeMatch'];
					}/* else {
						$position = 0;
					}*/
					$positionPourRequete = ", position=".$position;
				}
				else{
			        // Se fait manuellement
			        //$positionPourRequete = ""; // enlev� car remettait la position d'une �quipe dans le tour final � 0.
				}
				//echo "<p>A ".$positionPourRequete."</p>";
			}
			elseif($donneesE['equipeB']==$donnees['idEquipe']){ // Sinon si l'�quipe s�l�ctionn�e de Championnat_Equipes_Tours = Equipe B
				if($donneesE['pointsB']>$donneesE['pointsA']){ // Match gagn�
					if($donneesE['forfait'] == 0){ // L'autre �quipe n'a pas d�clar� forfait
						$nbMatchGagne++;
						$points = $points+$pointsMatchGagne;
					}
					elseif($donneesE['forfait'] == 1){ // L'autre �quipe a d�clar� forfait
						$nbMatchGagne++;
						$points = $points+$pointsMatchGagne;
					}
					else{
						echo "ERREUR B7: On ne sait pas si le match a �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
						echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
					}
				}
				elseif($donneesE['pointsB']<$donneesE['pointsA']){ // Match perdu
					if($donneesE['forfait'] == 0){ // L'�quipe n'a pas d�clar� forfait
						$nbMatchPerdu++;
						$points = $points+$pointsMatchPerdu;
					}
					elseif($donneesE['forfait'] == 1){ // L'�quipe a d�clar� forfait
						$nbMatchForfait++;
						$points = $points+$pointsMatchForfait;
					}
					else{
						echo "ERREUR B8: On ne sait pas si le match a �t� d�clar� forfait ou pas.<br />"; // ne devrait pas arriver car la valeur par d�faut dans mysql est 0
						echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
					}
				}
				elseif($donneesE['pointsA']==0 && $donneesE['pointsB']==0){
                    // Match pas encore jou�, ne rien faire.
				}
				elseif($donneesE['pointsB']==$donneesE['pointsA'] && ($donnees['idTour']==2000 OR $donnees['idTour']==3000 OR $donnees['idTour']==4000)){ //Match de promo/releg, playoff ou playout
					echo "ERREUR C4: Un match de play-off, play-out, promotion/rel�gation, tour final ne peut pas se terminer sur une �galit�.<br />"; // Ne devrait pas arriver car d�j? v�rifi� avant
					echo "Match concern� :".$donneesE['equipeA']." - ".$donneesE['equipeB']." de la saison ".$saison.". idCategorie : ".$idCategorie.", idTour : ".$idTour.", groupe ".$idGroupe."<br />";
				}
				else{ // Match nul
					$nbMatchNul++;
					$points = $points+$pointsMatchNul;
				}
				$nbPointMarque = $nbPointMarque+$donneesE['pointsB'];
				$nbPointRecu = $nbPointRecu+$donneesE['pointsA'];

				// D�termination de la position au classement.
				if($idTour == 10000 OR ($idTour == 4000 AND $nbMatchGagnantPlayoff==1) OR ($idTour == 3000 AND $nbMatchGagnantPlayout==1)){ // Si il n'y a qu'un match gagnant, on se base sur le score
					if($donneesE['idTypeMatch'] >= 1 AND $donneesE['idTypeMatch'] < 100 && $donneesE['pointsA']<$donneesE['pointsB']){ // Match pour la X?me place gagn�
						$position = ($donneesE['idTypeMatch']*2)-1;
					}
					elseif($donneesE['idTypeMatch'] >= 1 AND $donneesE['idTypeMatch'] < 100 && $donneesE['pointsA']>$donneesE['pointsB']){ // Match pour la X?me place perdu
						$position = $donneesE['idTypeMatch']*2;
					}
					elseif(($donneesE['idTypeMatch'] >= 100 AND $donneesE['idTypeMatch'] < 1000) OR $donneesE['idTypeMatch'] == 0 OR $donneesE['idTypeMatch'] == 2000){ // Match de classement ou match normal ou match de demi-finale
						$position = $donneesE['idTypeMatch'];
					} /*else {
						$position = 0;
					}*/
					$positionPourRequete = ", position=".$position;
				}
				else{
			        // Se fait manuellement
			        //$positionPourRequete = ""; // enlev� car remettait la position d'une �quipe dans le tour final � 0.
				}
				//echo "<p>B ".$positionPourRequete."</p>";
			}
			elseif($donneesE['pointsA']==0 && $donneesE['pointsB']==0){
                // Match pas encore jou�, ne rien faire.
			}
			else{
                $nbMatchJoue++;
            }
			if ($debugClassement) {
				echo "<p>Nb de points : ".$points."</p>";
			}
		} // fin boucle donneesE
		$goolaverage = $nbPointMarque-$nbPointRecu;
		$updateQuery = "UPDATE Championnat_Equipes_Tours SET nbMatchJoue=".$nbMatchJoue.", nbMatchGagne=".$nbMatchGagne.", nbMatchNul=".$nbMatchNul.", nbMatchPerdu=".$nbMatchPerdu.", nbMatchForfait=".$nbMatchForfait.", nbPointMarque=".$nbPointMarque.", nbPointRecu=".$nbPointRecu.", goolaverage=".$goolaverage.", points=".$points."".$positionPourRequete." WHERE saison=".$saison." AND idCategorie=".$idCategorie." AND idTour=".$idTour." AND noGroupe=".$idGroupe." AND idEquipe=".$donnees['idEquipe']."";
		if ($debugClassement) {
			echo "<p>".$updateQuery."</p>";
		}
		mysql_query($updateQuery);
	} // fin boucle donnees
} // fin de if isset annee
else{
	echo "ERREUR D: saison ind�finie.<br />";
}
?>