<?php
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '23' ORDER BY paragrapheNum");
    // affiche le texte
    $i=0;
    $j=0;
    while($donnees = mysql_fetch_array($retour)) {

        $i++;
        if($donnees['paragrapheNum'] == 0) {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</p><br />";
        }
        elseif($donnees['paragrapheNum'] == 1) {
            echo "<p class='center'><img src='".VAR_IMAGE_PRESSE_TSR."' alt='".$donnees["paragraphe".$_SESSION["__langue__"]]."' /><br />";
            echo "<dfn>".$donnees["paragraphe".$_SESSION["__langue__"]]."</dfn></p><br />";
        }
        elseif($j%2==0) {
            if($i%2==1) {
                echo "<div class='citationPresseGauche'>";
                echo "<q>";
                echo $donnees["paragraphe".$_SESSION["__langue__"]]."</q><br />";
            }
            else {
                echo "<cite>";
                echo $donnees["paragraphe".$_SESSION["__langue__"]];
                echo "</cite>";
                echo "</div><br />";
                $j++;
            }
        }
        else {
            if($i%2==1) {
                echo "<div class='citationPresseDroite'>";
                echo "<q>";
                echo $donnees["paragraphe".$_SESSION["__langue__"]]."</q><br />";
            }
            else {
                echo "<cite>";
                echo $donnees["paragraphe".$_SESSION["__langue__"]];
                echo "</cite>";
                echo "</div><br />";
                $j++;
            }
        }
    }
?>


