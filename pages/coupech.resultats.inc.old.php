<?php
if (isset($_POST['annee'])) {
    if ($_POST['annee'] == "" OR $_POST['annee'] == "Avenir") {
        $annee = "Avenir";
    } else {
        $annee = $_POST['annee'];
    }
}
?>
<div class="resultats">
    <form name="resultatsCoupeCH" action="" method="post">
        <table border="0" align="center">
            <tr>
                <td><p><?php echo $agenda_annee; ?> :</p></td>
                <td align="left"><select name="annee" id="select" onChange="resultatsCoupeCH.submit();">
                        <?php
                        // recherche de la premiere date
                        $requeteAnnee = "SELECT MIN( CoupeCH_Categories_Par_Annee.annee ) AS min FROM CoupeCH_Categories_Par_Annee";
                        $recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
                        $anneeMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");

                        $anneeDebutCoupeCH = $anneeMin['min'];

                        if ($annee == "") {
                            $annee = "Avenir";
                        }

                        if ($annee == "Avenir") {
                            echo "<option selected value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                        } else {
                            echo "<option value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                        }

                        $fin = date('Y') + 1;
                        for ($i = $anneeDebutCoupeCH; $i <= $fin; $i++) {
                            if ($annee == $i) {
                                echo "<option selected value='" . $i . "'>" . $i . "</option>";
                            } else {
                                echo "<option value='" . $i . "'>" . $i . "</option>";
                            }
                        }
                        ?></select>
                </td>
            </tr>
        </table>
    </form>

    <?php
    if ($annee == "Avenir") {
        $anneeRecherche = date('Y');
    } else {
        $anneeRecherche = $annee;
    }
    // Sélection de la politique des points de l'année choisie.
    $requeteAnnee = "SELECT COUNT(*) AS nbCat FROM CoupeCH_Categories_Par_Annee WHERE annee=" . $anneeRecherche . "";
    $retourAnnee = mysql_query($requeteAnnee);
    $donneesAnnee = mysql_fetch_array($retourAnnee);
    $nbCategories = $donneesAnnee['nbCat'];

    // J'EN SUIS RESTÉ ICI !!!

    $requete = "SELECT * FROM Championnat_Tours WHERE saison=" . $annee . " ORDER BY idCategorie, idTour DESC, idGroupe";
    $retour = mysql_query($requete);
    $nbCategories = 0;
    $nbTours = 0;
    $nbGroupes = 0;
    $tableauCategories = array();
    while ($donnees = mysql_fetch_array($retour)) {
        $idTour = $donnees['idTour'];

        if ($tableauCategories[$donnees['idCategorie']] != oui) { // On vérifie si c'est la première fois que cette catégorie apparaît dans la liste des tours. Si c'est le cas, l'id de la categorie n'appartient pas à $tableauCategories donc on affiche le nom de la categorie.
            $tableauCategories[$donnees['idCategorie']] = oui;
            $requeteA = "SELECT categorie" . $_SESSION['__langue__'] . " FROM Championnat_Categories WHERE idCategorie=" . $donnees['idCategorie'] . "";
            // echo $requeteA;
            $retourA = mysql_query($requeteA);
            $donneesA = mysql_fetch_array($retourA);
            $nomCategorie = $donneesA['categorie' . $_SESSION['__langue__']];
            $idCategorie = $donnees['idCategorie'];
            if ($idCategorie != -1) {
                echo "<h3>" . $nomCategorie . "</h3>";
            }
        }

        if ($idTour != 2000) {
            if ($donnees['idGroupe'] < 2) {
                $retourB = mysql_query("SELECT tour" . $_SESSION['__langue__'] . " FROM Championnat_Types_Tours WHERE idTour=" . $donnees['idTour'] . "");
                $donneesB = mysql_fetch_array($retourB);
                $nomTour = $donneesB['tour' . $_SESSION['__langue__']];
                echo "<h4>" . $nomTour . "</h4><br />";
            }
        }
        $nbTours++;
        if ($donnees['idGroupe'] != 0) {
            echo "<h5>" . VAR_LANG_GROUPE . " " . $donnees['idGroupe'] . "</h5>";
        }
        echo "<table class='resultatsTour'>";
        echo "<tr><th class='center'>";
        if ($idTour == 10000 OR $idTour == 2000 OR $idTour == 3000 OR $idTour == 4000) {
            ?>
            Description
            <?php
        } else {
            ?>
            Journée
            <?php
        }
        ?>
        </th>
        <th class="right">Club recevant</th>
        <th>Club visiteur</th>
        <th class="center">Score</th>
        </tr>
        <?php
        $requeteC = "SELECT * FROM Championnat_Matchs WHERE saison=" . $annee . " AND idCategorie=" . $donnees['idCategorie'] . " AND idTour=" . $donnees['idTour'] . " AND noGroupe=" . $donnees['idGroupe'] . " ORDER BY idTypeMatch, journee, dateDebut, heureDebut";
        // echo $requeteC;
        $retourC = mysql_query($requeteC);
        $tableauJournees = array();
        while ($donneesC = mysql_fetch_array($retourC)) {
            echo "<tr>";
            echo "<td class='center' height='20px'>";
            if ($idTour == 10000 OR $idTour == 2000 OR $idTour == 3000 OR $idTour == 4000) {
                $requeteD = "SELECT * FROM Championnat_Types_Matchs WHERE idTypeMatch=" . $donneesC['idTypeMatch'] . "";
                $retourD = mysql_query($requeteD);
                $donneesD = mysql_fetch_array($retourD);
                echo $donneesD['type' . $_SESSION['__langue__']];
            } else {
                if ($tableauJournees[$donneesC['journee']] != oui) {
                    $tableauJournees[$donneesC['journee']] = oui;
                    if ($donneesC['journee'] != 0) {
                        echo $donneesC['journee'];
                    }
                }
            }
            echo "</td>";
            echo "<td class='right'>";
            $requeteD = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=" . $donneesC['equipeA'] . "";
            $retourD = mysql_query($requeteD);
            $donneesD = mysql_fetch_array($retourD);
            if ($donneesC['pointsA'] > $donneesC['pointsB']) {
                echo "<strong>";
            }
            echo $donneesD['equipe'];
            if ($donneesC['pointsA'] > $donneesC['pointsB']) {
                echo "</strong>";
            }
            echo "</td>";
            echo "<td>";
            $requeteD = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=" . $donneesC['equipeB'] . "";
            $retourD = mysql_query($requeteD);
            $donneesD = mysql_fetch_array($retourD);
            if ($donneesC['pointsA'] < $donneesC['pointsB']) {
                echo "<strong>";
            }
            echo $donneesD['equipe'];
            if ($donneesC['pointsA'] < $donneesC['pointsB']) {
                echo "</strong>";
            }
            echo "</td>";
            echo "<td class='center'>";
            if ($donneesC['pointsA'] != 0 OR $donneesC['pointsB'] != 0) {
                echo $donneesC['pointsA'] . "-" . $donneesC['pointsB'];
            } elseif ($donneesC['reportDebut'] != 0000 - 00 - 00) {
                echo "Reporté";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</div>
