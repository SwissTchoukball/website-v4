<?php
	statInsererPageSurf(__FILE__);
?>
<div class="charte">
<?php
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '5' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        if ($donnees['paragrapheNum'] == 0) {
            echo "<h2>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</h2>";
        }
        elseif ($donnees['paragrapheNum'] == 3) {
            echo "<blockquote>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</blockquote>";
        }
        else {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</p>";
        }
	}

?>
</div>
