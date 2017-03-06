<?php ?>
<h3>
    <?php echo VAR_LANG_ETAPE_2; ?>
</h3>
<div class="modifierMatch">
    <p>Séléctionnez le match dont vous souhaitez modifier le score.</p>
    <table class="st-table">
        <?php
        echo "<tr>";
        echo "<th>" . VAR_LANG_MATCH . "</th>";
        echo "<th>" . VAR_LANG_TOUR . "</th>";
        echo "</tr>";

        $annee = $_GET['modAnnee'];
        $idCategorie = $_GET['modCat'];

        //Détermination des journées concernées.
        $requeteJournee = "SELECT idJournee FROM CoupeCH_Journees WHERE annee=" . $annee . " AND idCategorie=" . $idCategorie . "";
        $retourJournee = mysql_query($requeteJournee);
        while ($donneesJournee = mysql_fetch_array($retourJournee)) {

            $requete = "SELECT idMatch, equipeA, equipeB, idTypeMatch FROM CoupeCH_Matchs WHERE idJournee=" . $donneesJournee['idJournee'] . " ORDER BY idTypeMatch DESC, ordre";
            //echo $requete;
            $retour = mysql_query($requete);
            while ($donnees = mysql_fetch_array($retour)) {

                // Détermination du type de match
                $requeteTypeMatch = "SELECT nom" . $_SESSION['__langue__'] . " FROM CoupeCH_Type_Matchs WHERE idTypeMatch=" . $donnees['idTypeMatch'] . "";
                $retourTypeMatch = mysql_query($requeteTypeMatch);
                $donneesTypeMatch = mysql_fetch_array($retourTypeMatch);
                $typeMatch = $donneesTypeMatch['nom' . $_SESSION['__langue__']];

                // Détermination du nom des équipes
                if ($donnees['equipeA'] == 0) {
                    if ($donnees['forfait'] == 3) {
                        $equipeA = "-";
                    } else {
                        $equipeA = VAR_LANG_INCONNU;
                    }
                } else {
                    $requeteEquipeA = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donnees['equipeA'] . "";
                    $retourEquipeA = mysql_query($requeteEquipeA);
                    $donneesEquipeA = mysql_fetch_array($retourEquipeA);
                    $equipeA = $donneesEquipeA['nomEquipe'];
                }
                if ($donnees['equipeB'] == 0) {
                    if ($donnees['forfait'] == 3) {
                        $equipeB = "-";
                    } else {
                        $equipeB = VAR_LANG_INCONNU;
                    }
                } else {
                    $requeteEquipeB = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe=" . $donnees['equipeB'] . "";
                    $retourEquipeB = mysql_query($requeteEquipeB);
                    $donneesEquipeB = mysql_fetch_array($retourEquipeB);
                    $equipeB = $donneesEquipeB['nomEquipe'];
                }

                echo "<tr>";
                echo "<td><a href=?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&modMatch=" . $donnees['idMatch'] . ">" . $equipeA . " - " . $equipeB . "</a></td>";
                echo "<td>" . $typeMatch . "</td>";
                echo "</tr>";
            }
        } //fin boucle while pour chaque journee
        ?>
    </table>
</div>
