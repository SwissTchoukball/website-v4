<?php
if ($_SESSION['__nbIdClub__'] == 15) { //15 = Club ind�fini
    echo "<p class='notification'>Aucun club n'est associ� � votre compte.</p>";
} else {
    echo '<p>Pour toute <strong>question sur la gestion des membres</strong>, vous pouvez consulter <a href="https://wiki.tchoukball.ch/Gestion_des_membres_de_club" title="FAQ sur la gestion des membres de club" target="_blank">notre FAQ dédiée</a>.</p>';
    include('admin/club.statistiques.membres.inc.php');

    $showDetails = isset($_GET['details']);
    $nbMembersPerPage = null; //On veut afficher tous les membres en une page.
    $clubToShowId = $_SESSION['__nbIdClub__'];

    include('admin/membres.liste.inc.php');
}
