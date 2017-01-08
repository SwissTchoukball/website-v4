<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "";
}
statInsererPageSurf(__FILE__);
?>
<section>
    <?php

    $retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '14' ORDER BY paragrapheNum");
    // affiche le texte
    while ($donnees = mysql_fetch_array($retour)) {
        if ($donnees['paragrapheNum'] == 0) {
            echo "<h3>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</h3>";
        } elseif ($donnees['paragrapheNum'] == 6 OR $donnees['paragrapheNum'] == 5) {
            //Formulaire de désinscription désormais à part
        } elseif ($donnees['paragrapheNum'] == 3) {
            echo "<h5>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</h5>";
        } elseif ($donnees['paragrapheNum'] % 2 != 0) {
            echo "<h4>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</h4>";
        } else {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</p>";
        }
        if ($donnees['paragrapheNum'] == 0) {
            // image
            echo "<p class='center'><img src=" . VAR_IMG_NEWS_LETTER_PICT . " /></p>";
        }
        if ($donnees['paragrapheNum'] == 4) {
            // formulaire inscription
            ?>
            <div class="newsletterForm">
                <script type="text/javascript"
                        src="https://newsletter.infomaniak.com/external/webform-script/eyJpdiI6Ik1xcUhhbHFBeFwvNzBmaDI1dEpjdUd1T282ZGtHMjJQd0FGK2ZVOTlSN2FvPSIsInZhbHVlIjoic3BuMGFHR2VOMTZBNkdkY205azFyMTJjbHpBb0VNc2diQllyazU5QlVTOD0iLCJtYWMiOiIyYTNhOWEzODM0MzAxMDM5MTU5NjFhMWRjN2Q0OWU3Mzc5MDEzZjU1YWMyYjU3MjU2NTZlM2QwZTkwNjA4YjIzIn0="></script>
            </div>

            <?php
        }
    }
    $affichage_twitter = true;
    ?>
</section>

