<?php
$NB_NEWS_AFFICHEES = 6;
$TAILLE_NEWS = 300;

$aujourdhui = date_actuelle();
$AnneePassee = substr($aujourdhui, 0, 4) - 1;
$YaUneAnnee = $AnneePassee . substr($aujourdhui, 4, 6);

$newsIdSelection = is_numeric($_GET['newsIdSelection']) ? $_GET['newsIdSelection'] : '';

// developpement d'une seule news ?
if ($newsIdSelection != "") {
    $requeteSelect = "SELECT * FROM `News` WHERE `id`='" . $newsIdSelection . "'";
} else {

    if (isset($_GET['limitinf']) and is_numeric($_GET['limitinf'])) {
        $limitinf = $_GET['limitinf'];
    }
    else {
        $limitinf = 0;
    }

    if (isset($_GET['limitsup']) and is_numeric($_GET['limitsup'])) {
        $limitsup = $_GET['limitsup'];
    }
    else {
        $limitsup = $NB_NEWS_AFFICHEES;
    }

    $requeteSelect = "SELECT * FROM `News` WHERE published = 1 ORDER BY premiereNews DESC, `date` DESC LIMIT $limitinf, $limitsup";
}
//$recordset = mysql_query($requeteSelect) or die ("<H1>mauvaise requete</H1>");
$recordset = @mysql_query($requeteSelect);

if ($newsIdSelection != "") {
    if (mysql_num_rows($recordset) == 0) {
        printMessage(VAR_LANG_NEWS_NON_TROUVEE);
        $requeteSelect = "SELECT * FROM `News` WHERE `date` >= '" . $YaUneAnnee . "' AND `published` = 1 ORDER BY premiereNews DESC, `date` DESC LIMIT 6";
    } else {
        $record = mysql_fetch_array($recordset);
        $date = date_sql2date($record["date"]);
        $titre = $record['titre' . $_SESSION["__langue__"]];
        $corps = $record['corps' . $_SESSION["__langue__"]];
        if ($titre == "") {
            $titre = $record['titre' . $VAR_TABLEAU_DES_LANGUES[0][0]];
        }
        if ($corps == "") {
            $corps = $record['corps' . $VAR_TABLEAU_DES_LANGUES[0][0]];
        }

        if (!$record['published'] && !isAdmin()) {
            printMessage(VAR_LANG_NEWS_NON_TROUVEE);
        } else {
            echo "<h2>" . formatterTextEnHTML($titre) . "</h2>";
            echo "<div class='news_body'>";
            $positionImage = "class='imageFlottanteDroite'";
            if ($record['image'] != 0) { // On affiche l'image si il y en a une.
                $retour = mysql_query("SELECT * FROM Uploads WHERE id='" . $record['image'] . "'");
                $donnees = mysql_fetch_array($retour);
                echo "<img src='https://tchoukball.ch/uploads/" . $donnees['fichier'] . "' alt='" . $donnees['titre'] . "' " . $positionImage . " />";
            }
            //afficherAvecEncryptageEmail($corps);
            echo markdown($corps);
            echo "<p class='date'>Post&eacute; " . date_sql2date_joli($record["date"], "le", "Fr") . "</p>";
            echo "</div>";
            ?>
            <div class="socialButtons">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="https://tchoukball.ch/news/<?php echo $newsIdSelection; ?>"
                   data-text="<?php echo strip_tags($titre); ?>" data-count="none" data-via="SwissTchoukball"
                   data-lang="fr">Tweet</a>
                <script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
                <div class="fb-share-button"
                     data-href="https://tchoukball.ch/news/<?php echo $newsIdSelection; ?>"
                     data-layout="button"
                     data-size="small"
                     data-mobile-iframe="true">
                    <a class="fb-xfbml-parse-ignore"
                       target="_blank"
                       href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">
                        Share
                    </a>
                </div>
            </div>
            <?php
        }
    }


    echo "<a href='/news'><button class='button button--cancel'>" . VAR_LANG_NEWS_BACK_TO_NEWS . "</button></a>";
} else {
    $nbNews = 0;
    // afficher le resultat de la requete
    while ($record = mysql_fetch_array($recordset)) {
        $date = date_sql2date($record["date"]);
        $titre = $record['titre' . $_SESSION["__langue__"]];
        $corps = $record['corps' . $_SESSION["__langue__"]];
        if ($titre == "") {
            $titre = $record['titre' . $VAR_TABLEAU_DES_LANGUES[0][0]];
        }
        if ($corps == "") {
            $corps = $record['corps' . $VAR_TABLEAU_DES_LANGUES[0][0]];
        }
        echo "<h2 class='alt'>" . formatterTextEnHTML($titre) . "</h2><br />";
        echo "<div class='news_body'>";
        echo truncateHtml(markdown($corps), $TAILLE_NEWS,
                "... ");
        echo "<p><a href='/news/" . $record['id'] . "'>" . VAR_LANG_LIRE_SUITE_ARTICLE . "</a></p>";
        echo "<p class='news-date'>Post&eacute; " . date_sql2date_joli($record["date"], "le", "Fr", false) . "</p>";
        echo "</div>";
        $nbNews++;
    }
}

// bas de page
if ($newsIdSelection == "") {

    $requeteSelect = "SELECT * FROM `News` WHERE published = 1 ORDER BY premiereNews DESC, `date` DESC";
    $recordset = @mysql_query($requeteSelect);

    //&& $limitsup>0 && $limitinf>0
    if ($limitsup < mysql_num_rows($recordset) && $limitinf >= 0 && $limitsup > $limitinf) {
        echo "<p class='newsPrecedentes'><a href='" . VAR_HREF_PAGE_PRINCIPALE . "?menuselection=1&smenuselection=1&limitinf=" . ($limitinf + $NB_NEWS_AFFICHEES) . "&limitsup=" . ($limitsup + $NB_NEWS_AFFICHEES) . "'>&lt;&lt;&nbsp;" . VAR_LANG_NEWS_PRECEDENTES . "</a></p>";
    } else {
        echo "<p>&nbsp;</p>";
    }
    if ($limitinf >= $NB_NEWS_AFFICHEES && $limitinf >= 0 && $limitsup > $limitinf) {
        echo "<p class='newsSuivantes'><a href='" . VAR_HREF_PAGE_PRINCIPALE . "?menuselection=1&smenuselection=1&limitinf=" . ($limitinf - $NB_NEWS_AFFICHEES) . "&limitsup=" . ($limitsup - $NB_NEWS_AFFICHEES) . "'>" . VAR_LANG_NEWS_SUIVANTES . "&nbsp;&gt;&gt;</a></p>";
    } else {
        echo "<p>&nbsp;</p>";
    }
}
?>
