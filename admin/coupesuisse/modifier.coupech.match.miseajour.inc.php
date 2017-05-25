<?php
//ce fichier est inclu dans modifier.coupech.match.etape4.inc.php c'est de là que vient le $_POST['annee']

echo "<br /><br />";

$annee = $_POST['annee'];
$categorie = $_POST['idCategorie'];

$requete = "SELECT * FROM CoupeCH_Categories_Par_Annee WHERE annee=" . $annee;
$retour = mysql_query($requete);
$donnees = mysql_fetch_array($retour);

$nbEquipes = $donnees['nbEquipes'];
$nbSetsGagnants = $donnees['nbSetsGagnants'];

$Tour = $nbEquipes / 2;
$prochainTour = $Tour / 2;

while ($Tour > 0) {
    $requeteTour = "SELECT * FROM CoupeCH_Matchs WHERE idTypeMatch=" . $Tour . " AND annee=" . $annee . " AND idCategorie=" . $categorie . " ORDER BY ordre";
    // echo $requeteTour;
    $retourTour = mysql_query($requeteTour);
    $c = 0;
    while ($DT = mysql_fetch_array($retourTour)) {
        $c++;

        $pasEuLieu = false;

        $setsGagnantsA = 0;
        $setsGagnantsB = 0;
        if ($DT['scoreA1'] > $DT['scoreB1']) {
            $setsGagnantsA++;
        } elseif ($DT['scoreA1'] < $DT['scoreB1']) {
            $setsGagnantsB++;
        } elseif ($DT['forfait'] == 3) {
            // Tout va bien, pas d'erreurs
        } elseif ($DT['scoreA1'] == 0 AND $DT['scoreB1'] == 0) {
            // Tout va bien, pas d'erreurs
            $pasEuLieu = true;
        } else {
            if ($nbSetsGagnants > 0) {
                exit("<br />ERREUR : set 1 à égalité");
            } elseif ($nbSetsGagnants = 0) {
                exit("<br />ERREUR : score à égalité");
            } else {
                exit("<br />ERREUR : score à égalité et nombre de sets gagnants négatif !");
            }
        }
        if ($nbSetsGagnants > 1) {
            if ($DT['scoreA2'] > $DT['scoreB2']) {
                $setsGagnantsA++;
            } elseif ($DT['scoreA2'] < $DT['scoreB2']) {
                $setsGagnantsB++;
            } elseif ($DT['scoreA2'] == 0 OR $DT['scoreB2'] == 0) {
                // Tout va bien, pas d'erreurs
            } else {
                exit("<br />ERREUR : set 2 à égalité");
            }

            if ($DT['scoreA3'] > $DT['scoreB3']) {
                $setsGagnantsA++;
            } elseif ($DT['scoreA3'] < $DT['scoreB3']) {
                $setsGagnantsB++;
            } elseif ($DT['scoreA3'] == 0 OR $DT['scoreB3'] == 0) {
                // Tout va bien, pas d'erreurs
            } else {
                exit("<br />ERREUR : set 3 à égalité");
            }

            if ($DT['scoreA4'] > $DT['scoreB4']) {
                $setsGagnantsA++;
            } elseif ($DT['scoreA4'] < $DT['scoreB4']) {
                $setsGagnantsB++;
            } elseif ($DT['scoreA4'] == 0 OR $DT['scoreB4'] == 0) {
                // Tout va bien, pas d'erreurs
            } else {
                exit("<br />ERREUR : set 4 à égalité");
            }

            if ($DT['scoreA5'] > $DT['scoreB5']) {
                $setsGagnantsA++;
            } elseif ($DT['scoreA5'] < $DT['scoreB5']) {
                $setsGagnantsB++;
            } elseif ($DT['scoreA5'] == 0 OR $DT['scoreB5'] == 0) {
                // Tout va bien, pas d'erreurs
            } else {
                exit("<br />ERREUR : set 5 à égalité");
            }
        }
        if ($nbSetsGagnants > 0) {
            if ($setsGagnantsA > $setsGagnantsB AND $setsGagnantsA == $nbSetsGagnants) {
                $vainqueur = $DT['equipeA'];
                $perdant = $DT['equipeB'];
            } elseif ($setsGagnantsA < $setsGagnantsB AND $setsGagnantsB == $nbSetsGagnants) {
                $vainqueur = $DT['equipeB'];
                $perdant = $DT['equipeA'];
            } elseif ($DT['forfait'] == 3) {
                $vainqueur = $DT['equipeA'];
                $perdant = $DT['equipeB'];
            } else {
                exit("<br />ERREUR : les deux équipes ont gagnés autant de sets ou bien pas assez de sets par rapport au nombre de sets gagnants");
            }
        } elseif ($nbSetsGagnants = 0) {
            if ($setsGagnantsA > $setsGagnantsB AND $setsGagnantsA = 1) {
                $vainqueur = $DT['equipeA'];
                $perdant = $DT['equipeB'];
            } elseif ($setsGagnantsA < $setsGagnantsB AND $setsGagnantsB = 1) {
                $vainqueur = $DT['equipeB'];
                $perdant = $DT['equipeA'];
            } elseif ($DT['forfait'] == 3) {
                $vainqueur = $DT['equipeA'];
                $perdant = $DT['equipeB'];
            } else {
                exit("<br />ERREUR : les deux équipes ont l'air d'avoir fait égalité");
            }
        } else {
            exit("<br />ERREUR : nombre de sets gagnants négatif !");
        }

        if ($c == 1) {
            $m1 = $DT['ordre'];
            $v1 = $vainqueur;
            $p1 = $perdant;
        } elseif ($c == 2) {
            $m2 = $DT['ordre'];
            $v2 = $vainqueur;
            $p2 = $perdant;
        } else { // Ne devrait pas arriver
            exit("<br />ERREUR compteur");
        }

        if ($c % 2 == 0) {
            if ($m2 - $m1 != 1) {
                exit("<br />ERREUR : ordre mal défini.");
            } else {
                $ordreMatchProchainTour = ($m1 + 1) / 2;
                $requeteMaJ = "UPDATE CoupeCH_Matchs SET equipeA=" . $v1 . ", equipeB=" . $v2 . " WHERE idTypeMatch=" . $prochainTour . " AND ordre=" . $ordreMatchProchainTour . " AND annee=" . $annee . " AND categorie=" . $categorie;
                //echo $requeteMaJ."<br />";

                if ($Tour == 2) { // Petite finale
                    $requeteMaJPF = "UPDATE CoupeCH_Matchs SET equipeA=" . $p1 . ", equipeB=" . $p2 . " WHERE idTypeMatch=-1 AND ordre=" . $ordreMatchProchainTour . " AND annee=" . $annee . " AND categorie=" . $categorie;
                    //echo $requeteMaJPF."<br />";
                    mysql_query($requeteMaJPF);
                }

                mysql_query($requeteMaJ);

                if (!$pasEuLieu) {
                    $requeteEquipes = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $v1 . " OR idEquipe=" . $v2;
                    $retourEquipes = mysql_query($requeteEquipes);
                    $l = 0;
                    while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
                        $l++;
                        if ($l == 2) {
                            echo "et ";
                        }
                        echo $donneesEquipes['nomEquipe'] . " ";
                    }
                    echo "jouent ensemble en ";

                    $requeteTypeMatch = "SELECT nom" . $_SESSION['__langue__'] . " FROM CoupeCH_Type_Matchs WHERE idTypeMatch=" . $prochainTour;
                    $retourTypeMatch = mysql_query($requeteTypeMatch);
                    $donneesTypeMatch = mysql_fetch_array($retourTypeMatch);

                    echo $donneesTypeMatch['nom' . $_SESSION['__langue__']] . ".<br />";
                }

                if ($Tour == 2) { // Petite finale
                    if (!$pasEuLieu) {
                        $requeteEquipes = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $p1 . " OR idEquipe=" . $p2;
                        $retourEquipes = mysql_query($requeteEquipes);
                        $l = 0;
                        while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
                            $l++;
                            if ($l == 2) {
                                echo "et ";
                            }
                            echo $donneesEquipes['nomEquipe'] . " ";
                        }
                        echo "jouent ensemble en ";

                        $requeteTypeMatch = "SELECT nom" . $_SESSION['__langue__'] . " FROM CoupeCH_Type_Matchs WHERE idTypeMatch=-1";
                        $retourTypeMatch = mysql_query($requeteTypeMatch);
                        $donneesTypeMatch = mysql_fetch_array($retourTypeMatch);

                        echo $donneesTypeMatch['nom' . $_SESSION['__langue__']] . ".<br />";
                    }
                }
            }
            $c = 0;
        }
    } // fin boucle matchs
    if ($Tour == 1) {
        $Tour = 0;
    } else {
        $Tour = $Tour / 2;
        $prochainTour = $Tour / 2;
    }
} // fin boucle tant que tour > 0
