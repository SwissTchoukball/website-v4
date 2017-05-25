<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);
?>
<div class="presentation">
    <?php
    $retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '31' ORDER BY paragrapheNum");
    // affiche le texte
    while ($donnees = mysql_fetch_array($retour)) {
        if ($donnees["paragrapheNum"] == 1) {
            echo "<a href='http://www.worldgames2009.tw/'><img src='pictures/logo-WG09-large.jpg' style='margin-left: 67px;' alt='logo World Games' /></a>";
            echo "<a href='http://phototchouk.com/photoTchouk/v/2007/20071108_WarmUP/PremJour/20071109_WarmUpPremJour_MCarnal_0026.jpg.html'><img class='imageFlottanteDroite' src='http://phototchouk.com/photoTchouk/d/32093-1/20071109_WarmUpPremJour_MCarnal_0026.jpg' alt='World Games Warm Up in 2007' width='400px' height='267px' title='Match Taiwan - Italie lors des Warm Up des World Games à Taiwan en 2007'/></a>";
        }
        if ($donnees["paragrapheNum"] == 2) {
        }
        // entete
        if ($donnees["paragrapheNum"] == 0) {
            echo "<h2>";
            afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</h2>";
        } else {
            echo "<p>";
            afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</p><br />";
        }
    }

    ?>
</div>
<h2>Résultats Suisses</h2>
<h3 class="alt">Hommes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>2ème</td>
        <td>Kaohsiung, 2009</td>
    </tr>
    <tr>
        <td>4ème</td>
        <td>Karlsruhe, 1989</td>
    </tr>
</table>
<h3 class="alt">Femmes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>2ème</td>
        <td>Kaohsiung, 2009</td>
    </tr>
</table>
