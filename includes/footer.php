<footer>
	<div id="socialButtons">
        <a href="https://twitter.com/SwissTchoukball" class="twitter-follow-button" data-show-count="false" data-lang="<? echo strtolower($_SESSION["__langue__"]); ?>">Suivre @SwissTchoukball</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        <fb:like href="http://www.facebook.com/SwissTchoukball" send="false" layout="button_count" width="100" show_faces="false"></fb:like>
		<div class="g-follow" data-annotation="none" data-height="20" data-href="//plus.google.com/118036642572546838455" data-rel="publisher"></div>
        <!-- <a class="googlePlusLink" href="https://www.google.com/+TchoukballCH" rel="publisher">Google+</a> --> <!-- Necessary to have our URL validated on the page -->
    </div>

	<div id="adminLinks">
    <?
	if ($_SESSION["__userLevel__"] < 100) {
		echo "<a href='/logout'>".VAR_LANG_DECONNEXION."</a>";

		if ($admin) {
			echo " &loz; <a href='".VAR_HREF_PAGE_PRINCIPALE."'>".VAR_LANG_ACCUEIL."</a>";
		} else {
			echo " &loz; <a href='/admin'>".VAR_LANG_ADMINISTRATION."</a>";
		}
	} else {
		echo "<a href='/login'>".VAR_LANG_SE_LOGUER."</a>";
	}
    ?>
	</div>

    <div id="footerLinks">
        <a href="/contact">Contact</a>
        &loz; <a href="/impressum">Impressum</a>
        &loz; <a href='rss<? echo $_SESSION[__langue__].".php"; ?>'>RSS</a>
        &loz; <?php echo VAR_LANG_ASSOCIATION_NAME; ?>, 1000 Lausanne
    </div>
    <div id="copyright">
        Copyright &copy; <? echo date('Y'); ?> <?php echo VAR_LANG_ASSOCIATION_NAME; ?>, tous droits r�serv�s
    </div>
</footer>