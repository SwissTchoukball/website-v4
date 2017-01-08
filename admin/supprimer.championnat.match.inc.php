<?php
statInsererPageSurf(__FILE__);
?>
<div class="supprimerMatch">
    <?php
    if (isset($_POST['action']) && $_POST['action'] == 'supprimerMatchs1') {
        include('supprimer.championnat.match.etape2.inc.php');
    } elseif (isset($_POST['action']) && $_POST['action'] == 'supprimerMatchs2') {
        include('supprimer.championnat.match.etape3.inc.php');
    } else {
        include('supprimer.championnat.match.etape1.inc.php');
    }
    ?>
</div>
