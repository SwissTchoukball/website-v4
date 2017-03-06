<div class="supprimerPhase">
    <?php
    statInsererPageSurf(__FILE__);

    $tourArray = $_POST['tourArray'];

    if (isset($_POST["action"]) && $_POST["action"] == "supprimerTour") {

        if (is_array($tourArray)) {
            while (list(, $val) = each($tourArray)) {
                $tabValeur = preg_split("/[:]+/", $val);
                $saison = $tabValeur[0];
                $categorie = $tabValeur[1];
                $tour = $tabValeur[2];
                $groupe = $tabValeur[3];
                //echo "para = ".$saison.";".$tour.";".$groupe;

                $requete = "DELETE FROM Championnat_Tours WHERE saison=" . $saison . " AND idCategorie=" . $categorie . " AND idTour=" . $tour . " AND idGroupe=" . $groupe . "";
                // echo $requete;
                mysql_query($requete);
                $requete = "DELETE FROM Championnat_Equipes_Tours WHERE saison=" . $saison . " AND idCategorie=" . $categorie . " AND idTour=" . $tour . " AND noGroupe=" . $groupe . "";
                // echo $requete;
                mysql_query($requete);
            }

            echo "<h4>Suppression effectuée avec succès</h4><br /><br />";
        } else {
            echo "<h4>Rien à supprimer</h4><br /><br />";
        }
    }
    ?>
    <form name="supprimerTour" method="post"
          action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"
          onSubmit="return testQqchASupprimer();">
        <table class="st-table">
            <?php
            echo "<tr>";
            echo "<th>X</th>";
            echo "<th>" . VAR_LANG_CHAMPIONNAT . "</th>";
            echo "<th>" . VAR_LANG_CATEGORIE . "</th>";
            echo "<th>" . VAR_LANG_TOUR . "</th>";
            echo "<th>" . VAR_LANG_GROUPE . "</th>";
            echo "</tr>";

            $aujourdhui = date_actuelle();

            $requete = "SELECT * FROM Championnat_Tours ORDER BY saison DESC, idCategorie, idTour DESC, idGroupe DESC";

            $retour = mysql_query($requete);

            echo "<script language='JavaScript'>var nbTourChampionnatAfficher=" . mysql_affected_rows() . "</script>";
            ?>
            <script language="JavaScript">
                function testQqchASupprimer() {
                    var TourChampionnatCoche = false;

                    for (var i = 0; i < nbTourChampionnatAfficher && !TourChampionnatCoche; i++) {
                        if (supprimerTour.elements[i].checked) {
                            TourChampionnatCoche = true;
                        }
                    }
                    if (!TourChampionnatCoche)alert("Rien à supprimer");
                    return TourChampionnatCoche;
                }
            </script>
            <?php

            while ($donnees = mysql_fetch_array($retour)) {
                echo "<tr>";
                echo "<td class='center'><input class='couleurRadio' type='checkbox' name='tourArray[]' value='" . $donnees['saison'] . ":" . $donnees['idCategorie'] . ":" . $donnees['idTour'] . ":" . $donnees['idGroupe'] . "' class='couleurCheckBox'></td>";
                echo "<td class='center'>" . VAR_LANG_CHAMPIONNAT . " " . $donnees['saison'] . "-" . ($donnees['saison'] + 1) . "</td>";
                $requeteA = "SELECT categorie" . $_SESSION["__langue__"] . " FROM Championnat_Categories WHERE idCategorie=" . $donnees['idCategorie'] . "";
                $retourA = mysql_query($requeteA);
                $donneesA = mysql_fetch_array($retourA);
                if ($donnees['idCategorie'] == 0) { // Promotion / Relegation
                    echo "<td colspan='3' class='center'>" . $donneesA["categorie" . $_SESSION["__langue__"]] . "</td>";
                } else {
                    echo "<td class='center'>" . $donneesA["categorie" . $_SESSION["__langue__"]] . "</td>";
                    $requeteB = "SELECT tour" . $_SESSION["__langue__"] . " FROM Championnat_Types_Tours WHERE idTour=" . $donnees['idTour'] . "";
                    $retourB = mysql_query($requeteB);
                    $donneesB = mysql_fetch_array($retourB);
                    if ($donnees["idTour"] == 10000 OR $donnees["idTour"] == 2000 OR $donnees["idTour"] == 3000 OR $donnees["idTour"] == 4000) {
                        echo "<td colspan='2' class='center'>" . $donneesB["tour" . $_SESSION["__langue__"]] . "</td>";
                    } else {
                        echo "<td class='center'>" . $donneesB["tour" . $_SESSION["__langue__"]] . "</td>";
                        if ($donnees["idGroupe"] == 0) {
                            echo "<td class='center'>Qualifications</td>";
                        } else {
                            echo "<td class='center'>" . VAR_LANG_GROUPE . " " . $donnees["idGroupe"] . "</td>";
                        }
                    }
                }
                echo "</tr>";
            }
            ?>
        </table>
        <br>
        <input type="hidden" name="action" value="supprimerTour">
        <p align="center"><input type="submit" name="Supprimer" value="<?php echo VAR_LANG_SUPPRIMER; ?>"></p>
    </form>
</div>
