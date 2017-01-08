<div class="kicker">Cahier des charges</div>
<?php
if (isset($_GET['id']) && isValidID($_GET['id'])) {
    $specID = $_GET['id'];

    $query = "SELECT c.title, c.body, c.competences, c.lastEdit, c.forCommitteeMember, p.nom, p.prenom
				  FROM CahierDesCharges c
				  LEFT OUTER JOIN Personne p ON c.lastEditor = p.id
				  WHERE c.id = " . $specID . "
				  LIMIT 1";

    if ($data = mysql_query($query)) {
        $spec = mysql_fetch_assoc($data);
        echo '<h1>' . $spec['title'] . '</h1>';
        echo $spec['forCommitteeMember'] == 1 ? '<p>Poste pour membre du comité exécutif</p>' : '';
        echo '<div>' . markdown($spec['body']) . '</div>';
        echo '<h2>Compétences</h2>';
        echo '<div>' . markdown($spec['competences']) . '</div>';
        echo '<p>Dernière modification ' . date_sql2date_joli($spec['lastEdit'], 'le', 'Fr', false);
        if (isAdmin()) {
            echo ' par ' . $spec['prenom'] . ' ' . $spec['nom'];
        }
        echo '</p>';
    } else {
        printErrorMessage('Erreur lors de la récupération des informations');
        if (isAdmin()) {
            printInfoMessage(mysql_error());
            printInfoMessage($query);
        }
    }
} else {
    printErrorMessage('Identifiant invalide');
}
?>
<p><a href="/cahiers-des-charges">Retour à la liste des cahiers des charges</a></p>