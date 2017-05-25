<?php
?>
<h3>
    <?php echo VAR_LANG_ETAPE_1; ?>
</h3>

<p>Phase dans laquelle modifier un match :</p>
<table class="st-table">
    <?php
    echo "<tr>";
    echo "<th>" . VAR_LANG_CHAMPIONNAT . "</th>";
    echo "<th>" . VAR_LANG_CATEGORIE . "</th>";
    echo "<th>" . VAR_LANG_TOUR . "</th>";
    echo "<th>" . VAR_LANG_GROUPE . "</th>";
    echo "</tr>";

    $requete = "SELECT * FROM Championnat_Tours ORDER BY saison DESC, idCategorie, idTour DESC, idGroupe DESC";

    $retour = mysql_query($requete);

    while ($donnees = mysql_fetch_array($retour)) {
        echo "<tr>";
        echo "<td class='center'><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&saison=" . $donnees['saison'] . "&idCat=" . $donnees['idCategorie'] . "&idTour=" . $donnees['idTour'] . "&idGroupe=" . $donnees['idGroupe'] . "'>" . VAR_LANG_CHAMPIONNAT . " " . $donnees['saison'] . "-" . ($donnees['saison'] + 1) . "</a></td>";
        $requeteA = "SELECT categorie" . $_SESSION["__langue__"] . " FROM Championnat_Categories WHERE idCategorie=" . $donnees['idCategorie'];
        $retourA = mysql_query($requeteA);
        $donneesA = mysql_fetch_array($retourA);
        if ($donnees['idCategorie'] == 0) { // Promotion / Relegation
            echo "<td colspan='3' class='center'><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&saison=" . $donnees['saison'] . "&idCat=" . $donnees['idCategorie'] . "&idTour=" . $donnees['idTour'] . "&idGroupe=" . $donnees['idGroupe'] . "'>" . $donneesA["categorie" . $_SESSION["__langue__"]] . "</a></td>";
        } else {
            echo "<td class='center'><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&saison=" . $donnees['saison'] . "&idCat=" . $donnees['idCategorie'] . "&idTour=" . $donnees['idTour'] . "&idGroupe=" . $donnees['idGroupe'] . "'>" . $donneesA["categorie" . $_SESSION["__langue__"]] . "</a></td>";
            $requeteB = "SELECT tour" . $_SESSION["__langue__"] . " FROM Championnat_Types_Tours WHERE idTour=" . $donnees['idTour'];
            $retourB = mysql_query($requeteB);
            $donneesB = mysql_fetch_array($retourB);
            if ($donnees["idTour"] == 10000 OR $donnees["idTour"] == 2000 OR $donnees["idTour"] == 3000 OR $donnees["idTour"] == 4000) {
                echo "<td colspan='2' class='center'><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&saison=" . $donnees['saison'] . "&idCat=" . $donnees['idCategorie'] . "&idTour=" . $donnees['idTour'] . "&idGroupe=" . $donnees['idGroupe'] . "'>" . $donneesB["tour" . $_SESSION["__langue__"]] . "</a></td>";
            } else {
                echo "<td class='center'><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&saison=" . $donnees['saison'] . "&idCat=" . $donnees['idCategorie'] . "&idTour=" . $donnees['idTour'] . "&idGroupe=" . $donnees['idGroupe'] . "'>" . $donneesB["tour" . $_SESSION["__langue__"]] . "</a></td>";
                if ($donnees["idGroupe"] == 0) {
                    echo "<td class='center'><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&saison=" . $donnees['saison'] . "&idCat=" . $donnees['idCategorie'] . "&idTour=" . $donnees['idTour'] . "&idGroupe=" . $donnees['idGroupe'] . "'>Qualifications</a></td>";
                } else {
                    echo "<td class='center'><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&saison=" . $donnees['saison'] . "&idCat=" . $donnees['idCategorie'] . "&idTour=" . $donnees['idTour'] . "&idGroupe=" . $donnees['idGroupe'] . "'>" . VAR_LANG_GROUPE . " " . $donnees["idGroupe"] . "</a></td>";
                }
            }
        }
    }
    echo "</table>";
    ?>
