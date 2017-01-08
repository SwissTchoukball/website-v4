<?php ?>
<h3>
    <?php echo VAR_LANG_ETAPE_1; ?>
</h3>
<div class="modifierMatch">
    <p>Séléctionnez l'édition dans laquelle vous souhaitez modifier le score d'un match.</p>
    <table class="tableauEditionsModifierMatchCoupeCH">
        <?php
        echo "<tr>";
        echo "<th>" . VAR_LANG_EDITION . "</th>";
        echo "<th>" . VAR_LANG_CATEGORIE . "</th>";
        echo "</tr>";

        $requete = "SELECT * FROM CoupeCH_Categories_Par_Annee ORDER BY annee DESC, idCategorie";
        $retour = mysql_query($requete);
        while ($donnees = mysql_fetch_array($retour)) {

            $requeteCat = "SELECT nom" . $_SESSION['__langue__'] . " FROM CoupeCH_Categories WHERE idCategorie=" . $donnees['idCategorie'] . "";
            $retourCat = mysql_query($requeteCat);
            $donneesCat = mysql_fetch_array($retourCat);
            $categorie = $donneesCat['nom' . $_SESSION['__langue__']];

            echo "<tr>";
            echo "<td><a href=?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&modAnnee=" . $donnees['annee'] . "&modCat=" . $donnees['idCategorie'] . ">" . $donnees['annee'] . "</a></td>";
            echo "<td>" . $categorie . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
