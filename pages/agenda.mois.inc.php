<div id="navigationCalendrier">
    <?php
    if ($mois == 12) {
        $moisSuivant = 1;
        $anneeSuivante = $annee + 1;
        $moisPrecedant = $mois - 1;
        $anneePrecedante = $annee;
    } elseif ($mois == 1) {
        $moisPrecedant = 12;
        $anneePrecedante = $annee - 1;
        $moisSuivant = $mois + 1;
        $anneeSuivante = $annee;
    } else {
        $moisSuivant = $mois + 1;
        $moisPrecedant = $mois - 1;
        $anneeSuivante = $annee;
        $anneePrecedante = $annee;
    }
    ?>

    <script language="javascript">
        function defineActionForm() {
            var selectionMois = document.getElementById("selectionMoisCalendrier");
            var formSelectionMoisCalendrier = document.getElementById("formSelectionMoisCalendrier");
            formSelectionMoisCalendrier.action = selectionMois.value;
        }
    </script>
    <a class="calendrierPrecedant"
       href="/calendrier/<?php echo $anneePrecedante; ?>/<?php echo $moisPrecedant; ?>#navigationCalendrier"
       title="Mois précédant"></a>
    <form id="formSelectionMoisCalendrier" name="formSelectionMoisCalendrier" action="" method="post"
          class="selectionMoisCalendrier">
        <select name="selectionMoisCalendrier" id="selectionMoisCalendrier" class="titreCalendrier"
                title="Mois"
                onChange="defineActionForm();formSelectionMoisCalendrier.submit();">
            <?php
            for ($k = -12; $k <= 12; $k++) {
                $moisSelection = $mois + $k;
                if ($moisSelection > 12) {
                    $moisSelection = $moisSelection - 12;
                    $anneeSelection = $annee + 1;
                } elseif ($moisSelection < 1) {
                    $moisSelection = $moisSelection + 12;
                    $anneeSelection = $annee - 1;
                } else {
                    $anneeSelection = $annee;
                }
                if ($k == 0) {
                    $selected = "selected='selected'";
                } else {
                    $selected = "";
                }
                echo "<option " . $selected . " value='/calendrier/" . $anneeSelection . "/" . $moisSelection . "'>" . ucfirst($moisDeLAnnee[$moisSelection]) . " " . $anneeSelection . "</option>";
            }
            ?>
        </select>
    </form>
    <a class="calendrierSuivant"
       href="/calendrier/<?php echo $anneeSuivante; ?>/<?php echo $moisSuivant; ?>#navigationCalendrier"
       title="Mois suivant"></a>
</div><br/>
<table id="calendrierMois">
    <tr>
        <?php
        for ($j = 1; $j <= 7; $j++) {
            echo '<th>' . ucfirst($jourDeLaSemaine[$j]) . '</th>';
        }
        ?>
    </tr>
    <tr>
        <?php
        for ($casesVidesDebut = 1; $casesVidesDebut <= $nombreCasesVidesPremiereSemaine; $casesVidesDebut++) {
            echo "<td class='caseVide'></td>";
        }
        for ($jourDuMois = 1; $jourDuMois <= $nombreJoursMois; $jourDuMois++) {
            $timestampJour = mktime(0, 0, 0, $mois, $jourDuMois, $annee); // timestamp du $jour $mois $annee à minuit

            $dayOfWeek = date('w', $timestampJour); // 0 (pour dimanche) à 6 (pour samedi)
            if ($dayOfWeek == 0) { //On transforme en 1 (pour Lundi) à 7 (pour Dimanche)
                $dayOfWeek = 7;
            }
            if ($dayOfWeek == 1) {
                echo "<tr>";
            }

            $classDuJour = '';
            if ($jourDuMois == date('j') AND $mois == date('n') AND $annee == date('Y')) {
                $classDuJour .= " calendrierAujourdhui";
            }
            if ($dayOfWeek == 1) {
                $classDuJour .= ' firstDayOfWeek';
            } elseif ($dayOfWeek == 7) {
                $classDuJour .= ' lastDayOfWeek';
            }

            echo "<td class='" . $classDuJour . "'>";
            echo "<div class='jourDuMois'>";
            if ($affichageParJour) {
                //URL non supportée
                echo "<a href='?" . $navigation->getCurrentPageLinkQueryString() . "&affichage=calendrier&jour=" . $jourDuMois . "&mois=" . $mois . "&annee=" . $annee . "'>";
            }
            echo $jourDuMois;
            if ($affichageParJour) {
                echo "</a>";
            }
            echo "</div>";
            $premier = true;
            $triageCategorie = " AND (";
            for ($k = 1; $k <= $categorieCochee['max']; $k++) {
                if ($categorieCochee[$k]) {
                    if (!$premier) {
                        $triageCategorie .= " OR ";
                    }
                    $triageCategorie .= "idCategorie=" . $k;
                    $premier = false;
                }

            }
            $triageCategorie .= ")";

            $requete = "SELECT e.id AS idEvent, e.description, e.titre, e.lieu, e.jourEntier, c.couleur, c.nom AS nomCategorie
					  FROM Calendrier_Evenements e, Calendrier_Categories c
					  WHERE e.idCategorie=c.id" . $triageCategorie . "
					  AND e.dateDebut<='" . $annee . "-" . $mois . "-" . $jourDuMois . "'
					  AND e.dateFin>='" . $annee . "-" . $mois . "-" . $jourDuMois . "'
					  AND e.visible=1
					  ORDER BY e.jourEntier DESC, e.heureDebut, e.titre";
            $retour = mysql_query($requete);
            while ($donnees = mysql_fetch_array($retour)) {
                if ($donnees['jourEntier'] == 1) {
                    echo "<div class='calendrierMoisEvenement calendarEvent' style='background-color: " . $donnees['couleur'] . "; text-align:center;'>";
                    echo "<a href='/evenement/" . $donnees['idEvent'] . "' style='color:white'>" . $donnees['titre'] . '</a>';
                } else {
                    echo "<div class='calendrierMoisEvenement calendarEvent'>";
                    echo "<a href='/evenement/" . $donnees['idEvent'] . "' style='color: " . $donnees['couleur'] . "'>&#149; " . $donnees['titre'] . "</a>";
                }
                echo "<span class='infobulle' style='border-color:  " . $donnees['couleur'] . "'>";
                echo "<a href='/evenement/" . $donnees['idEvent'] . "'>" . $donnees['titre'] . '</a><br />';
                echo "<span style='color:  " . $donnees['couleur'] . "'>" . $donnees['nomCategorie'] . "</span><br />";
                if ($donnees['lieu'] != "") {
                    echo "Lieu : " . $donnees['lieu'] . "<br />";
                }
                echo nl2br($donnees['description']) . "</span>";
                echo "</div>";
            }


            if (isset($categorieCochee[4])) { // Championnat
                // On affiche pas les matchs juniors car cela prend trop de place dans l'agenda vu qu'ils sont tous le même jour.
                $requeteChampionnat = "SELECT m.idMatch, m.equipeA, m.equipeB, c.couleur
									   FROM Championnat_Matchs m, Calendrier_Categories c
									   WHERE c.id=4
									   AND m.idCategorie != 4 -- M15 régional
									   AND m.idCategorie != 5 -- M12 régional
									   AND m.idCategorie != 7 -- M10 régional
									   AND m.idCategorie != 8 -- M15 régional monopolaire
									   AND m.dateDebut<='" . $annee . "-" . $mois . "-" . $jourDuMois . "'
									   AND m.dateFin>='" . $annee . "-" . $mois . "-" . $jourDuMois . "'
									   ORDER BY m.heureDebut";
                $retourChampionnat = mysql_query($requeteChampionnat);
                while ($donneesChampionnat = mysql_fetch_array($retourChampionnat)) {
                    echo "<div class='calendrierMoisEvenement'>";
                    echo "<a href='/championnat/match/" . $donneesChampionnat['idMatch'] . "' style='color: " . $donneesChampionnat['couleur'] . "' class='calendarEvent'>";
                    echo "&#149; " . $tableauEquipes[$donneesChampionnat['equipeA']] . " - " . $tableauEquipes[$donneesChampionnat['equipeB']];
                    echo "</a></div>";
                }
            }

            echo "</td>";
            if ($dayOfWeek == 7) {
                echo "</tr>";
            }
        }
        for ($casesVidesFin = 1; $casesVidesFin <= $nombreCasesVidesDerniereSemaine; $casesVidesFin++) {
            echo "<td class='caseVide'></td>";
        }
        ?>
    </tr>
</table>
