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
                echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</h4>";
            }
            else{
                echo "<p>";
                echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</p>";
            }
    }
	*/
    ?>
</div>
<h2>R�sultats Suisses</h2>
<h3 class="alt">Hommes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>�dition</th>
    </tr>
    <tr>
        <td>4�mes</td>
        <td>Salle</td>
        <td>Ferrara, 2011</td>
    </tr>
    <tr>
        <td>3�mes et 5�mes</td>
        <td>Sable</td>
        <td>Gen�ve, 2005</td>
    </tr>
    <tr>
        <td>1ers</td>
        <td>Salle</td>
        <td>Kaohsiung, 2004</td>
    </tr>
    <tr>
        <td>2�mes</td>
        <td>Salle</td>
        <td>Loughborough, 2002</td>
    </tr>
    <tr>
        <td>3�mes</td>
        <td>Salle</td>
        <td>Gen�ve, 2000</td>
    </tr>
    <tr>
        <td>5�mes</td>
        <td>Salle</td>
        <td>Portsmouth, 1990</td>
    </tr>
    <tr>
        <td>5�mes</td>
        <td>Salle</td>
        <td>Neuch�tel, 1987</td>
    </tr>
    <tr>
        <td>7�mes</td>
        <td>Salle</td>
        <td>Taipei, 1984</td>
    </tr>
</table>
<h3 class="alt">Femmes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>�dition</th>
    </tr>
    <tr>
        <td>2�mes</td>
        <td>Salle</td>
        <td>Ferrara, 2011</td>
    </tr>
    <tr>
        <td>3�mes et 4�mes</td>
        <td>Sable</td>
        <td>Gen�ve, 2005</td>
    </tr>
    <tr>
        <td>2�mes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2004</td>
    </tr>
    <tr>
        <td>2�mes</td>
        <td>Salle</td>
        <td>Loughborough, 2002</td>
    </tr>
    <tr>
        <td>3�mes</td>
        <td>Salle</td>
        <td>Gen�ve, 2000</td>
    </tr>
    <tr>
        <td>4�mes</td>
        <td>Salle</td>
        <td>Portsmouth, 1990</td>
    </tr>
    <tr>
        <td>4�mes</td>
        <td>Salle</td>
        <td>Neuch�tel, 1987</td>
    </tr>
</table>
<h3 class="alt">M18 Hommes ou M18 Mixte</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>�dition</th>
    </tr>
    <tr>
        <td>3�mes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2013</td>
    </tr>
    <tr>
        <td>2�mes</td>
        <td>Salle</td>
        <td>Traiskirchen, 2011</td>
    </tr>
    <tr>
        <td>3�mes et 4�mes</td>
        <td>Sable</td>
        <td>Gen�ve, 2005</td>
    </tr>
</table>
<h3 class="alt">M18 Femmes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>�dition</th>
    </tr>
    <tr>
        <td>3�mes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2013</td>
    </tr>
    <tr>
        <td>1�res</td>
        <td>Sable</td>
        <td>Gen�ve, 2005</td>
    </tr>
</table>
<h3 class="alt">M15 Hommes</h3>
<table class="classementTourFinal">
    <tr>
        <th>Position</th>
        <th>Terrain</th>
        <th>�dition</th>
    </tr>
    <tr>
        <td>4�mes</td>
        <td>Salle</td>
        <td>Kaohsiung, 2013</td>
    </tr>
</table>
