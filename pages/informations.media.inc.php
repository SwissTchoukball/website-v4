<?php
showDomainHead(8);
$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '13' ORDER BY paragrapheNum");
// affiche le texte
while ($donnees = mysql_fetch_array($retour)) {
    if ($donnees['paragrapheNum'] == 0) {
        echo "<h3>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h3>";
    } elseif ($donnees['paragrapheNum'] == 1 || $donnees['paragrapheNum'] == 3) {
        echo "<h4>";
        echo afficherAvecEncryptageEmail($donnees["paragraphe" . $_SESSION["__langue__"]]);
        echo "</h4><br />";
    }
}


$requeteSQL = "SELECT * FROM `Download` WHERE `IdType`='1' ORDER BY `date` DESC";
$recordset = mysql_query($requeteSQL) or die ("<p>internal error</p>");

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

$nbFois = 0;
echo "<ul>";
while ($record = mysql_fetch_array($recordset)) {
    if ($nbFois > 0) {
        $nbFois++;
    }
    $pressReleaseUrl = PATH_DOCUMENTS . $VAR_TABLEAU_DES_LANGUES[0][0] . "_" . $record["fichier"];
    if (is_file($root . PATH_DOCUMENTS . $_SESSION["__langue__"] . "_" . $record["fichier"])) {
        $pressReleaseUrl = PATH_DOCUMENTS . $_SESSION["__langue__"] . "_" . $record["fichier"];
    }

    echo "<li>" . date_sql2date($record["date"]) . " : <a href='" . $pressReleaseUrl . "'>" . $record["titre" . $VAR_TABLEAU_DES_LANGUES[0][0]] . "</a></li>";
}
echo "</ul>";

?>


