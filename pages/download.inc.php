<?php
statInsererPageSurf(__FILE__);
echo "<div class='downloads'>";

//Récupération des catégories
$typesArray = array();
echo "<ul class='listeTypeDownload'>";
echo "<li><a href='/telechargements'>" . VAR_LANG_TOUT . "</a></li>";
$requeteListeTypesDownload = "SELECT id, description" . $_SESSION["__langue__"] . " AS description FROM TypeDownload WHERE visible=1 ORDER BY description";
$retour = mysql_query($requeteListeTypesDownload);
while ($typeDownload = mysql_fetch_assoc($retour)) {
    echo "<li><a href='/telechargements/" . $typeDownload['id'] . "'>" . $typeDownload['description'] . "</a></li>";
    $typesArray[$typeDownload['id']] = $typeDownload['description'];
}
echo "</ul>";


//Récupération des downloads
if (isset($_GET['type']) && is_numeric($_GET['type'])) {
    $requeteSQL = "SELECT * FROM `Download` WHERE `idType`='" . $_GET['type'] . "' AND `visible`='1' ORDER BY `titre" . $_SESSION["__langue__"] . "`";
    $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");
} else {
    $requeteSQL = "SELECT * FROM `Download`,`TypeDownload` WHERE `TypeDownload`.`id`= `Download`.`idType` AND `Download`.`visible`=1 AND `TypeDownload`.`visible`=1 ORDER BY `TypeDownload`.`description" . $_SESSION["__langue__"] . "`,`titre" . $_SESSION["__langue__"] . "`,`date`";
    $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");
    //$catergorie = true;
}

$nbColonne = count($VAR_TABLEAU_DES_LANGUES) + 1;
$bordure = true;

// construction de l'entete
$entete_download[0] = DOWNLOAD_ENTETE_DESCRIPTION;
$taille_colonne_download[0] = "NULL";
for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
    $entete_download[$i + 1] = $VAR_TABLEAU_DES_LANGUES[$i][1];
    $taille_colonne_download[$i + 1] = "65px";
}
//Début d'un tableau
$debut_tableau = '<table class="st-table st-table--alternate-bg">';
$debut_tableau .= "<tr>";
for ($i = 0; $i < count($entete_download); $i++) {
    if ($taille_colonne_download == "NULL" || $taille_colonne_download[$i] == "NULL") {
        $debut_tableau .= "<th>" . $entete_download[$i] . "</th>";
    } else {
        $debut_tableau .= "<th width='" . $taille_colonne_download[$i] . "'>" . $entete_download[$i] . "</th>";
    }
}
//Fin d'un tableau
$fin_tableau = "</table>";

$premiereFois = true;
$typeEnCours = "";
while ($record = mysql_fetch_array($recordset)) {

    if ($typeEnCours != $record["idType"] /*&& $catergorie===true*/) {
        $typeEnCours = $record["idType"];
        if (!$premiereFois) {
            echo $fin_tableau;
        }
        echo "<h4>" . $typesArray[$record["idType"]] . "</h4><br />";
        echo $debut_tableau;
    }
    //echo "<td><p>".$record["titre".$_SESSION["__langue__"]].($record["date"]!="0000-00-00"?" : ".date_sql2date($record["date"]):"")."</p></td>";
    echo "<tr>";
    if ($record["date"] != "0000-00-00") {
        if ($record["idType"] == 8) {
            echo "<td>" . $record["titre" . $_SESSION["__langue__"]] . " : " . date_sql2MonthYear($record["date"],
                    $VAR_G_MOIS) . "</td>";
        } else {
            echo "<td>" . $record["titre" . $_SESSION["__langue__"]] . " : " . date_sql2date($record["date"]) . "</td>";
        }
    } else {
        echo "<td>" . $record["titre" . $_SESSION["__langue__"]] . "</td>";
    }
    for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
        if (preg_match("#^(http|https)://#", $record["fichier"])) {
            echo "<td class='center'><a target='_blank' href='" . $record["fichier"] . "'>";
            echo "lien";
            echo "</a></td>";
        } elseif (is_file($_ENV["DOCUMENT_ROOT"] . PATH_DOCUMENTS . $VAR_TABLEAU_DES_LANGUES[$i][0] . "_" . $record["fichier"])) {
            echo "<td class='center'><a href='" . PATH_DOCUMENTS . $VAR_TABLEAU_DES_LANGUES[$i][0] . "_" . $record["fichier"] . "'>" . tailleFichier($_ENV["DOCUMENT_ROOT"] . PATH_DOCUMENTS . $VAR_TABLEAU_DES_LANGUES[$i][0] . "_" . $record["fichier"]) . "</a></td>";
        } else {
            echo "<td><p>&nbsp;</p></td>";
        }
        if ($i < count($VAR_TABLEAU_DES_LANGUES) - 1) {
        }
    }
    echo "</tr>";


    if ($premiereFois) {
        $premiereFois = false;
    }
}
echo $fin_tableau;
echo "</div>";
?>
