<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '7' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == 0) {
        echo "<h2>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h2>";
        echo "<img class='imageFlottanteDroite' src='" . VAR_IMAGE_FORMATION_ARBITRE . "' alt='formation arbitre' />";
    } else {
        echo "<p>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
}
showCommissionHead(2);
?>

