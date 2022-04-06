<?php
echo "<p class='imageFlottanteDroite'><a href='/" . VAR_LANG_DOC_FLYER_PRESENTATION . "'><img src=" . VAR_IMAGE_FLYER_PRESENTATION . " border='0' ></a><br />";
echo "<a href='/" . VAR_LANG_DOC_FLYER_PRESENTATION . "'>" . VAR_LANG_DOC_NOM_LIEN_FLYER_PRESENTATION . "</a></p>";

$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '2' ORDER BY paragrapheNum");
// affiche le texte
$entete = 0;
while ($donnees = mysql_fetch_array($retour)) {
    // entete
    if ($donnees["paragrapheNum"] == 12) {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    } elseif ($entete % 2 == 0) {
        echo "<h2>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h2>";
    } else {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";

        if ($entete == 3) {
            echo "<p class='imageFlottanteDroite'><img src=" . VAR_IMAGE_TERRAIN_PRESENTATION . "></p>";

        }
    }
    $entete++;
}
?>
