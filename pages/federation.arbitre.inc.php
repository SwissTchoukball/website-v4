<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '6' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == -1) {
        echo "<h2>";
        echo $donnees["paragraphe" . $_SESSION["__langue__"]];
        echo "</h2>";
        echo "<p class='center'><img src='" . VAR_IMAGE_BANNIERE_ARBITRE . "' width='500' height='120' /></p><br />";
    } elseif ($donnees['paragrapheNum'] == 0) {
        echo "<h3>";
        echo $donnees["paragraphe" . $_SESSION["__langue__"]];
        echo "</h3>";
    } else {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
}
showCommissionHead(2);
