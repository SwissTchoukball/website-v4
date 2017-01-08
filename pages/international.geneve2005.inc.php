<?php
statInsererPageSurf(__FILE__);

$idEvent = 4;
$nbColonne = 7;
$bordure = true;
$nomEvent = VAR_LANG_GENEVE_2005;
$bonus = "<a href='http://www.tchoukball.org/wbtc05/index.php?lang=fr' target='_blank'><img border='0' src='" . VAR_IMG_GENEVE_2005 . "'></a>
    <p class='center'><strong><a href='http://www.tchoukball.org/wbtc05/champ/result.php' target='_blank'>Consultez tous les scores sur le site officiel du WBTC-05</a></strong></p>";
?>
<form name="international" action="" method="post" class="center">
    <select name="idEvent" onChange="international.submit();">
        <option value='2' <?php if ($nomEvent == VAR_LANG_ITALIE_2003) {
            echo "selected='selected'";
        } ?> ><?php echo VAR_LANG_ITALIE_2003_SHORT; ?></option>
        <option value='3' <?php if ($nomEvent == VAR_LANG_TAIWAN_2004) {
            echo "selected='selected'";
        } ?> ><?php echo VAR_LANG_TAIWAN_2004_SHORT; ?></option>
        <option value='4' <?php if ($nomEvent == VAR_LANG_GENEVE_2005) {
            echo "selected='selected'";
        } ?> ><?php echo VAR_LANG_GENEVE_2005_SHORT; ?></option>
    </select>
</form>
<?php
echo "<h3>";
echo $nomEvent;
echo "</h3><br />";
echo "<p class='center'>";
echo $bonus;
echo "</p><br />";

echo "<table class='tableauInternational'>";
include "moteur.affichage.international.inc.php";
echo "</table>";
?>
