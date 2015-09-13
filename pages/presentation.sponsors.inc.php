<?php
	statInsererPageSurf(__FILE__);
?>
<div class="introSponsors">
<?php
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '11' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        echo "<p>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
        echo "</p>";
    }
?>
</div>

<?php
	$retour = mysql_query("SELECT * FROM TypeSponsors ORDER BY id DESC");
    while($donnees = mysql_fetch_array($retour)) {
        echo "<div class='presentationSponsors'>";
        echo "<h4>";
        echo afficherAvecEncryptageEmail($donnees["nomType".$_SESSION["__langue__"]]);
        echo "</h4><br />";
        $retour2 = mysql_query("SELECT * FROM Sponsors WHERE idTypeSponsors = '".$donnees["id"]."' ORDER BY ordre");
        $i=0;
        while($donnees2 = mysql_fetch_array($retour2)) {
            if($i%2==0){
                $classImage = "imageFlottanteDroite";
            }
            else{
                $classImage = "imageFlottanteGauche";
            }
            echo "<div class='".$classImage."'><a target='_blank' href='".$donnees2["lienWeb"]."'><img src='".$donnees2["lienLogo"]."' border='0' /></a></div>";
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees2["description".$_SESSION["__langue__"]]);
            echo "</p><br />";
            echo "<p style='clear: right;'>&nbsp;</p>";
            $i++;
        }
        echo "</div><br />";
    }
?>
