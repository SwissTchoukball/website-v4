<?
if($_SESSION["debug_tracage"])echo __FILE__."<BR>";
statInsererPageSurf(__FILE__);
?>
<script src="http://live.tchoukballworld.net/components/event-11/results"></script>
<div>
	<?	 	    
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '35' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
            // entete
            if($donnees["paragrapheNum"] == 0) {
                echo "<h4>";
                echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</h4>";
            }
            else{
                echo "<p>";
                echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</p>";
            }
    }	 
	
	?>
</div>

<div id="tbw_etc_results">
    Results should be displayed here.
</div>

<script>
    $('#tbw_etc_results').tbwResults();
</script>