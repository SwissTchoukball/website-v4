<?
if($_SESSION["debug_tracage"])echo __FILE__."<BR>";
statInsererPageSurf(__FILE__);
?>
<div id="pageContact">

	<div class="typeContact">Adresse postale</div>
	<div class="contenuContact">
		<?php echo VAR_LANG_ASSOCIATION_NAME; ?><br />
		1000 Lausanne
	</div>
	<div class="typeContact">Demande d'information</div>
	<div class="contenuContact"><? email("info@tchoukball.ch"); ?></div>
	<div class="typeContact">Présidence</div>
	<div class="contenuContact"><? email("presidence@tchoukball.ch"); ?></div>
	<div class="typeContact">Vice-présidence</div>
	<div class="contenuContact"><? email("vice-presidence@tchoukball.ch"); ?></div>
	<div class="typeContact">Comité</div>
	<div class="contenuContact"><a href="/comiteFSTB">Page des membres du comité</a></div>
	<div class="typeContact">Commissions</div>
	<div class="contenuContact"><a href="/commissions">Page des commissions</a></div>
	<div class="typeContact" title="Contact Presse">Communication</div>
	<div class="contenuContact"><? email("communication@tchoukball.ch"); ?></div>
	<div class="typeContact">Sponsoring</div>
	<div class="contenuContact"><? email("sponsoring@tchoukball.ch"); ?></div>
	<div class="typeContact" title="Compétitions et Arbitrage">Technique</div>
	<div class="contenuContact"><? email("technique@tchoukball.ch"); ?></div>
	<div class="typeContact">Responsable Championnat</div>
	<div class="contenuContact"><? email("resp.championnat@tchoukball.ch"); ?></div>
	<div class="typeContact">Responsable Coupe Suisse</div>
	<div class="contenuContact"><? email("resp.coupesuisse@tchoukball.ch"); ?></div>
	<div class="typeContact">Responsable Arbitrage</div>
	<div class="contenuContact"><? email("resp.arbitrage@tchoukball.ch"); ?></div>
	<div class="typeContact">Juniors</div>
	<div class="contenuContact"><? email("juniors@tchoukball.ch"); ?></div>
	<div class="typeContact" title="Photo et vidéo">Multimédia</div>
	<div class="contenuContact"><? email("multimedia@tchoukball.ch"); ?></div>
	<div class="typeContact">Rédaction du tchouk<sup>up</sup></div>
	<div class="contenuContact"><? email("redaction@tchoukball.ch"); ?></div>
	<div class="typeContact">Webmaster</div>
	<div class="contenuContact"><? email("webmaster@tchoukball.ch"); ?></div>
</div>