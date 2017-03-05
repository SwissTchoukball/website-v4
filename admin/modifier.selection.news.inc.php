<?php
echo "<table class='adminTable'>";
echo "<tr>";
echo "<th>Date</th>";
echo "<th>Titre</th>";
echo "</tr>";

$requeteSQL = "SELECT * FROM `News` ORDER BY premiereNews DESC, `Date` DESC";
$recordset = mysql_query($requeteSQL);

while ($record = mysql_fetch_array($recordset)) {
    echo "<tr>";
    echo "<td width='75px' class='center'>" . date_sql2date($record["date"]) . "</td>";
    $titre = $record["titre" . $_SESSION["__langue__"]];
    if ($titre == "") {
        $titre = $record["titre" . $VAR_TABLEAU_DES_LANGUES[0][0]];
    }
    echo "<td><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&modifierNewsID=" . $record["id"] . "'>" . $titre . "</a></td>";
    echo "</tr>";
}
?>
</table>
