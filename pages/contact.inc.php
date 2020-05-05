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
    <div class="typeContact">Responsable communication</div>
    <div class="contenuContact"><?php echo email("communication@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable sponsoring</div>
    <div class="contenuContact"><?php echo email("sponsoring@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable technique</div>
    <div class="contenuContact"><?php echo email("technique@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable Championnat suisse</div>
    <div class="contenuContact"><?php echo email("championnat@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable Coupe suisse</div>
    <div class="contenuContact"><?php echo email("coupesuisse@tchoukball.ch"); ?></div>
    <div class="typeContact">Responsable arbitrage</div>
    <div class="contenuContact"><?php echo email("arbitrage@tchoukball.ch"); ?></div>
    <div class="typeContact">Multim�dia</div>
    <div class="contenuContact"><?php echo email("multimedia@tchoukball.ch"); ?></div>
    <div class="typeContact">R�daction du Tchouk<sup>up</sup></div>
    <div class="contenuContact"><?php echo email("redaction@tchoukball.ch"); ?></div>
    <div class="typeContact">Administrateur</div>
    <div class="contenuContact"><?php echo email("admin@tchoukball.ch"); ?></div>
</div>
