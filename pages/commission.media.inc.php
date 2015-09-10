<?php
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '26' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        if ($donnees['paragrapheNum'] == 2) {
            echo "<h2>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</h2>";
        }
        elseif ($donnees['paragrapheNum'] == 3 || $donnees['paragrapheNum'] == 5|| $donnees['paragrapheNum'] == 7) {
            echo "<h5>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</h5>";
        }
        else {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</p>";
        }
    }

    showDomainHead(8);
?>


