<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&ajouter"><img
            src="/admin/images/ajouter.png" alt="Ajouter un versement"/> Ajouter un versement</a></div>
<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>&gerer"><img
            src="/admin/images/modifier.png" alt="G�rer les versements"/> G�rer les versements</a></div>
<div>
    <a href="?menuselection=<?php echo $_GET['menuselection']; ?>&smenuselection=<?php echo $_GET['smenuselection']; ?>"><img
            src="/admin/images/liste.png" alt="Liste des d�fraiements"/> Liste des d�fraiements</a></div>
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
