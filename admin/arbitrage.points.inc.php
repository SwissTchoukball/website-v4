<?php

if (hasRefereeManagementAccess()) {
    ?>
    <div>
        <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&ajouter"><img
                src="/admin/images/ajouter.png" alt="Ajouter des points"/> Ajouter des points</a></div>
    <div>
        <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&gerer"><img
                src="/admin/images/modifier.png" alt="G�rer les points distribu�s"/> G�rer les points distribu�s</a>
    </div>
    <div>
        <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>"><img
                src="/admin/images/liste.png" alt="Liste des points distribu�s"/> Liste des points distribu�s</a></div>
    <br/>
    <?php
}

include('admin/arbitrage.points.databaseWrite.inc.php');

if ((isset($_GET['ajouter']) || isset($_GET['modifier'])) && hasRefereeManagementAccess()) {
    include('admin/arbitrage.points.editer.inc.php');
} elseif (isset($_GET['gerer']) && hasRefereeManagementAccess()) {
    include('admin/arbitrage.points.gerer.inc.php');
} else {
    include('admin/arbitrage.points.liste.inc.php');
}

?>
