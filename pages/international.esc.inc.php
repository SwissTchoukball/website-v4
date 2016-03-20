<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '37' ORDER BY paragrapheNum");

// Mettre le logo, quand un logo officiel sera validé.
// echo "<img class='imageFlottanteDroite' src='pictures/logos/logo_EWC15.png' />";

while($donnees = mysql_fetch_array($retour)) {
    echo "<p>";
    echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
    echo "</p><br />";
}

?>
<h2>Résultats suisses</h2>
<h3 class="alt">Le Havre (FR) 2016</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Équipe</th>
    </tr>
    <tr>
        <td>2ème</td>
        <td>Piranyon Origin</td>
    </tr>
</table>
<h3 class="alt">Turin (IT) 2015</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Équipe</th>
    </tr>
    <tr>
        <td>1er</td>
        <td>Val-de-Ruz Flyers</td>
    </tr>
    <tr>
        <td>2ème</td>
        <td>Geneva Eagles</td>
    </tr>
</table>
