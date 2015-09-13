<?php
    $image = false;

	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '3' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        if(!$image) {
            echo "<img class='imageFlottanteDroite' src=".VAR_IMAGE_FEDERATION_JUNIORS." />";
            $image = true;
        }
        echo "<p>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
        echo "</p>";
    }
    include "tableau.age.junior.inc.php";
?>

