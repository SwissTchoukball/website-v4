<?php

if (hasRefereeManagementAccess()) {
	?>
	<div><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>&ajouter"><img src="admin/images/ajouter.png" alt="Ajouter des points" /> Ajouter des points</a></div>
	<div><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>&gerer"><img src="admin/images/modifier.png" alt="Gérer les points distribués" /> Gérer les points distribués</a></div>
	<div><a href="?menuselection=<? echo $_GET['menuselection']; ?>&smenuselection=<? echo $_GET['smenuselection']; ?>"><img src="admin/images/liste.png" alt="Liste des points distribués" /> Liste des points distribués</a></div>
	<br />
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