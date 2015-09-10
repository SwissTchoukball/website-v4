<?php
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '36' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        echo markdown($donnees["paragraphe".$_SESSION["__langue__"]]);
    }
    showFunctionPerson(7);
?>

