<?php ?>
<h3>
    <?php echo VAR_LANG_ETAPE_3; ?>
</h3>
<?php
//Donn�es � entrer dans la BDD
if (isset($_POST['annee']) AND isset($_POST['categorie']) AND isset($_POST['nbEquipes']) AND isset($_POST['nbSetsGagnants']) AND isset($_POST['scoreGagnantParForfait'])) {
    $annee = $_POST['annee'];
    $idCategorie = $_POST['categorie'];
    $nbEquipes = $_POST['nbEquipes'];
    $nbSetsGagnants = $_POST['nbSetsGagnants'];
    $scoreGagnantParForfait = $_POST['scoreGagnantParForfait'];


    /* Check �quipes existent */
    $erreurA = 0;
    $okA = 0;
    for ($k = 1; $k <= $nbEquipes; $k++) {
        if (!isset($_POST['equipe' . $k])) {
            $erreurA++;
        } else {
            $okA++;
        }
    } // fin boucle check �quipes

    if ($erreurA == 0 AND $okA == $nbEquipes) { // Toutes les POST equipe existent

        /* Calcul nombre d'�quipes vides*/
        $equipesVides = 0;
        for ($k = 1; $k <= $nbEquipes; $k++) {
            if ($_POST['equipe' . $k] == 0) {
                $equipesVides++;
            }
        }
        $equipesPleines = $nbEquipes - $equipesVides;

        /* Check autoqualif */
        $erreurB = 0;
        $okB = 0;
        for ($k = 1; $k <= $nbEquipes; $k++) {
            if ($k % 2 != 0) { // l'�quipe B ou "�quipe du bas" ne peut pas �tre autoqualifi�e. C'est l'�quipe portant un nombre impair dans la liste de mise en place du classement de d�part.
                if ($_POST['equipe' . $k] == 0) {
                    $erreurB++;
                } else {
                    $okB++;
                }
            }
        } // fin boucle check autoqualif

        if ($erreurB == 0 AND $okB == $nbEquipes / 2) { // Il n'y a pas de mauvaise* �quipe autoqualifi�e (*l'�quipe B ou "�quipe du bas")

            /* Check doublons */
            $erreurC = 0;
            $okC = 0;
            for ($k = 1; $k <= $nbEquipes; $k++) {
                if ($_POST['equipe' . $k] != 0) {
                    for ($j = 1; $j <= $nbEquipes; $j++) {
                        if ($_POST['equipe' . $j] != 0) {
                            if ($j != $k) {
                                if ($_POST['equipe' . $j] == $_POST['equipe' . $k]) {
                                    $erreurC++;
                                } else {
                                    $okC++;
                                }
                            }
                        }
                    }
                }
            } // fin boucle check doublons

            if ($erreurC == 0 AND $okC == $equipesPleines * ($equipesPleines - 1)) { // Pas de doublons (okC=nbEquipes*(nbEquipes-1) car on v�rifie toutes les combinaisons.)

                /* Si il n'y a aucune erreur, voir ci-DESSOUS*/

                echo "D�but insertion<br />";

                // On ajoute l'entr�e dans la table CoupeCH_Categories_Par_Annee
                $requete = "INSERT INTO CoupeCH_Categories_Par_Annee ('idCategorie', 'annee', 'nbSetsGagnants', 'scoreGagnantParForfait', 'nbEquipes')
										VALUES (" . $idCategorie . ", " . $annee . ", " . $nbSetsGagnants . ", " . $scoreGagnantParForfait . ", " . $nbEquipes . ")";

                // On ajoute l('/es )entr�e(s) dans la table CoupeCH_Journees


                // On ajoute les entr�es dans la table CoupeCH_Matchs

                // echo "Fin insertion<br />";

                /* Si il n'y a aucune erreur, voir ci-DESSUS*/

            } else { // Il y a un doublon
                $nbEquipesDoublon = $equipesPleines * ($equipesPleines - 1);
                echo "Erreurs doublon : " . $erreurC . "<br />";
                echo "Equipes ok : " . $okC . "<br />";
                if ($okC + $erreurC != $nbEquipesDoublon) { // probl�me
                    echo "<strong>" . $erreurC . "+" . $okC . "!=" . $nbEquipesDoublon . "</strong><br />";
                } else { // c'est juste
                    echo $erreurC . "+" . $okC . "=" . $nbEquipesDoublon . "<br />";
                }
            }
        } else { // Il y a une mauvaise * �quipe autoqualifi�e (*l'�quipe B ou "�quipe du bas")
            $nbEquipesAutoqualif = $nbEquipes / 2;
            echo "Erreurs autoqualification : " . $erreurB . "<br />";
            echo "Equipes ok : " . $okB . "<br />";
            if ($okB + $erreurB != $nbEquipesAutoqualif) { // probl�me
                echo "<strong>" . $erreurB . "+" . $okB . "!=" . $nbEquipesAutoqualif . "</strong><br />";
            } else { // c'est juste
                echo $erreurB . "+" . $okB . "=" . $nbEquipesAutoqualif . "<br />";
            }
        }
    } else { // Certains POST equipe n'existent pas
        echo "Erreurs donn�es �quipe : " . $erreurA . "<br />";
        echo "Equipes ok : " . $okA . "<br />";
        if ($okA + $erreurA != $nbEquipes) { // probl�me
            echo "<strong>" . $erreurA . "+" . $okA . "!=" . $nbEquipes . "</strong><br />";
        } else { // c'est juste
            echo $erreurA . "+" . $okA . "=" . $nbEquipes . "<br />";
        }
    }

} // fin if donn�es existent
else {
    exit("<br />ERREUR : donn�es manquantes");
}
?>
