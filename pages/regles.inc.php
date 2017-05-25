<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '1' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == 0 || $donnees['paragrapheNum'] == 2 || $donnees['paragrapheNum'] == 4) {
        echo "<h2>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h2>";
    } else {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
    if ($donnees['paragrapheNum'] == 1) {
        echo "<p align='center'><img src='" . VAR_IMAGE_RELGES_POINT_MARQUE . "'></p>";
    }
    if ($donnees['paragrapheNum'] == 3) {
        echo "<p align='center'><img src='" . VAR_IMAGE_RELGES_POINT_PERDU . "'></p>";
    }
    if ($donnees['paragrapheNum'] == 5) {
        echo "<p align='center'><img src='" . VAR_IMAGE_RELGES_FAUTE_1 . "'></p>";
    }
    if ($donnees['paragrapheNum'] == 6) {
        echo "<p align='center'><img src='" . VAR_IMAGE_RELGES_FAUTE_2 . "'></p>";
    }
}
