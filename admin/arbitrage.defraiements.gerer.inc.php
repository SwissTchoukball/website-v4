<?php

// TODO : pagination

echo '<h2>Gestion des versements</h2>';
echo '<table class="adminTable alternateBackground">';
echo '<tr>';
echo '<th>' . ucfirst(VAR_LANG_ARBITRE) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_MONTANT) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_POINTS) . '</th>';
echo '</tr>';

$queryVersements = "SELECT av.id, p.nom, p.prenom, av.datePaiement, av.montantPaye
                    FROM Arbitres_Versements av
                    LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne=av.idArbitre
                    ORDER BY av.datePaiement DESC, nom, prenom";

if ($versements = mysql_query($queryVersements)) {
    while ($versement = mysql_fetch_assoc($versements)) {
        echo '<tr class="clickable-row" data-href="?menuselection=' . $_GET['menuselection'] . '&smenuselection=' . $_GET['smenuselection'] . '&modifier=' . $versement['id'] . '">';
        echo '<td>' . $versement['prenom'] . ' ' . $versement['nom'] . '</td>';
        echo '<td>' . date_sql2date($versement['datePaiement']) . '</td>';
        echo '<td>' . $versement['montantPaye'] . '</td>';
        echo '</tr>';
    }
} else {
    printErrorMessage("Problème lors de la récupération de la liste des versements");
}
echo '</table>';
?>
