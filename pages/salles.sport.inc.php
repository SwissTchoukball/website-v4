<?
if($_SESSION["debug_tracage"])echo __FILE__."<BR>";
statInsererPageSurf(__FILE__);
?>
<div class="juniors">
<?
    
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '33' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        echo "<p>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
        echo "</p>";
    }
?>
</div>

