<?php

function triParPointsInterne($informations, $tableau, $debug)
{ // fonctionne !
    $nouveauTableau = array();
    $nbGroupeEgalite = 0;
    $annee = $informations['annee'];
    $idCategorie = $informations['idCategorie'];
    $idTour = $informations['idTour'];
    $noGroupe = $informations['noGroupe'];
    for ($k = 1; $k <= $tableau[0]; $k++) {
        if (count($tableau[$k]) > 1) {
            if ($debug) {
                echo "<br /><strong>Il y a une égalité de points.</strong><br />";
            }

            $pointsInterne = array();
            $ordningEquipesEgalitesPoints = array();
            $ordningEquipesEgalitesId = array();
            $l = 0;

            for ($i = 1; $i <= count($tableau[$k]); $i++) { // Une boucle par équipe à égalité ==>> $i = EQUIPE EGALITE
                $pointsInterne[$i] = 0; // Initialisation des points interne de l'équipe.
                for ($j = 1; $j <= count($tableau[$k]); $j++) { // Une boucle pour chaque rencontre possible que cette équipe a fait avec une autre équipe à égalité. Le système calcule uniquement les points de l'équipe sélectionnée ==>> $j = AUTRE EQUIPE EGALITE avec qui on compte les points
                    if ($i != $j) { // Condition pour qu'on ne cherche pas de match ou équipe joue avec elle-même
                        $requete = "SELECT equipeA, equipeB, pointsA, pointsB FROM Championnat_Matchs WHERE saison=" . $annee . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $noGroupe . " AND ((equipeA=" . $tableau[$k][$i] . " AND equipeB=" . $tableau[$k][$j] . ") OR (equipeA=" . $tableau[$k][$j] . " AND equipeB=" . $tableau[$k][$i] . ")) AND (pointsA!=0 AND pointsB!=0)";
                        if ($debug) {
                            echo "<br /><br />Tri par points internes : " . $requete;
                        }
                        $retour = mysql_query($requete);
                        if (mysql_num_rows($retour) == 0) {// requête vide, le nouveauTableau ne bouge pas. SI TU CHERCHES UN BUG C'EST SUREMENT ICI BOULET !!!!
                            // Cela arrive s'il y a :
                            // - une équipe qui n'a pas joué de match dans le tour. Le classement peut être faux.
                            // - une égalité de point entre deux équipes qui n'ont jamais joué l'une avec l'autre.
                            $pasChangerClassement = true;
                            if ($debug) {
                                echo "<p style='color:red;'>Une erreur dans classement ? C'est normal, ce script est mal foutu ! Vient voir le code pour plus d'infos.</p>";
                            }
                            break 3;
                        }
                        while ($donnees = mysql_fetch_array($retour)) { // Boucle pour chaque match où les équipes ont joués ensemble dans le tour.
                            if ($donnees['equipeA'] == $tableau[$k][$i]) { // Si l'équipe sélectionnée est equipeA
                                if ($forfait == 1) { // Si il y a forfait
                                    if ($donnees['pointsA'] > $donnees['pointsB']) { // Si l'équipe sélectionnée a + de points
                                        $pointsInterne[$i] = $pointsInterne[$i] + 3; // Gagne par forfait
                                    } else { // Si l'équipe sélectionnée a - de points
                                        $pointsInterne[$i] = $pointsInterne[$i] + 0; // Déclare forfait
                                    }
                                } elseif ($donnees['pointsA'] > $donnees['pointsB']) { // Si l'équipe sélectionnée gagne
                                    $pointsInterne[$i] = $pointsInterne[$i] + 3;
                                } elseif ($donnees['pointsA'] < $donnees['pointsB']) { // Si l'équipe sélectionnée perd
                                    $pointsInterne[$i] = $pointsInterne[$i] + 1;
                                } else { // Egalité
                                    $pointsInterne[$i] = $pointsInterne[$i] + 2;
                                }
                            } elseif ($donnees['equipeB'] == $tableau[$k][$i]) { // Si l'équipe sélectionnée est equipeB
                                if ($forfait == 1) { // Si il y a forfait
                                    if ($donnees['pointsA'] > $donnees['pointsB']) { // Si l'équipe sélectionnée a - de points
                                        $pointsInterne[$i] = $pointsInterne[$i] + 0; // Déclare forfait
                                    } else { // Si l'équipe sélectionnée a + de points
                                        $pointsInterne[$i] = $pointsInterne[$i] + 3; // Gagne par forfait
                                    }
                                }
                                if ($donnees['pointsA'] > $donnees['pointsB']) { // Si l'équipe sélectionnée perd
                                    $pointsInterne[$i] = $pointsInterne[$i] + 1;
                                } elseif ($donnees['pointsA'] < $donnees['pointsB']) { // Si l'équipe sélectionnée gagne
                                    $pointsInterne[$i] = $pointsInterne[$i] + 3;
                                } else { // Egalité
                                    $pointsInterne[$i] = $pointsInterne[$i] + 2;
                                }
                            } else {
                                echo "<br />ERREUR A2<br />";
                            }
                        } // Fin boucle chaque match de deux équipes spécifiques
                    } // Fin condition équipe avec elle-même
                } // Fin boucle pour chaque rencontre possible
                if ($debug) {
                    echo "<br /><br />" . $tableau[$k][$i] . " : Points Interne :" . $pointsInterne[$i];
                }

                $ordningEquipesEgalitesPoints[$i] = $pointsInterne[$i];
                $ordningEquipesEgalitesId[$i] = $tableau[$k][$i];
                $pointsInterne = array();

            } // Fin boucle par équipe égalité

            for ($m = max($ordningEquipesEgalitesPoints); $m >= min($ordningEquipesEgalitesPoints); $m--) {
                $compteur = 0;
                for ($n = 1; $n <= count($tableau[$k]); $n++) {
                    if ($ordningEquipesEgalitesPoints[$n] == $m) {
                        if ($compteur == 0) {
                            $nbGroupeEgalite++; // Nouveau groupe a égalité
                        }
                        $compteur++;
                        $nouveauTableau[$nbGroupeEgalite][$compteur] = $ordningEquipesEgalitesId[$n];
                    }
                }
            }
            $pointsInterneAvant = null;
        } elseif (count($tableau[$k]) == 1) {
            $nbGroupeEgalite++; // Nouveau groupe a égalité
            $nouveauTableau[$nbGroupeEgalite][1] = $tableau[$k][1];
        } else {
            echo "<br /><strong>ERREUR A1</strong><br />";
        }
    }
    $nouveauTableau[0] = $nbGroupeEgalite;
    if ($pasChangerClassement) {
        $nouveauTableau = $tableau;
    }
    return $nouveauTableau;

}

?>
