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
    if (is_file($root . PATH_DOCUMENTS . $_SESSION["__langue__"] . "_" . $record["fichier"])) {
        if (preg_match("#^Dossier de presse#i", $record["titreFr"])) {
            echo "<li class='dossierPresse'>";
        } else {
            echo "<li>";
        }
        echo date_sql2date($record["date"]) . " : <a href='" . PATH_DOCUMENTS . $_SESSION["__langue__"] . "_" . $record["fichier"] . "'>" . $record["titre" . $_SESSION["__langue__"]] . "</a></li>";
    } // fichier en francais par defaut
    else {
        if (preg_match("#^Dossier de presse#i", $record["titreFr"])) {
            echo "<li class='dossierPresse'>";
        } else {
            echo "<li>";
        }
        echo date_sql2date($record["date"]) . " : <a href='" . PATH_DOCUMENTS . $VAR_TABLEAU_DES_LANGUES[0][0] . "_" . $record["fichier"] . "'>" . $record["titre" . $VAR_TABLEAU_DES_LANGUES[0][0]] . "</a></li>";
    }
}
echo "</ul>";
echo "<p class='dossierPresse' align='right'>Dossiers de Presse</p>";

?>


