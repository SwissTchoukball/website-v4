<?php
	statInsererPageSurf(__FILE__);
	$idEvent = 3; // taiwan = 3

	$nbColonne=7;
	$bordure=true;
?>
<h3><?php echo VAR_LANG_TAIWAN_2004; ?><br />
<img src="<?php echo VAR_IMG_EQUIPE_SUISSE_TAIWAN_2004;?>"></h3><br />
<table class="tableauInternational">
<?php
	include "moteur.affichage.international.inc.php";
?>
</table>
