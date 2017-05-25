<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '30' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    // entete
    if ($donnees["paragrapheNum"] == 0) {
        echo "<h4>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h4>";
    } else {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
}
echo "<p><a href='/telechargements/3'>Documents officiels</a></p>";
