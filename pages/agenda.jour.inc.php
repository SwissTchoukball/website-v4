<div id="navigationCalendrier">
    <?php
    if ($jour + 1 > $nombreJoursMois) {
        $jourSuivant = $jour + 1 - $nombreJoursMois;
        $jourPrecedant = $jour - 1;
        $moisSuivant = $mois + 1;
        $moisPrecedant = $mois;
        $anneeSuivante = $annee;
        $anneePrecedante = $annee;
    } elseif ($jour - 1 < 1) {
        $jourPrecedant = $jour - 1 + $nombreJoursMoisPrecedant;
        $jourSuivant = $jour + 1;
        $moisPrecedant = $mois - 1;
        $moisSuivant = $mois;
        $anneeSuivante = $annee;
        $anneePrecedante = $annee;
    } else {
        $jourSuivant = $jour + 1;
        $jourPrecedant = $jour - 1;
        $moisSuivant = $mois;
        $moisPrecedant = $mois;
        $anneeSuivante = $annee;
        $anneePrecedante = $annee;
    }


    if ($moisSuivant == 13) {
        $moisSuivant = 1;
        $anneeSuivante = $annee + 1;
    } elseif ($moisPrecedant == 0) {
        $moisPrecedant = 12;
        $anneePrecedante = $annee - 1;
    }
    ?>
    <span class="calendrierPrecedant"><a
            href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&affichage=calendrier&jour=<?php echo $jourPrecedant; ?>&mois=<?php echo $moisPrecedant; ?>&annee=<?php echo $anneePrecedante; ?>#navigationCalendrier"
            title="Jour précédant"><img src="pictures/calendrier.precedant.png" alt="Jour précédant"/></a></span>
    <span
        class="titreCalendrier"><?php echo ucfirst($jourDeLaSemaine[$jourSemaineJour]) . " " . $jour . " " . ucfirst($moisDeLAnnee[$mois]) . " " . $annee; ?></span>
    <span class="calendrierSuivant"><a
            href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&affichage=calendrier&jour=<?php echo $jourSuivant; ?>&mois=<?php echo $moisSuivant; ?>&annee=<?php echo $anneeSuivante; ?>#navigationCalendrier"
            title="Jour suivant"><img src="pictures/calendrier.suivant.png" alt="Jour suivant"/></a></span>
</div>
<div id="blocCalendrierJour">
    <table id="calendrierJour">
        <?php
        $requete = "SELECT Calendrier_Evenements.id AS idEvent, titre, description, lieu, jourEntier, couleur, nom, heureDebut, heureFin FROM Calendrier_Evenements, Calendrier_Categories WHERE Calendrier_Evenements.idCategorie=Calendrier_Categories.id AND dateDebut<='" . $annee . "-" . $mois . "-" . $jour . "' AND dateFin>='" . $annee . "-" . $mois . "-" . $jour . "' AND jourEntier=1 AND visible=1 ORDER BY heureDebut, titre";
        $retour = mysql_query($requete);
        while ($donnees = mysql_fetch_array($retour)) {
            echo "<tr>";
            echo "<td class='jourEntierJour' style='background-color: " . $donnees['couleur'] . ";'>";
            echo "<a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&idEvenement=" . $donnees['idEvent'] . "'><span style='color:white;'>" . $donnees['titre'] . "</span></a>";
            echo "</td>";
            echo "</tr>";
        }
        for ($k = 0; $k <= 23.5; $k = $k + 0.5) {
            echo "<tr>";
            if (strlen($k) > 2) { // On défini si c'est une demi-heure
                $minute = "30";
                $positionPoint = strpos($k, '.');
                $heure = substr($k, 0, $positionPoint);
                if (strlen($heure) == 1) {
                    $heure = "0" . $heure;
                }
            } elseif (strlen($k) == 1) { // On rajoute un zéro si l'heure ne compte qu'un chiffre.
                $heure = "0" . $k;
                $minute = "00";
            } elseif (strlen($k) == 2) {
                $heure = $k;
                $minute = "00";

            } else {
                $minute = "00";
                $heure = $k;
            }
            echo "<td>";
            echo "<div class='evenementJour'>";
            $requete = "SELECT Calendrier_Evenements.id AS idEvent, titre, description, lieu, jourEntier, couleur, nom, heureDebut, heureFin FROM Calendrier_Evenements, Calendrier_Categories WHERE Calendrier_Evenements.idCategorie=Calendrier_Categories.id AND dateDebut<='" . $annee . "-" . $mois . "-" . $jour . "' AND dateFin>='" . $annee . "-" . $mois . "-" . $jour . "' AND heureDebut<='" . $heure . ":" . $minute . ":00' AND heureFin>'" . $heure . ":" . $minute . ":00' AND jourEntier=0 AND visible=1 ORDER BY jourEntier DESC, heureDebut, titre";
            $retour = mysql_query($requete);
            while ($donnees = mysql_fetch_array($retour)) {
                echo "<span style='color: " . $donnees['couleur'] . "'>" . $donnees['titre'] . "</span><br />";
            }
            echo "</div>";
            if ($minute != 30) {
                echo "<div class='heureDuJour'>" . $heure . "h" . $minute . "</div>";
            } else {
                echo "<div class='heureDuJour'></div>";
            }
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
