<?
if($_SESSION["debug_tracage"])echo __FILE__."<BR>";
statInsererPageSurf(__FILE__);
?>
<div class="presentation">
	<?
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '34' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
            // entete
            if($donnees["paragrapheNum"] == 0) {
                echo "<h2>";
                echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</h2>";
            }
            else{
                echo "<p>";
                echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
                echo "</p>";
            }
    }

	?>
</div>
<img src="pictures/international_championnat_europe.jpg" alt="Championat d'Europe" />
<h2>R�sultats Suisses</h2>
<h3 class="alt">Hommes</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�dition</th>
	</tr>
	<tr>
		<td>3�mes</td>
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
		<td>2�mes</td>
		<td>Macolin (CH), 2006</td>
	</tr>
	<tr>
		<td>1ers</td>
		<td>Rimini (IT), 2003</td>
	</tr>
	<tr>
		<td>2�mes et 4�mes</td>
		<td>Gen�ve (CH), 2001</td>
	</tr>
</table>
<h3 class="alt">Femmes</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�dition</th>
	</tr>
	<tr>
		<td>1�res</td>
		<td>Radevormwald (DE), 2014</td>
	</tr>
	<tr>
		<td>1�res</td>
		<td>Hereford (UK), 2010</td>
	</tr>
	<tr>
		<td>1�res</td>
		<td>Usti (CZ), 2008</td>
	</tr>
	<tr>
		<td>1�res</td>
		<td>Macolin (CH), 2006</td>
	</tr>
	<tr>
		<td>1�res</td>
		<td>Rimini  (IT), 2003</td>
	</tr>
	<tr>
		<td>1�res</td>
		<td>Gen�ve (CH), 2001</td>
	</tr>
</table>
<h3 class="alt">M18</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�dition</th>
	</tr>
	<tr>
		<td>1ers</td>
		<td>Usti (CZ), 2008</td>
	</tr>
	<tr>
		<td>2�mes et 4�mes</td>
		<td>Macolin (CH), 2006</td>
	</tr>
</table>