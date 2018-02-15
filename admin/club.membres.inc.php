<div id="membres"><?php
    statInsererPageSurf(__FILE__);

    $clubRequest = "SELECT club FROM clubs WHERE nbIdClub=" . $_SESSION["__nbIdClub__"];
    $clubResult = mysql_query($clubRequest);
    $clubData = mysql_fetch_assoc($clubResult);

    $clubName = $clubData['club'];

    //Vérification si le montant de la cotisation est bloqué et non-payé.
    $unpaidFees = false;
    $resultCheckIfUnpaidFees = mysql_query("SELECT datePaiement FROM Cotisations_Clubs WHERE idClub=" . $_SESSION['__nbIdClub__']);
    while ($donnees = mysql_fetch_assoc($resultCheckIfUnpaidFees)) {
        if ($donnees['datePaiement'] == null) {
            $unpaidFee = true;
        }
    }


    echo "<h4>" . $clubName . "</h4><br />";

    if (!$_SESSION['__gestionMembresClub__']) {
        echo "<p class='notification'>Vous n'êtes pas reconnu en tant que gestionnaire des membres de votre club (Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a> si vous l'êtes)</p>";
    } elseif ($unpaidFees) {
        echo "<p class='notification'>Vous ne pouvez pas faire de modification car le paiement de la cotisation n'a pas encore été confirmé.</p>";
    } else {
        ?>
        <p><a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&new"><img
                    src="/admin/images/ajouter.png" alt="Ajouter un membre"/> Ajouter un membre</a><br/>
            <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>"><img
                    src="/admin/images/liste.png" alt="Liste des membres simple"/> Liste des membres simple</a><br/>
            <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&details"><img
                    src="/admin/images/liste.png" alt="Liste des membres détaillée"/> Liste des membres
                détaillée</a><br/>
            <a href="admin/club.export.excel.php"><img src="/admin/images/document_excel.png" alt="Liste Excel"/> Liste
                Excel</a></p>
        <?php

        $newMember = false;
        $nbError = 0;
        if (isset($_POST['memberID']) && (isValidMemberID($_POST['memberID']) || $_POST['memberID'] == 0)) {
            include("admin/membres.databaseWrite.php");
            if ($nbError == 0) {
                include("admin/club.liste.membres.inc.php");
            } else {
                include("admin/membres.editer.inc.php");
            }
        } elseif (isset($_GET['edit']) && isValidMemberID($_GET['edit'])) { //édition demandé && id conforme
            $idMemberToEdit = $_GET['edit'];
            include("admin/membres.editer.inc.php");
        } elseif (isset($_GET['new'])) { //ajout
            $newMember = true;
            include("admin/membres.editer.inc.php");
        } elseif (isset($_GET['delete']) && isValidMemberID($_GET['delete'])) { //suppression demandé && id conforme
            include("admin/membres.supprimer.inc.php");
            include("admin/club.liste.membres.inc.php");
        } elseif (isset($_GET['transfer-request']) && isValidMemberID($_GET['transfer-request'])) { // demande de transfert demandé et id conforme
            include("admin/membres.requete-transfert.inc.php");
        } else {
            include("admin/club.liste.membres.inc.php");
        }
    }
    ?>
</div>
