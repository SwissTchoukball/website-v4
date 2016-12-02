<?php
?>
    <h3>
        <?php echo VAR_LANG_ETAPE_2; ?>
    </h3>
<?php
if (!(isset($_POST['saison']) AND isset($_POST['categorie']) AND isset($_POST['tour']) AND isset($_POST['idGroupe']))) {
    echo "Erreur: il manque des informations.";
} else {
    $saison = $_POST['saison'];
    $idCategorie = $_POST['categorie'];
    $idTour = $_POST['tour'];
    $idGroupe = $_POST['idGroupe'];
    ?>
    <form name="supprimerMatch" method="post"
          action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"
          onSubmit="return testQqchASupprimer();">
        <table class="tableauSupprimerMatch">
            <?php
            echo "<tr>";
            echo "<th>X</th>";
            echo "<th>Date</th>";
            echo "<th>Match</th>";
            echo "<th>";
            if ($idTour == 10000 OR $idTour == 2000 OR $idTour == 3000 OR $idTour == 4000) {
                ?>
                Description
                <?php
            } else {
                ?>
                Journée
                <?php
            }
            echo "</th>";
            echo "</tr>";

            $aujourdhui = date_actuelle();

            $requete = "SELECT idMatch, equipeA, equipeB, journee, dateDebut, idTypeMatch FROM Championnat_Matchs WHERE saison=" . $saison . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $idGroupe . " ORDER BY journee, idTypeMatch";
            $retour = mysql_query($requete);
            $tableauJournees = array();
            while ($donnees = mysql_fetch_array($retour)) {
                echo "<tr>";
                echo "<td class='center'><input class='couleurRadio' type='checkbox' name='matchArray[]' value='" . $donnees['idMatch'] . "' class='couleurCheckBox'></td>";
                echo "<td class='center'>" . date_sql2date($donnees['dateDebut']) . "</td>";
                echo "<td class='center'>";
                $requeteA = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=" . $donnees['equipeA'] . "";
                $retourA = mysql_query($requeteA);
                $donneesA = mysql_fetch_array($retourA);
                echo $donneesA['equipe'];
                echo " - ";
                $requeteB = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=" . $donnees['equipeB'] . "";
                $retourB = mysql_query($requeteB);
                $donneesB = mysql_fetch_array($retourB);
                echo $donneesB['equipe'];
                echo "</td>";
                echo "<td>";
                if ($idTour == 10000 OR $idTour == 2000 OR $idTour == 3000 OR $idTour == 4000) {
                    $requeteD = "SELECT * FROM Championnat_Types_Matchs WHERE idTypeMatch=" . $donnees['idTypeMatch'] . "";
                    $retourD = mysql_query($requeteD);
                    $donneesD = mysql_fetch_array($retourD);
                    echo $donneesD['type' . $_SESSION['__langue__']];
                } else {
                    if ($tableauJournees[$donnees['journee']] != oui) {
                        $tableauJournees[$donnees['journee']] = oui;
                        if ($donnees['journee'] != 0) {
                            echo $donnees['journee'];
                        }
                    }
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <input type="hidden" name="saison" value="<?php echo $saison; ?>">
        <input type="hidden" name="idCategorie" value="<?php echo $idCategorie; ?>">
        <input type="hidden" name="idTour" value="<?php echo $idTour; ?>">
        <input type="hidden" name="idGroupe" value="<?php echo $idGroupe; ?>">
        <input type="hidden" name="action" value="supprimerMatchs2">
        <input type="submit" name="submit" value="<?php echo VAR_LANG_SUPPRIMER; ?>">
    </form>
    <?php
}
