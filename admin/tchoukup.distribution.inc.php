<h3>Liste des personnes de votre club à qui distribuer le Tchouk<sup>up</sup></h3>
<p>Si la base de données a été modifiée après que nous ayons transmis la commande à l'imprimeur, il se peut que le
    nombre de Tchouk<sup>up</sup> que vous recevez ne corresponde pas au nombre de personnes listées ici.</p>
<?php
if ($_SESSION['__nbIdClub__'] == 15 && !isAdmin()) { //15 = Club indéfini
    echo "<p class='info'>Aucun club n'est associé à votre compte.</p>";
} elseif ($_SESSION["__gestionMembresClub__"] || isAdmin()) {
    if (isAdmin() && isset($_GET['clubId']) && is_numeric($_GET['clubId'])) {
        $clubId = $_GET['clubId'];
    } else {
        $clubId = $_SESSION['__nbIdClub__'];
    }

    $distributionQuery =
        "SELECT `ClubsFstb`.`club`,
               `DBDPersonne`.`nom`,
               `DBDPersonne`.`prenom`
        FROM `DBDPersonne`, `ClubsFstb`
        WHERE `idCHTB` = 2
        AND `DBDPersonne`.`idClub` = `ClubsFstb`.`nbIdClub`
        AND `ClubsFstb`.`actif` = 1 -- Membre d'un club actif
        AND (`DBDPersonne`.`idStatus` = 3 OR `DBDPersonne`.`idStatus` = 6) -- membre actif ou junior
        AND `idClub` = " . $clubId . "
        ORDER BY `nom`, `prenom`";

    $distributionResult = mysql_query($distributionQuery);
    $nbPeople = mysql_num_rows($distributionResult);

    echo '<table class="adminTable">';
    echo '<tr><th>Prénom</th><th>Nom</th></tr>';
    while($person = mysql_fetch_assoc($distributionResult)) {
        echo '<tr>';
        echo '<td>' . $person['prenom'] . '</td>';
        echo '<td>' . $person['nom'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}