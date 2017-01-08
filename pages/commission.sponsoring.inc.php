<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '12' ORDER BY paragrapheNum");
// affiche le texte
echo "<img src='" . VAR_IMAGE_PHOTO_SPONSORING . "' class='imageFlottanteDroite' />";
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == 4) {
        echo "<p><ol><li>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</li>";
    } elseif ($donnees['paragrapheNum'] == 5) {
        echo "<li>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</li></ol></p>";

    } else {
        echo "<p>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
}

showCommissionHead(17);
?>