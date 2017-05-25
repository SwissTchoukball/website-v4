<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '22' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == 1 || $donnees['paragrapheNum'] == 3 || $donnees['paragrapheNum'] == 4) {
        echo "<h4>";
        echo $donnees["paragraphe" . $_SESSION["__langue__"]];
        echo "</h4>";
        if ($donnees['paragrapheNum'] == 4) {
            echo "<br />";
            echo "Email" . " : ";
            echo emailperso('webmaster@tchoukball.ch', 'webmaster@tchoukball.ch', 'Contact depuis Tchoukball.ch');
            echo "<br />";
        }
    } else {
        echo "<p>";
        echo $donnees["paragraphe" . $_SESSION["__langue__"]];
        echo "</p>";
    }
}
showDomainHead(6);
