<?php
if ($_SESSION['__nbIdClub__'] == 15) { //15 = Club ind�fini
    echo "<p class='notification'>Aucun club n'est associ� � votre compte.</p>";
} else {
    include('admin/club.statistiques.membres.inc.php');

    $showDetails = isset($_GET['details']);
    $nbMembersPerPage = null; //On veut afficher tous les membres en une page.
    $clubToShowId = $_SESSION['__nbIdClub__'];

    include('admin/membres.liste.inc.php');
}
