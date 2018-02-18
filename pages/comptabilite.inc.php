<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);

showDomainHead(9);
?>
<h2>Numéro CCP</h2>
<p class="ccp-number"><?php echo VAR_CCP_ASSOCIATION; ?></p>