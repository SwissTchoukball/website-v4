<div class="insererMatch">
    <?php
    statInsererPageSurf(__FILE__);

    if (isset($_POST['action']) && $_POST['action'] == 'goToStep2') {
        include('inserer.championnat.match.etape2.inc.php');
    } elseif (isset($_GET['nbMatchs']) AND isset($_GET['saison']) AND isset($_GET['idCat']) AND isset($_GET['idTour']) AND isset($_GET['idGroupe'])) {
        include('inserer.championnat.match.etape3.inc.php');
    } elseif (isset($_POST['action']) && $_POST['action'] == 'insererMatchs2') {
        include('inserer.championnat.match.etape4.inc.php');
    } else {
        include('inserer.championnat.match.etape1.inc.php');
    }
    ?>
</div>
