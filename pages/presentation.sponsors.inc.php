<?php
statInsererPageSurf(__FILE__);
?>
<div class="introSponsors">
    <?php
    $retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '11' ORDER BY paragrapheNum");
    // affiche le texte
    while ($donnees = mysql_fetch_array($retour)) {
        echo "<p>";
        afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</p>";
    }
    ?>
</div>

<?php
$retour = mysql_query("SELECT * FROM TypeSponsors WHERE visible = 1 ORDER BY id DESC");
echo '<div class="sponsor-list">';
while ($donnees = mysql_fetch_array($retour)) {
    echo "<h4>";
    afficherAvecEncryptageEmail($donnees["nomType" . $_SESSION["__langue__"]]);
    echo "</h4>";
    $retour2 = mysql_query("SELECT * FROM Sponsors WHERE idTypeSponsors = '" . $donnees["id"] . "' ORDER BY ordre");
    $i = 0;
    while ($donnees2 = mysql_fetch_array($retour2)) {
        echo '<div class="sponsor">';
        echo "<div class='sponsor__logo'><a target='_blank' href='" . $donnees2["lienWeb"] . "'><img src='" . $donnees2["lienLogo"] . "' border='0' /></a></div>";
        echo "<p>";
        afficherAvecEncryptageEmail($donnees2["description" . $_SESSION["__langue__"]]);
        echo "</p>";
        echo '</div>';
        $i++;
    }
}
echo '</div>';

?>
