<?php
if (isset($_POST['prochaineEtape'])) {
    if ($_POST['prochaineEtape'] == "etape2") {
        include('inserer.edition.coupech.etape2.inc.php');
    } elseif ($_POST['prochaineEtape'] == "etape3") {
        include('inserer.edition.coupech.etape3.inc.php');
    } elseif ($_POST['prochaineEtape'] == "etape4") {
        include('inserer.edition.coupech.etape4.inc.php');
    }
} else {
    include('inserer.edition.coupech.etape1.inc.php');
}
