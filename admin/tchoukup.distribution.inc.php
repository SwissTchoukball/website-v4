<h3>Liste des personnes de votre club à qui distribuer le Tchouk<sup>up</sup></h3>
<p>Si la base de données a été modifiée après que nous ayons transmis la commande à l'imprimeur, il se peut que le
    nombre de Tchouk<sup>up</sup> que vous recevez ne corresponde pas au nombre de personnes listées ici.</p>
<?php
if ($_SESSION['__nbIdClub__'] == null && !isAdmin()) { // Club indéfini
    echo "<p class='notification'>Aucun club n'est associé à votre compte.</p>";
} elseif ($_SESSION["__gestionMembresClub__"] || isAdmin()) {
    if (isAdmin() && isset($_GET['clubId']) && is_numeric($_GET['clubId'])) {
        $clubId = $_GET['clubId'];
    } else {
        $clubId = $_SESSION['__nbIdClub__'];
    }

    $distributionQuery =
        "SELECT c.`club`,
               p.`nom`,
               p.`prenom`
        FROM `DBDPersonne` p, `clubs` c
        WHERE p.`idCHTB` = 2
        AND p.`idClub` = c.`nbIdClub`
        AND c.`statusId` = 1 -- Membre d'un club adhérent actif
        AND (p.`idStatus` = 3 OR p.`idStatus` = 6) -- membre actif ou junior
        AND p.`idClub` = " . $clubId . "
        ORDER BY p.`nom`, p.`prenom`";

    $distributionResult = mysql_query($distributionQuery);
    $nbPeople = mysql_num_rows($distributionResult);

    echo '<table class="st-table">';
    echo '<tr><th>Prénom</th><th>Nom</th></tr>';
    while ($person = mysql_fetch_assoc($distributionResult)) {
        echo '<tr>';
        echo '<td>' . $person['prenom'] . '</td>';
        echo '<td>' . $person['nom'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}