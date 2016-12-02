<?php
if (isset($_GET['affichage'])) {
    if ($_GET['affichage'] == "avenir") {
        $affichage = "avenir";
    } elseif ($_GET['affichage'] == "evenement") {
        $affichage = "evenement";
    } else {
        $affichage = "calendrier";
    }
} else {
    $affichage = "calendrier";
}

if ($affichage == "calendrier") {
    echo "<h4>Calendrier</h4>";
    echo "<p><a href='?page=" . $_GET['page'] . "&affichage=avenir'>Afficher la liste des événements à venir</a></p>";
    include('pages/calendrier.php');
} elseif ($affichage == "avenir") {
    echo "<h4>Évenements à venir</h4>";
    echo "<p><a href='?page=" . $_GET['page'] . "&affichage=calendrier'>Afficher le calendrier</a></p>";
    include('pages/avenir.php');
} elseif ($affichage == "evenement") {
    echo "<p><a href='?page=" . $_GET['page'] . "&affichage=calendrier'>Afficher le calendrier</a></p>";
    echo "<p><a href='?page=" . $_GET['page'] . "&affichage=avenir'>Afficher la liste des événements à venir</a></p>";
    include('pages/evenement.php');
}

?>
