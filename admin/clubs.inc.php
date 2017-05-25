<div id="clubs">
    <?php
    statInsererPageSurf(__FILE__);
    ?>

    <p><a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&new"><img
                src="/admin/images/ajouter.png" alt="Ajouter un club"/> Ajouter un club</a><br/>
        <a href="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>"><img
                src="/admin/images/liste.png" alt="Liste des clubs"/> Liste des clubs</a></p>

    <?php

    $newClub = isset($_GET['new']);
    $nbError = 0;
    if (isset($_POST['clubID']) && isValidClubID($_POST['clubID'])) {
        include("admin/clubs.databaseWrite.php");
        if ($nbError == 0) {
            include('admin/clubs.liste.inc.php');
        } else {
            include("admin/clubs.editer.inc.php");
        }
    } elseif (isset($_GET['edit']) && isValidClubID($_GET['edit'])) { //édition demandé && id conforme
        $idClubToEdit = $_GET['edit'];
        include("admin/clubs.editer.inc.php");
    } elseif ($newClub) { //ajout
        include("admin/clubs.editer.inc.php");
    } elseif (isset($_GET['delete']) && isValidClubID($_GET['delete'])) { //suppression demandé && id conforme
        include("admin/clubs.supprimer.inc.php");
        include('admin/clubs.liste.inc.php');
    } else {
        include('admin/clubs.liste.inc.php');
    }

    ?>
</div>
