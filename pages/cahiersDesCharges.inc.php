<div id="cahierDesCharges">
    <?php

    if (isset($_GET['id']) && isValidID($_GET['id'])) {
        include('pages/cahierDesCharges.afficher.inc.php');
    } else {
        include('pages/cahiersDesCharges.liste.inc.php');
    }

    ?>
</div>