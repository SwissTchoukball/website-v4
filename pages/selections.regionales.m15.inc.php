<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);

echo '<div>' . getSimplePageContent(47) . '</div>';

showFunctionPerson(12);
showFunctionPerson(13);
showCommissionHead(9);
