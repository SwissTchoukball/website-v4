<?
if($_SESSION["debug_tracage"])echo __FILE__."";
statInsererPageSurf(__FILE__);
?>
<section>
<?

	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '14' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        if ($donnees['paragrapheNum'] == 0) {
            echo "<h3>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</h3>";
        }
        elseif ($donnees['paragrapheNum'] == 6 OR $donnees['paragrapheNum'] == 5){
        	//Formulaire de d�sinscription d�sormais � part
        }
        elseif ($donnees['paragrapheNum'] == 3){
            echo "<h5>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</h5>";
        }
        elseif ($donnees['paragrapheNum']%2!=0) {
            echo "<h4>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</h4>";
        }
        else {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</p>";
        }
        if ($donnees['paragrapheNum'] == 0) {
            // image
            echo "<p class='center'><img src=".VAR_IMG_NEWS_LETTER_PICT." /></p>";
        }
        if ($donnees['paragrapheNum'] == 4) {
            // formulaire inscription
            ?>
            <form class="newsletterForm" method="post" action="http://newsletter.sharedbox.com/user/process.php?sExternalid=dcb9e3937752002925deb32307becae3" name="signup" accept-charset="utf-8">
            <label for="email"><strong>Votre Email:</strong></label>
			<input type="text" name="Email" id="email" maxlength="60" size="40" />
            <input type="hidden" name="pommo_signup" value="true" />
            <input type='submit' value='<? echo VAR_LANG_INSCRIPTION;?>'>
            </form>
            <?
        }
    }
$affichage_twitter=true;
?>
</section>

