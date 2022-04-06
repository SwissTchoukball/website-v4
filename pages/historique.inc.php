<?php
echo "<img class='imageFlottanteGauche' src=" . VAR_IMG_DRBRANDT . ">";

$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '4' ORDER BY paragrapheNum");
// affiche le texte
$entete = 0;
while ($donnees = mysql_fetch_array($retour)) {
    // affiche le texte
    echo "<p>";
    afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
    echo "</p>";
}
?>
<br/>
<img src="pictures/licences/CC-BY.png" alt="Licence Creative Commons BY 3.0" style="float: right;"/><br/>
