<header>
	<nav id="language-switcher">
		<ul>
			<?php
				for($i=0;$i<count($VAR_TABLEAU_DES_LANGUES);$i++){
					if($_SESSION["__langue__"]==$VAR_TABLEAU_DES_LANGUES[$i][0]){
						echo '<li>'.$VAR_TABLEAU_DES_LANGUES[$i][1].'</li>';
					}
					else{
						echo '<li><a class="menu" href="/switchlang/' . $VAR_TABLEAU_DES_LANGUES[$i][0] . '/' . $_GET['lien'] . '">' . $VAR_TABLEAU_DES_LANGUES[$i][1] . '</a></li>';
					}
				}
			?>
		</ul>
	</nav>
    <a href="<?php echo PATH_TO_ROOT; ?>" id="banner"></a>
</header>
