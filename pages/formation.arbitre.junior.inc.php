<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);
?>
<div class="arbitreJunior">
    <?php
    $retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '15' ORDER BY paragrapheNum");
    // affiche le texte
    while ($donnees = mysql_fetch_array($retour)) {
        if ($donnees['paragrapheNum'] == 0) {
            echo "<h3>";
            afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</h3>";
            echo "<p class='center'><img src='" . VAR_IMAGE_FORMATION_ARBITRE_ENSEMBLE . "' alt='formation arbitre junior' /></p><br />";
            echo "<p>";

        } elseif ($donnees['paragrapheNum'] == 3) {
            echo "<p>";
            echo "<ul>";
            echo "<li>";
            afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</li>";
        } elseif ($donnees['paragrapheNum'] == 4) {
            echo "<li>";
            afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</li>";
            echo "</ul>";
            echo "</p>";
        } else {
            echo "<p>";
            afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</p>";
        }
        if ($donnees['paragrapheNum'] == 1) {
            echo "<br /><p class='center'><img src='" . VAR_IMAGE_FORMATION_ARBITRE_FEEDBACK . "' alt='formation arbitre junior' /></p><br />";
        }
    }
    showCommissionHead(2);
    ?>
</div>
