<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '8' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == 0) {
        echo "<h2>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h2>";
        echo "<img width='370px;' class='imageFlottanteDroite' src='" . VAR_IMAGE_FORMATION_GESTIONNAIRE_CLUB . "'>";
    } elseif ($donnees['paragrapheNum'] == 2) {
        echo "<h3>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h3>";
    } else {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
}
showFunctionPerson(1);
