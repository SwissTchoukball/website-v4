<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);
?>
<div class="presentation">
    <?php
    $retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '34' ORDER BY paragrapheNum");
    // affiche le texte
    while ($donnees = mysql_fetch_array($retour)) {
        // entete
        if ($donnees["paragrapheNum"] == 0) {
            echo "<h2>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</h2>";
        } else {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
            echo "</p>";
        }
    }

    ?>
</div>
<img src="pictures/international_championnat_europe.jpg" alt="Championat d'Europe"/>
<h2>Résultats Suisses</h2>
<!-- TODO: Save this data in the database instead of being hard-coded -->
<h3 class="alt">Hommes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>5èmes</td>
        <td>Ji&ccaron;ín (CZ), 2016</td>
    </tr>
    <tr>
        <td>3èmes</td>
        <td>Radevormwald (DE), 2014</td>
    </tr>
    <tr>
        <td>1ers</td>
        <td>Hereford (UK), 2010</td>
    </tr>
    <tr>
        <td>1ers</td>
        <td>Usti (CZ), 2008</td>
    </tr>
    <tr>
        <td>2èmes</td>
        <td>Macolin (CH), 2006</td>
    </tr>
    <tr>
        <td>1ers</td>
        <td>Rimini (IT), 2003</td>
    </tr>
    <tr>
        <td>2èmes et 4èmes</td>
        <td>Genève (CH), 2001</td>
    </tr>
</table>
<h3 class="alt">Femmes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Ji&ccaron;ín (CZ), 2016</td>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Radevormwald (DE), 2014</td>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Hereford (UK), 2010</td>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Usti (CZ), 2008</td>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Macolin (CH), 2006</td>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Rimini (IT), 2003</td>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Genève (CH), 2001</td>
    </tr>
</table>
<h3 class="alt">M18</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>2èmes</td>
        <td>Ji&ccaron;ín (CZ), 2016</td>
    </tr>
    <tr>
        <td>1ers</td>
        <td>Usti (CZ), 2008</td>
    </tr>
    <tr>
        <td>2èmes et 4èmes</td>
        <td>Macolin (CH), 2006</td>
    </tr>
</table>
