<?php
if ($_SESSION['__nbIdClub__'] == null) { // Club indéfini
    echo "<p class='notification'>Aucun club n'est associé à votre compte.</p>";
} else {
    include('admin/club.statistiques.membres.inc.php');

    $showDetails = isset($_GET['details']);
    $nbMembersPerPage = null; //On veut afficher tous les membres en une page.
    $clubToShowId = $_SESSION['__nbIdClub__'];

    include('admin/membres.liste.inc.php');
}
