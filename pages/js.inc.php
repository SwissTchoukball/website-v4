<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '9' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    $paragraphNum = $donnees['paragrapheNum'];
    $paragraphText = encryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
    if ($paragraphNum == 0) {
        echo "<h2>" . $paragraphText . "</h2>";
    } elseif ($paragraphNum == 2 || $paragraphNum == 4 || $paragraphNum == 7 || $paragraphNum == 9 || $paragraphNum == 11) {
        echo "<h3>" . $paragraphText . "</h3>";
    } else {
        echo "<p>" . $paragraphText . "</p>";
    }

    if ($paragraphNum == 2) {
        echo "<img width='380px' class='imageFlottanteDroite' src='" . VAR_IMAGE_FORMATION_JS . "' />";
    }
}
showFunctionPerson(2);
?>
