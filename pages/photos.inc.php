<?
	statInsererPageSurf(__FILE__);
?>
<div class="historique">
<?
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '27' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        if ($donnees['paragrapheNum'] == 0 || $donnees['paragrapheNum'] == 2) {
            echo "<h4>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</h4>";
        }
        else {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</p>";
        }
    }

?>
</div>