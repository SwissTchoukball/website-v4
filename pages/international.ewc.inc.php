<?php
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '32' ORDER BY paragrapheNum");
// affiche le texte
echo "<img class='imageFlottanteDroite' src='pictures/logos/logo_EWC.png' />";
while($donnees = mysql_fetch_array($retour)) {
    echo "<p>";
    echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
    echo "</p><br />";
}

?>
<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FEuropean-Winners-Cup%2F154520307917335&amp;width=600&amp;colorscheme=light&amp;show_faces=false&amp;stream=false&amp;header=false&amp;height=62" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:600px; height:62px;" allowTransparency="true"></iframe>

<p style="clear:right;"></p>
<h2>R�sultats suisses</h2>
<h3 class="alt">Ryb&nacute;ik (PL) 2016</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>4�me</td>
		<td>Chamb�sy Panthers</td>
	</tr>
	<tr>
		<td>7�me</td>
		<td>Val-de-Ruz Flyers</td>
	</tr>
</table>
<h3 class="alt">Saronno & Rescaldina (IT) 2015</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>3�me</td>
		<td>Chamb�sy Panthers</td>
	</tr>
	<tr>
		<td>9�me</td>
		<td>Piranyon Origin</td>
	</tr>
</table>
<h3 class="alt">Neuch�tel (CH) 2014</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>2�me</td>
		<td>Chamb�sy Panthers</td>
	</tr>
	<tr>
		<td>4�me</td>
		<td>Val-de-Ruz Flyers</td>
	</tr>
	<tr>
		<td>6�me</td>
		<td>Gen�ve</td>
	</tr>
</table>
<h3 class="alt">Leeds (UK) 2013</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>2�me</td>
		<td>Chamb�sy Panthers</td>
	</tr>
	<tr>
		<td>5�me</td>
		<td>Val-de-Ruz Flyers</td>
	</tr>
</table>
<h3 class="alt">Lazne Belohrad (CZ) 2012</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>3�me</td>
		<td>Chamb�sy</td>
	</tr>
	<tr>
		<td>4�me</td>
		<td>Val-de-Ruz</td>
	</tr>
</table>
<h3 class="alt">Wels (AUT) 2011</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>2�me</td>
		<td>Chamb�sy</td>
	</tr>
	<tr>
		<td>4�me</td>
		<td>Val-de-Ruz</td>
	</tr>
</table>
<h3 class="alt">Saronno (IT) 2010</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>3�me</td>
		<td>Gen�ve</td>
	</tr>
	<tr>
		<td>4�me</td>
		<td>Lausanne</td>
	</tr>
</table>
<h3 class="alt">Lausanne (CH) 2009</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>1er</td>
		<td>Lausanne</td>
	</tr>
	<tr>
		<td>3�me</td>
		<td>Gen�ve</td>
	</tr>
	<tr>
		<td>4�me</td>
		<td>La Chaux-de-Fonds</td>
	</tr>
</table>
<h3 class="alt">Ferrara (IT) 2008</h3>
<table class="classementTourFinal">
	<tr>
		<th>Position</th>
		<th>�quipe</th>
	</tr>
	<tr>
		<td>1er</td>
		<td>Lausanne</td>
	</tr>
	<tr>
		<td>3�me</td>
		<td>Gen�ve</td>
	</tr>
</table>
