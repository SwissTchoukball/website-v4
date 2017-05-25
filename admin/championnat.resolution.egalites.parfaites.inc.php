<div class="insererMatch">
    <?php
    statInsererPageSurf(__FILE__);

    if (isset($_POST['type'])) {
        if ($_POST['type'] == 'manuel') {
            $requeteModifierOrdre = array();
            for ($j = 1; $j <= $_POST['idMax']; $j++) {
                $requeteValide = true;
                if (isset($_POST['positionEquipe' . $j])) {
                    $positionEquipe = $_POST['positionEquipe' . $j];
                    if ($positionEquipe > 1 AND $positionEquipe < 10) {
                        $requeteModifierOrdre[$j] = "UPDATE Championnat_Equipes_Tours SET egaliteParfaite='" . $_POST['positionEquipe' . $j] . "' WHERE points='" . $_POST['points'] . "' AND egaliteParfaite='1' AND idEquipe=" . $j;
                    } else {
                        echo "<h4>" . $positionEquipe . " n'est pas un chiffre plus grand que un !</h4>";
                        $requeteValide = false;
                    }
                }
            }
            for ($j = 1; $j <= $_POST['idMax']; $j++) {
                if (isset($_POST['positionEquipe' . $j])) {
                    if ($requeteValide) {
                        //echo "Equipe \"".$_POST['nomEquipe'.$j]."\" : ".$_POST['positionEquipe'.$j]."<br />";
                        //echo $requeteModifierOrdre[$j]."<br />";
                        mysql_query($requeteModifierOrdre[$j]);
                    }
                }
            }
        } elseif ($_POST['type'] == 'auto') {
            $requeteModifierOrdre = array();
            for ($j = 1; $j <= $_POST['idMax']; $j++) {
                $requeteValide = true;
                if (isset($_POST['nomEquipe' . $j])) {
                    $requeteModifierOrdre[$j] = "UPDATE Championnat_Equipes_Tours SET egaliteParfaite='0' WHERE points='" . $_POST['points'] . "' AND egaliteParfaite='" . $_POST['egaliteParfaite' . $j] . "' AND idEquipe=" . $j;
                }
            }
            for ($j = 1; $j <= $_POST['idMax']; $j++) {
                if (isset($_POST['nomEquipe' . $j])) {
                    //echo "Equipe \"".$_POST['nomEquipe'.$j]."\" : ".$_POST['egaliteParfaite'.$j]."<br />";
                    //echo $requeteModifierOrdre[$j]."<br />";
                    mysql_query($requeteModifierOrdre[$j]);
                }
            }
        } else {
            echo "<br />ERREUR F1<br />";
        }
    }


    $requete = "SELECT Championnat_Equipes.idEquipe, equipe, saison, categorie" . $_SESSION['__langue__'] . " AS categorie, tour" . $_SESSION['__langue__'] . " AS tour, noGroupe, points FROM Championnat_Equipes_Tours, Championnat_Equipes, Championnat_Categories, Championnat_Types_Tours WHERE egaliteParfaite=1 AND Championnat_Equipes_Tours.idEquipe=Championnat_Equipes.idEquipe AND Championnat_Categories.idCategorie=Championnat_Equipes_Tours.idCategorie AND Championnat_Types_Tours.idTour=Championnat_Equipes_Tours.idTour ORDER BY points, equipe";
    $retour = mysql_query($requete);
    if (mysql_num_rows($retour) > 0) {
        $pointsAvant = -1;
        echo "<h3>" . VAR_LANG_EGALITE_ORDRE_ALPHABETIQUE . "</h3>";
        echo "<h5>" . VAR_LANG_PAS_TOUCHER_POUR_RESOUDRE_EGALITE . "</h5>";
        ?>
        <?php
        $premier = true;
        while ($donnees = mysql_fetch_array($retour)) {
            if ($pointsAvant != $donnees['points']) { // Pour regrouper les équipes à égalités.
                $saisonFin = $donnees['saison'] + 1;
                if (!$premier) { // Fin du formulaire précédent.
                    echo "<input type='hidden' name='compteur' value='" . $c . "' />";
                    echo "<input type='hidden' name='idMax' value='" . $idMax . "' />";
                    echo "<input type='hidden' name='points' value='" . $pointsAvant . "' />";
                    echo "<input type='hidden' name='type' value='manuel' />";
                    echo "<input type='submit' value=\"Afficher les équipes dans l'ordre croissant en fonction des chiffres insérés dans les champs\" />";
                    echo "</fieldset>";
                    echo "</form><br />";
                    $c = 0;
                    $idMax = 0;
                }
                echo "<form name='classerEgalites" . $donnees['points'] . "' action='' method='post'>";
                echo "<fieldset>";
                echo "<legend>" . VAR_LANG_SAISON . " " . $donnees['saison'] . " - " . $saisonFin . " / " . $donnees['categorie'] . " / " . $donnees['tour'] . " / " . VAR_LANG_GROUPE . " " . $donnees['noGroupe'] . " / " . VAR_LANG_POINTS . " : " . $donnees['points'] . "</legend>";
            }
            echo "<label for='positionEquipe" . $donnees['idEquipe'] . "'>" . $donnees['equipe'] . " : </label><input type='text' id='positionEquipe" . $donnees['idEquipe'] . "' name='positionEquipe" . $donnees['idEquipe'] . "' size='1' />";
            echo "<input type='hidden' name='nomEquipe" . $donnees['idEquipe'] . "' value='" . $donnees['equipe'] . "' /><br /><br />";
            $pointsAvant = $donnees['points'];
            if ($donnees['idEquipe'] > $idMax) {
                $idMax = $donnees['idEquipe'];
            }
            $premier = false;
            $c++;
        }
        // Fin du dernier formulaire.
        echo "<input type='hidden' name='compteur' value='" . $c . "' />";
        echo "<input type='hidden' name='idMax' value='" . $idMax . "' />";
        echo "<input type='hidden' name='points' value='" . $pointsAvant . "' />";
        echo "<input type='hidden' name='type' value='manuel' />";
        echo "<input type='submit' value=\"Afficher les équipes dans l'ordre croissant en fonction des chiffres insérés dans les champs\" />";
        echo "</fieldset>";
        echo "</form>";
        ?>
        <?php
    } else {
        echo "<h3>" . VAR_LANG_PAS_EGALITES_PARFAITES . "</h3>";

    }

    $requete = "SELECT Championnat_Equipes.idEquipe, equipe, saison, categorie" . $_SESSION['__langue__'] . " AS categorie, tour" . $_SESSION['__langue__'] . " AS tour, noGroupe, points, egaliteParfaite FROM Championnat_Equipes_Tours, Championnat_Equipes, Championnat_Categories, Championnat_Types_Tours WHERE egaliteParfaite>1 AND Championnat_Equipes_Tours.idEquipe=Championnat_Equipes.idEquipe AND Championnat_Categories.idCategorie=Championnat_Equipes_Tours.idCategorie AND Championnat_Types_Tours.idTour=Championnat_Equipes_Tours.idTour ORDER BY points, equipe";
    $retour = mysql_query($requete);
    if (mysql_num_rows($retour) > 0) {
        $pointsAvant = -1;
        echo "<h4>" . VAR_LANG_EGALITES_PARFAITES_RESOLUES_HASARD . "</h4><br />";
        ?>
        <?php
        $premier = true;
        while ($donnees = mysql_fetch_array($retour)) {
            if ($pointsAvant != $donnees['points']) { // Pour regrouper les équipes à égalités.
                $saisonFin = $donnees['saison'] + 1;
                if (!$premier) { // Fin du formulaire précédent.
                    echo "<input type='hidden' name='compteur' value='" . $c . "' />";
                    echo "<input type='hidden' name='idMax' value='" . $idMax . "' />";
                    echo "<input type='hidden' name='points' value='" . $pointsAvant . "' />";
                    echo "<input type='hidden' name='type' value='auto' />";
                    echo "<input type='submit' value=\"Ôter le classement manuel\" />";
                    echo "</fieldset>";
                    echo "</form><br />";
                    $c = 0;
                    $idMax = 0;
                }
                echo "<form name='egalitesClassees" . $donnees['points'] . "' action='' method='post'>";
                echo "<fieldset>";
                echo "<legend>" . VAR_LANG_SAISON . " " . $donnees['saison'] . " - " . $saisonFin . " / " . $donnees['categorie'] . " / " . $donnees['tour'] . " / " . VAR_LANG_GROUPE . " " . $donnees['noGroupe'] . " / " . VAR_LANG_POINTS . " : " . $donnees['points'] . "</legend>";
            }
            echo $donnees['equipe'] . " : " . $donnees['egaliteParfaite'];
            echo "<input type='hidden' name='egaliteParfaite" . $donnees['idEquipe'] . "' value='" . $donnees['egaliteParfaite'] . "' />";
            echo "<input type='hidden' name='nomEquipe" . $donnees['idEquipe'] . "' value='" . $donnees['equipe'] . "' /><br /><br />";
            $pointsAvant = $donnees['points'];
            if ($donnees['idEquipe'] > $idMax) {
                $idMax = $donnees['idEquipe'];
            }
            $premier = false;
            $c++;
        }
        // Fin du dernier formulaire.
        echo "<input type='hidden' name='compteur' value='" . $c . "' />";
        echo "<input type='hidden' name='idMax' value='" . $idMax . "' />";
        echo "<input type='hidden' name='points' value='" . $pointsAvant . "' />";
        echo "<input type='hidden' name='type' value='auto' />";
        echo "<input type='submit' value=\"Ôter le classement manuel\" />";
        echo "</fieldset>";
        echo "</form>";
    }
    ?>
</div>
