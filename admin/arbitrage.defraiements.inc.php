<div>
    <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&ajouter"><img
            src="/admin/images/ajouter.png" alt="Ajouter un versement"/> Ajouter un versement</a></div>
<div>
    <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&gerer"><img
            src="/admin/images/modifier.png" alt="Gérer les versements"/> Gérer les versements</a></div>
<div>
    <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>"><img
            src="/admin/images/liste.png" alt="Liste des défraiements"/> Liste des défraiements</a></div>
<br/>
<?php

include('admin/arbitrage.defraiements.databaseWrite.inc.php');

if ((isset($_GET['ajouter']) || isset($_GET['modifier']))) {
    include('admin/arbitrage.defraiements.editer.inc.php');
} elseif (isset($_GET['gerer'])) {
    include('admin/arbitrage.defraiements.gerer.inc.php');
} else {
    include('admin/arbitrage.defraiements.liste.inc.php');
}

?>
