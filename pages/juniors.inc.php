<?php
$image = false;

$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '3' ORDER BY paragrapheNum");
// affiche le texte
while($donnees = mysql_fetch_array($retour)) {
    if(!$image) {
        echo "<img class='imageFlottanteDroite' src=".VAR_IMAGE_FEDERATION_JUNIORS." />";
        $image = true;
    }

    if ($donnees['paragrapheNum'] == 5) {
        echo "<h3>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
        echo "</h3>";
    }
    else {
        echo "<p>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
        echo "</p>";
    }
}

$language = $_SESSION['__langue__'];
if ($language == 'De' || $language == 'Fr') {
    $langId = strtolower($language) . '_' . strtoupper($language);
} elseif ($language == 'En') {
    $langId = 'en_US';
} else {
    $langId = 'fr_FR';
}

?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="paypal-button">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="A3WULH36VPPDS">
    <input type="image" src="https://www.paypalobjects.com/<?php echo $langId; ?>/CH/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal, le r�flexe s�curit� pour payer en ligne">
    <img alt="" border="0" src="https://www.paypalobjects.com/<?php echo $langId; ?>/i/scr/pixel.gif" width="1" height="1">
</form>



<?php
include "tableau.age.junior.inc.php";
?>

