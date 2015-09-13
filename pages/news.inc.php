<?php
$NB_NEWS_AFFICHEES=6;
$TAILLE_NEWS = 300;

$aujourdhui = date_actuelle();
$AnneePassee = substr($aujourdhui,0,4)-1;
$YaUneAnnee = $AnneePassee.substr($aujourdhui,4,6);

$newsIdSelection = is_numeric($_GET['newsIdSelection']) ? $_GET['newsIdSelection'] : '';

// developpement d'une seule news ?
if ($newsIdSelection != "") {
	$requeteSelect = "SELECT * FROM `News` WHERE `Id`='".$newsIdSelection."'";
} else{

	if($limitinf=="" || $limitsup==""){
		if($limitinf==""){
			$limitinf=0;
		}
		if($limitsup==""){
			$limitsup=$NB_NEWS_AFFICHEES;
		}
	}
	$requeteSelect = "SELECT * FROM `News` ORDER BY premiereNews DESC, `Date` DESC LIMIT $limitinf, $limitsup";
}
//$recordset = mysql_query($requeteSelect) or die ("<H1>mauvaise requete</H1>");
$recordset = @mysql_query($requeteSelect);

if ($newsIdSelection != "") {
	if(mysql_num_rows($recordset)==0){
		printMessage(VAR_LANG_NEWS_NON_TROUVEE);
		$requeteSelect = "SELECT * FROM `News` WHERE `Date` >= '".$YaUneAnnee."' ORDER BY premiereNews DESC, `Date` DESC LIMIT 6";
	}
	else{
		$record = mysql_fetch_array($recordset);
		$date = date_sql2date($record["date"]);
		$titre = $record['titre'.$_SESSION["__langue__"]];
		$corps = $record['corps'.$_SESSION["__langue__"]];
		if($titre==""){
			$titre = $record['titre'.$VAR_TABLEAU_DES_LANGUES[0][0]];
		}
		if($corps==""){
			$corps = $record['corps'.$VAR_TABLEAU_DES_LANGUES[0][0]];
		}
			echo "<h2 class='alt'>".$date.": ".formatterTextEnHTML($titre)."</h2>";
			echo "<div class='news_body'>";
				$positionImage = "class='imageFlottanteDroite'";
				if($record['image'] != 0){ // On affiche l'image si il y en a une.
				    $retour = mysql_query("SELECT * FROM Uploads WHERE id='".$record['image']."'");
				    $donnees = mysql_fetch_array($retour);
				    echo "<img src='http://www.tchoukball.ch/uploads/".$donnees['fichier']."' alt='".$donnees['titre']."' ".$positionImage." />";
				}
				//afficherAvecEncryptageEmail($corps);
				echo markdown($corps);
				echo "<br />";
				?>
    <a href="http://twitter.com/share" class="twitter-share-button" data-url="/news/<?php echo $newsIdSelection; ?>" data-text="FSTB : <?php echo strip_tags($titre); ?>" data-count="none" data-via="tchouksuisse" data-lang="fr">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <!--<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.tchoukball.ch%2Findex.php%3Fmenuselection%3D1%26smenuselection%3D1%26newsIdSelection%3D<?php echo $newsIdSelection; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;font=arial" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:150px; height:20px"></iframe>-->
    <fb:like href="http://www.tchoukball.ch/news/<?php echo $newsIdSelection; ?>" send="true" layout="button_count" width="450" show_faces="false" font="lucida grande"></fb:like>
				<?php
			echo "</div><br />";
	}


		echo "<p class='center'><a href='/news'>".VAR_LANG_NEWS_BACK_TO_NEWS."</a><p>";
}
else{
	$nbNews=0;
	// afficher le resultat de la requete
	while($record = mysql_fetch_array($recordset)){
		$date = date_sql2date($record["date"]);
		$titre = $record['titre'.$_SESSION["__langue__"]];
		$corps = $record['corps'.$_SESSION["__langue__"]];
		if($titre==""){
			$titre = $record['titre'.$VAR_TABLEAU_DES_LANGUES[0][0]];
		}
		if($corps==""){
			$corps = $record['corps'.$VAR_TABLEAU_DES_LANGUES[0][0]];
		}
			echo "<h2 class='alt'>".$date.": ".formatterTextEnHTML($titre)."</h2><br />";
			echo "<div class='news_body'>";
			echo truncateHtml(markdown($corps), $TAILLE_NEWS, "... ")."<p class='lireSuiteArticle'><a href='/news/".$record['id']."'>".VAR_LANG_LIRE_SUITE_ARTICLE."</a></p>";
			echo "</div>";
			$nbNews++;
	}
}

// bas de page
if($newsIdSelection==""){

	$requeteSelect = "SELECT * FROM `News` ORDER BY premiereNews DESC, `Date` DESC";
	$recordset = @mysql_query($requeteSelect);

	//&& $limitsup>0 && $limitinf>0
		if($limitsup < mysql_num_rows($recordset) && $limitinf>=0 && $limitsup>$limitinf ){
			echo "<p class='newsPrecedentes'><a href='".VAR_HREF_PAGE_PRINCIPALE."?menuselection=1&smenuselection=1&limitinf=".($limitinf+$NB_NEWS_AFFICHEES)."&limitsup=".($limitsup+$NB_NEWS_AFFICHEES)."'>&lt;&lt;&nbsp;".VAR_LANG_NEWS_PRECEDENTES."</a></p>";
		}
		else{
			echo "<p>&nbsp;</p>";
		}
		if($limitinf>=$NB_NEWS_AFFICHEES && $limitinf>=0 && $limitsup>$limitinf){
			echo "<p class='newsSuivantes'><a href='".VAR_HREF_PAGE_PRINCIPALE."?menuselection=1&smenuselection=1&limitinf=".($limitinf-$NB_NEWS_AFFICHEES)."&limitsup=".($limitsup-$NB_NEWS_AFFICHEES)."'>".VAR_LANG_NEWS_SUIVANTES."&nbsp;&gt;&gt;</a></p>";
		}
		else{
			echo "<p>&nbsp;</p>";
		}
}
?>
