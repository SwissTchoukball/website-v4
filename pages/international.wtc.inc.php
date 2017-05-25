<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}
statInsererPageSurf(__FILE__);
?>
<div class="presentation">
    <?php /*
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '33' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
            // entete
            if($donnees["paragrapheNum"] == 0) {
                echo "<h4>";
                afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</h4>";
            }
            else{
                echo "<p>";
                afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</p>";
            }
    }
	*/
    ?>
</div>
<h2>Résultats Suisses</h2>
<h3 class="alt">Hommes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>4èmes</td>
        <td>Salle</td>
        <td>Ferrara, 2011</td>
    </tr>
    <tr>
        <td>3èmes et 5èmes</td>
        <td>Sable</td>
        <td>Genève, 2005</td>
    </tr>
    <tr>
        <td>1ers</td>
        <td>Salle</td>
        <td>Kaohsiung, 2004</td>
    </tr>
    <tr>
        <td>2èmes</td>
        <td>Salle</td>
        <td>Loughborough, 2002</td>
    </tr>
    <tr>
        <td>3èmes</td>
        <td>Salle</td>
        <td>Genève, 2000</td>
    </tr>
    <tr>
        <td>5èmes</td>
        <td>Salle</td>
        <td>Portsmouth, 1990</td>
    </tr>
    <tr>
        <td>5èmes</td>
        <td>Salle</td>
        <td>Neuchâtel, 1987</td>
    </tr>
    <tr>
        <td>7èmes</td>
        <td>Salle</td>
        <td>Taipei, 1984</td>
    </tr>
</table>
<h3 class="alt">Femmes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>2èmes</td>
        <td>Salle</td>
        <td>Ferrara, 2011</td>
    </tr>
    <tr>
        <td>3èmes et 4èmes</td>
        <td>Sable</td>
        <td>Genève, 2005</td>
    </tr>
    <tr>
        <td>2èmes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2004</td>
    </tr>
    <tr>
        <td>2èmes</td>
        <td>Salle</td>
        <td>Loughborough, 2002</td>
    </tr>
    <tr>
        <td>3èmes</td>
        <td>Salle</td>
        <td>Genève, 2000</td>
    </tr>
    <tr>
        <td>4èmes</td>
        <td>Salle</td>
        <td>Portsmouth, 1990</td>
    </tr>
    <tr>
        <td>4èmes</td>
        <td>Salle</td>
        <td>Neuchâtel, 1987</td>
    </tr>
</table>
<h3 class="alt">M18 Hommes ou M18 Mixte</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>3èmes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2013</td>
    </tr>
    <tr>
        <td>2èmes</td>
        <td>Salle</td>
        <td>Traiskirchen, 2011</td>
    </tr>
    <tr>
        <td>3èmes et 4èmes</td>
        <td>Sable</td>
        <td>Genève, 2005</td>
    </tr>
</table>
<h3 class="alt">M18 Femmes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>3èmes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2013</td>
    </tr>
    <tr>
        <td>1ères</td>
        <td>Sable</td>
        <td>Genève, 2005</td>
    </tr>
</table>
<h3 class="alt">M15 Hommes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>Édition</th>
    </tr>
    <tr>
        <td>4èmes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2013</td>
    </tr>
</table>
