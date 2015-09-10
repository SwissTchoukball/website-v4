<?php
if (isset($_GET['matchID']) && isValidMatchID($_GET['matchID'])) {
	//echo '<p><a href="?menuselection='.$menuselection.'&smenuselection='.$smenuselection.'">Retour au programme</a></p>';
	$referees = getReferees();
	// ------------------------------------------ //
	// ! Affichage d'un match
	// ------------------------------------------ //
	$matchID = $_GET['matchID'];
	//TODO: se faire la réflexion s'il ne serait pas mieux de faire une requête séparée pour les arbitres...
	$matchQuery = "SELECT e1.equipe AS nomEquipeA, e2.equipe AS nomEquipeB, m.pointsA, m.pointsB, p.scoreA AS periodScoreA, p.scoreB AS periodScoreB, p.noPeriode, p.idArbitreA, p.idArbitreB, p.idArbitreC,
						  m.saison, c.categorie".$_SESSION['__langue__']." AS nomCategorie, m.idTour, tt.tour".$_SESSION['__langue__']." AS nomTour, m.noGroupe, m.journee, m.idTypeMatch, tm.type".$_SESSION['__langue__']." AS nomTypeMatch,
						  m.dateDebut, m.heureDebut, l.id AS idLieu, l.nom AS nomLieu, l.adresse, l.npa, l.ville, m.prixEntree, m.nbSpectateurs, m.flickrSetId
				   FROM Championnat_Matchs m
				   LEFT OUTER JOIN Championnat_Equipes e1 ON m.equipeA = e1.idEquipe
				   LEFT OUTER JOIN Championnat_Equipes e2 ON m.equipeB = e2.idEquipe
				   LEFT OUTER JOIN Championnat_Categories c ON m.idCategorie = c.idCategorie
				   LEFT OUTER JOIN Championnat_Types_Matchs tm ON m.idTypeMatch = tm.idTypeMatch
				   LEFT OUTER JOIN Championnat_Types_Tours tt ON m.idTour = tt.idTour
				   LEFT OUTER JOIN Lieux l ON m.idLieu = l.id
				   LEFT OUTER JOIN Championnat_Periodes p ON p.idMatch = m.idMatch
				   WHERE m.idMatch = ".$matchID."
				   ORDER BY p.noPeriode";
	if (!$matchData = mysql_query($matchQuery)) {
		echo '<p class="error">Erreur lors de la récupération des données du match.<br />Requête: '.$matchQuery.'<br />Message: '.mysql_error().'</p>';
	} else {
		$firstPeriod = true;
		$scoreA = 0;
		$scoreB = 0;
		$periodScoreA = array();
		$periodScoreB = array();
		$idArbitreA = array();
		$idArbitreB = array();
		$idArbitreC = array();

		while ($match = mysql_fetch_assoc($matchData)) { // Loop for each period of the match.
			if ($firstPeriod) {

				// TODO: move all $match[] here.
				$teamAName = $match['nomEquipeA'];
				$teamBName = $match['nomEquipeB'];

				$recordedScoreA = $match['pointsA'];
				$recordedScoreB = $match['pointsB'];

				$saison = $match['saison'];
				$nomCategorie = $match['nomCategorie'];
				$idTour = $match['idTour'];
				$nomTour = $match['nomTour'];
				$idTypeMatch = $match['idTypeMatch'];
				$nomTypeMatch = $match['nomTypeMatch'];
				$noGroupe = $match['noGroupe'];
				$journee = $match['journee'];

				$dateDebut = $match['dateDebut'];
				$heureDebut = $match['heureDebut'];

				$idLieu = $match['idLieu'];
				$nomLieu = $match['nomLieu'];
				$ville = $match['ville'];

				$price = $match['prixEntree'];
				$attendance = $match['nbSpectateurs'];

				$gMapsURL = 'https://maps.google.com/maps?q='.urlencode($match['adresse'].', '.$match['npa'].' '.$match['ville']).'&amp;num=1&amp;t=m&amp;ie=UTF8&amp;output=embed';

				$nbSpectateurs = $match['nbSpectateurs'];

				$flickrSetId = $match['flickrSetId'];

				$firstPeriod = false;
			}

			$noPeriod = $match['noPeriode'];

			$periodScoreA[$noPeriod] = $match['periodScoreA'];
			$periodScoreB[$noPeriod] = $match['periodScoreB'];
			$idArbitreA[$noPeriod] = $match['idArbitreA'];
			$idArbitreB[$noPeriod] = $match['idArbitreB'];
			$idArbitreC[$noPeriod] = $match['idArbitreC'];

			$scoreA += $periodScoreA[$noPeriod];
			$scoreB += $periodScoreB[$noPeriod];
		}

		$detailedScore = true;
		if ($scoreA == 0 && $scoreB == 0) {
			$scoreA = $recordedScoreA;
			$scoreB = $recordedScoreB;
			$detailedScore = false;
		}

		if (isAdmin()) {
			if ($scoreA != $recordedScoreA) {
				printMessage('La somme des tiers et le score final ne sont pas identiques pour l\'équipe A');
			}
			if ($scoreB != $recordedScoreB) {
				printMessage('La somme des tiers et le score final ne sont pas identiques pour l\'équipe B');
			}
		}
		?>
		<div class="match">
			<h1>
				<?php
				$teamAClass = "host";
				$teamBClass = "visitor";
				if ($scoreA > $scoreB) {
					$teamAClass .= " winner";
					$teamBClass .= " loser";
				} else if ($scoreA < $scoreB) {
					$teamAClass .= " loser";
					$teamBClass .= " winner";
				}
				echo '<span class="'.$teamAClass.'">'.$teamAName.'</span>';
				echo $scoreA != 0 ? '<span class="score">' . $scoreA . ' - ' . $scoreB : '<span class="score unplayed">0 - 0';
				echo '</span><span class="'.$teamBClass.'">'.$teamBName.'</span>';
				?>
			</h1>
			<?php
				if ($detailedScore) {
					echo '<p class="detailedScore">';
					for ($p = 1; $p <= $noPeriod; $p++) { // The number of period is equal to the no of the last period
						echo '<span class="period">' . $periodScoreA[$p] . '-' . $periodScoreB[$p] . '</span>';
					}
					echo '</p>';
				}
			?>
			<p class="classification">
				<?php
				$finSaison = $saison+1;
				echo VAR_LANG_SAISON. ' '.$saison.'-'.$finSaison;
				echo ' - '.$nomCategorie;
				echo ' - '.$nomTour;
				if ($noGroupe != 0) {
					echo ' - '.VAR_LANG_GROUPE.' '.$noGroupe;
				}
				if ($idTypeMatch != 0) {
					echo ' - '.$nomTypeMatch;
				}
				if ($idTour > 1000) {
					echo ' - '.VAR_LANG_ACTE.' '.chif_rome($journee);
				} else {
					echo ' - '.VAR_LANG_JOURNEE.' '.$journee;
				}
				?>
			</p>
			<p class="date sideIcon">
				<?php
				echo ucfirst(date_sql2date_joli($dateDebut, 'le', $_SESSION['__langue__']).' '.VAR_LANG_A.' '.time_sql2heure($heureDebut));
				?>
			</p>
			<p class="venue sideIcon">
				<?php
				echo '<a href="/lieu/'.$idLieu.'">'.$nomLieu.', ',$ville.'</a>';
				?>
			</p>
			<p class="price sideIcon">
				<?php
				if ($price == 0) {
					echo 'Entrée libre';
				} else {
					echo money_format('%.2i', $price);
				}
				?>
			</p>
			<p class="referees sideIcon">
				<?php
				$matchReferees = array_merge($idArbitreA, $idArbitreB, $idArbitreC);
				$matchReferees = array_count_values(array_filter($matchReferees)); // counts the number of period refereed by each referee
				if (count($matchReferees) > 0) {
					foreach($matchReferees AS $idArbitre => $nbTiers) {
						if ($referees[$idArbitre]['arbitrePublic']) {
							echo $referees[$idArbitre]['nom'] . ' ' . $referees[$idArbitre]['prenom'];
						} else {
							echo 'Anonyme';
						}

						// If the referee didn't refereed the whole match, we show the number of period she refereed.
						echo $nbTiers != $noPeriod ? ' ('.$nbTiers.' tiers)' : '';

						end($matchReferees); //as the foreach works on a copy of the array, this won't change the foreach behavior.
					    echo $idArbitre !== key($matchReferees) ? ', ' : '';
					}
				} else {
					echo 'Aucun arbitre enregistré pour ce match.';
				}

				?>
			</p>
			<?php
			if ($attendance) {
				?>
				<p class="attendance sideIcon">
					<?php
					echo $nbSpectateurs . ' ' . VAR_LANG_SPECTATEURS;
					?>
				</p>
				<?php
			}
			?>
			<?php
			if ($flickrSetId) {
				?>
				<div class="photos">
					<?php
					$f = new phpFlickr(FLICKR_API_KEY);
					$photos = $f->photosets_getPhotos($flickrSetId);
					/*echo '<pre>';
					print_r($photos);
					echo '</pre>';*/
					?>
					<h2>Photos du match</h2>
					<p><a href="https://www.flickr.com/photos/swisstchoukball/sets/<?php echo $photos['photoset']['id']; ?>">Voir l'album sur Flickr</a></p>
					<div class="photosArray">
						<?php
						foreach($photos['photoset']['photo'] as $photo) {
							$title = utf8_decode($photo['title']);
							$imgSrcURL = 'https://farm' . $photo['farm'] . '.staticflickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'] . '_q.jpg';
							?>
							<a href="https://www.flickr.com/photos/swisstchoukball/<?php echo $photo['id']; ?>" title="<?php echo $title; ?>" target="_blank">
								<img src="<?php echo $imgSrcURL; ?>" width="150" height="150" alt="<?php echo $title; ?>">
							</a>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
			<div class="map">
				<iframe width="849" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo $gMapsURL; ?>"></iframe>
			</div>
		</div>
		<?php
	}

}
?>