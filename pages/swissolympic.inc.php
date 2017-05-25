<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '10' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == 0) {
        echo "<h2>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h2>";
        echo "<p class='center'><a href='http://www.swissolympic.ch' target='_blank'><img src='" . VAR_IMAGE_LOGO_SWISSOLYMPIC . "' alt='Logo Swiss Olympic'border='0'></a></p>";
    } else {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
}
showFunctionPerson(5);
