<?
	statInsererPageSurf(__FILE__);
	$idEvent = 4; // gen�ve = 4
	
	$nbColonne=7;
	$bordure=true;
?>

<h3><? echo VAR_LANG_GENEVE_2005; ?><br />
<a href='http://www.tchoukball.org/wbtc05/index.php?lang=fr' target="_blank"><img border="0" src="<? echo VAR_IMG_GENEVE_2005;?>"></a></h3>
<p align="center"><strong><a href="http://www.tchoukball.org/wbtc05/champ/result.php" target="_blank">Consultez tous les scores sur le site officiel du WBTC-05</a></strong></p><br />
<table class="tableauInternational">
<? 
	include "moteur.affichage.international.inc.php";
?>
</table>