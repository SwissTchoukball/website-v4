<div class="supprimerNews">
    <?php
    statInsererPageSurf(__FILE__);

    if (isset($_GET['supprimerNewsID']) && is_numeric($_GET['supprimerNewsID'])) {
        $val = $_GET['supprimerNewsID'];
        $requeteSQL = "DELETE FROM `RegroupementNews` WHERE `idNews`='$val'";
        mysql_query($requeteSQL) or die ("<h4>Internal error</h4>");
        $requeteSQL = "DELETE FROM `News` WHERE `Id` =$val";
        mysql_query($requeteSQL) or die ("<h4>Internal error</h4>");
        echo "<h4>Suppression effectuée avec succès</h4>";
    }
    ?>
    <table class="tableauSupprimerNews">
        <?php
        echo "<tr>";
        echo "<th>Date</th>";
        echo "<th>Titre</th>";
        echo "</tr>";

        $requeteSQL = "SELECT * FROM `News` ORDER BY premiereNews DESC, `Date` DESC LIMIT 30";
        $recordset = mysql_query($requeteSQL);

        while ($record = mysql_fetch_array($recordset)) {
            echo "<tr>";
            echo "<td width='75px' class='center'>" . date_sql2date($record["date"]) . "</td>";
//		$titre =$record["titre".$_SESSION["__langue__"]]==""?$record["titre".$VAR_TABLEAU_DES_LANGUES[0][0]]:$record["titre".$_SESSION["__langue__"]]
            $titre = $record["titre" . $_SESSION["__langue__"]];
            if ($titre == "") {
                $titre = $record["titre" . $VAR_TABLEAU_DES_LANGUES[0][0]];
            }
            echo "<td><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&supprimerNewsID=" . $record["id"] . "' onClick='confirm(\"Êtes-vous sur de supprimer cette news ?\")'>" . $titre . "</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
