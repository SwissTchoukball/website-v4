<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);
?>
<!--suppress ALL -->
<div id="pageContact">

    <div class="typeContact">Adresse postale</div>
    <div class="contenuContact">
        <?php echo VAR_LANG_ASSOCIATION_NAME; ?><br/>
        1000 Lausanne
    </div>
    <div class="typeContact">Demande d'information</div>
    <div class="contenuContact"><?php echo email("info@tchoukball.ch"); ?></div>
    <div class="typeContact">Pr�sidence</div>
    <div class="contenuContact"><?php echo email("presidence@tchoukball.ch"); ?></div>
    <div class="typeContact">Vice-pr�sidence</div>
    <div class="contenuContact"><?php echo email("vice-presidence@tchoukball.ch"); ?></div>
    <div class="typeContact">Comit�</div>
    <div class="contenuContact"><a href="/comite-executif">Page des membres du comit�</a></div>
    <div class="typeContact">Commissions</div>
    <div class="contenuContact"><a href="/commissions">Page des commissions</a></div>
    <div class="typeContact">Responsable Communication</div>
    <div class="contenuContact"><?php echo email("communication@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable Sponsoring</div>
    <div class="contenuContact"><?php echo email("sponsoring@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable Comp�titions</div>
    <div class="contenuContact"><?php echo email("competitions@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable Arbitrage</div>
    <div class="contenuContact"><?php echo email("arbitrage@tchoukball.ch"); ?></div>
    <div class="typeContact">Juniors</div>
    <div class="contenuContact"><?php echo email("juniors@tchoukball.ch"); ?></div>
    <div class="typeContact" title="Photo et vid�o">Multim�dia</div>
    <div class="contenuContact"><?php echo email("multimedia@tchoukball.ch"); ?></div>
    <div class="typeContact">R�daction du tchouk<sup>up</sup></div>
    <div class="contenuContact"><?php echo email("redaction@tchoukball.ch"); ?></div>
    <div class="typeContact">Webmaster</div>
    <div class="contenuContact"><?php echo email("webmaster@tchoukball.ch"); ?></div>
</div>
