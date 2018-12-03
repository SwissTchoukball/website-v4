<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);

showDomainHead(9);
?>
<h2>Coordonnées bancaires</h2>
<h3>Numéro CCP</h3>
<p class="center">
    <span class="account-number">20-8957-2</span>
</p>
<h3>Numéro IBAN</h3>
<p class="center">
    <span class="account-number">CH13 0900 0000 2000 8957 2</span>
</p>
<h3>Adresse postale</h3>
<p>
    Swiss Tchoukball<br>
    1000 Lausanne
</p>