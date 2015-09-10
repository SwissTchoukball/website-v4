<?
    statInsererPageSurf(__FILE__);
 
    $idEvent=2;
    $nbColonne=7;
    $bordure=true;
    $nomEvent=VAR_LANG_ITALIE_2003;
    $bonus="";
?>
<form name="international" action="" method="post" class="center">
    <select name="idEvent" onChange="international.submit();">
        <option value='2' <? if($nomEvent==VAR_LANG_ITALIE_2003){ echo "selected='selected'"; } ?> ><? echo VAR_LANG_ITALIE_2003_SHORT; ?></option>
        <option value='3' <? if($nomEvent==VAR_LANG_TAIWAN_2004){ echo "selected='selected'"; } ?> ><? echo VAR_LANG_TAIWAN_2004_SHORT; ?></option>
        <option value='4' <? if($nomEvent==VAR_LANG_GENEVE_2005){ echo "selected='selected'"; } ?> ><? echo VAR_LANG_GENEVE_2005_SHORT; ?></option>
    </select>
</form>
<?
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