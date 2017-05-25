<?php

// TODO : pagination

echo '<h2>Gestion des points distribués</h2>';
echo '<table class="st-table st-table--alternate-bg">';
echo '<tr>';
echo '<th>' . ucfirst(VAR_LANG_ARBITRE) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_DATE) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_TYPE_POINTS) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_POINTS) . '</th>';
echo '</tr>';

$queryPoints = "SELECT ap.id, p.nom, p.prenom, ap.date, atp.typesPoints" . $_SESSION['__langue__'] . " AS typePoint, ap.points
						FROM Arbitres_Points ap
						LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne=ap.idArbitre
						LEFT OUTER JOIN Arbitres_Types_Points atp ON atp.id=ap.idTypePoints
						WHERE atp.ajoutManuel=1
						ORDER BY date DESC, nom, prenom";

if ($points = mysql_query($queryPoints)) {
    while ($point = mysql_fetch_assoc($points)) {
        echo '<tr class="clickable-row" data-href="?menuselection=' . $_GET['menuselection'] . '&smenuselection=' . $_GET['smenuselection'] . '&modifier=' . $point['id'] . '">';
        echo '<td>' . $point['prenom'] . ' ' . $point['nom'] . '</td>';
        echo '<td>' . date_sql2date($point['date']) . '</td>';
        echo '<td>' . $point['typePoint'] . '</td>';
        echo '<td>' . $point['points'] . '</td>';
        echo '</tr>';
    }
} else {
    printErrorMessage("Problème lors de la récupération de la liste des points");
}
echo '</table>';
