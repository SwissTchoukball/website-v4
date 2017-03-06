<?php
if (isset($_GET['venueID']) && isValidMatchID($_GET['venueID'])) {
    //echo '<p><a href="?menuselection='.$menuselection.'&smenuselection='.$smenuselection.'">Retour au programme</a></p>';
    // ------------------------------------------ //
    // ! Affichage d'un match
    // ------------------------------------------ //
    $venueID = $_GET['venueID'];
    //TODO: se faire la réflexion s'il ne serait pas mieux de faire une requête séparée pour les arbitres...
    $venueQuery = "SELECT l.nom, l.adresse, l.npa, l.ville, c.sigle AS sigleCanton, l.idPays, p.descriptionPays" . $_SESSION['__langue__'] . " AS pays, l.latitude, l.longitude, l.url
				   FROM Lieux l
				   LEFT OUTER JOIN Canton c ON c.id = l.idCanton
				   LEFT OUTER JOIN DBDPays p ON p.idPays = l.idPays
				   WHERE l.id = " . $venueID . "
				   LIMIT 1";
    if (!$venueData = mysql_query($venueQuery)) {
        echo '<p class="notification notification--error">Erreur lors de la récupération des données du lieu.<br />Requête: ' . $venueQuery . '<br />Message: ' . mysql_error() . '</p>';
    } else {
        $venue = mysql_fetch_assoc($venueData);
        $gMapsURL = 'https://maps.google.com/maps?q=' . urlencode($venue['adresse'] . ', ' . $venue['npa'] . ' ' . $venue['ville']) . '&amp;num=1&amp;t=m&amp;ie=UTF8&amp;output=embed';
        ?>
        <div class="venue">
            <h1><?php echo $venue['nom'] ?></h1>
            <p class="address">
                <?php
                echo $venue['adresse'] . '<br />';
                echo $venue['npa'] . ' ' . $venue['ville'] . ' (' . $venue['sigleCanton'] . ')';
                echo $venue['idPays'] != 42 ? '<br />' . $venue['pays'] : '';
                ?>
            </p>
            <?php
            if (!is_null($venue['url'])) {
                echo '<p class="url"><a href="' . $venue['url'] . '" target="_blank">Site web</a></p>';
            }
            if (!is_null($venue['adresse']) && !is_null($venue['npa']) && !is_null($venue['ville'])) {
                ?>
                <div class="map fullWidth">
                    <iframe width="849" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                            src="<?php echo $gMapsURL; ?>"></iframe>
                </div>
                <?php
            }
            ?>
            <div class="matches">
                <h2><?php echo VAR_LANG_PROCHAINS_MATCHS . ' (' . VAR_LANG_CHAMPIONNAT . ')'; ?></h2>
                <?php
                $matchesQuery = "SELECT m.idMatch, ea.equipe AS equipeA, eb.equipe AS equipeB, m.dateDebut, m.heureDebut
								 FROM Championnat_Matchs m
								 LEFT OUTER JOIN Championnat_Equipes ea ON m.equipeA = ea.idEquipe
								 LEFT OUTER JOIN Championnat_Equipes eb ON m.equipeB = eb.idEquipe
								 WHERE dateFin>='" . date('Y-m-d') . "'
								 AND idLieu=" . $venueID . "
								 ORDER BY m.dateDebut, m.heureDebut";
                if (!$matchesData = mysql_query($matchesQuery)) {
                    printErrorMessage('Erreur lors de la récupération des matchs.<br />Message : ' . mysql_error() . '<br />Requête : ' . $matchesQuery);
                } else {
                    if (mysql_num_rows($matchesData) <= 0) {
                        printMessage('Aucun match prévu');
                    } else {
                        echo '<ul>';
                        while ($match = mysql_fetch_assoc($matchesData)) {
                            echo '<li><a href="/championnat/match/' . $match['idMatch'] . '">' . $match['equipeA'] . ' - ' . $match['equipeB'] . '</a>, ' . date_sql2date_joli($match['dateDebut'],
                                    'le',
                                    $_SESSION['__langue__']) . ' à ' . time_sql2heure($match['heureDebut']) . '</li>';
                        }
                        echo '</ul>';
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

}
?>