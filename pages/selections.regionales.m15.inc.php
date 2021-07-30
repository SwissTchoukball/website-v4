<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);

echo '<div>' . getSimplePageContent(117) . '</div>';

showFunctionPerson(12);
showFunctionPerson(13);
showCommissionHead(9);
