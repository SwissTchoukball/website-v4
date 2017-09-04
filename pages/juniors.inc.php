<?php
$image = false;

echo "<img class='imageFlottanteDroite' src=" . VAR_IMAGE_FEDERATION_JUNIORS . " />";
echo '<div>' . getSimplePageContent(17) . '</div>';

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
    <input type="image" src="https://www.paypalobjects.com/<?php echo $langId; ?>/CH/i/btn/btn_donateCC_LG.gif"
           border="0" name="submit" alt="PayPal, le réflexe sécurité pour payer en ligne">
    <img alt="" border="0" src="https://www.paypalobjects.com/<?php echo $langId; ?>/i/scr/pixel.gif" width="1"
         height="1">
</form>


<?php
include "tableau.age.junior.inc.php";
?>

