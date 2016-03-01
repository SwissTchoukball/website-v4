<?php
session_start();

require('../config.php');

include('../includes/fonctions.inc.php');

if ($_SESSION['__userLevel__'] != 0){
    header("HTTP/1.0 403 Forbidden");
    echo "<h1>Forbidden</h1>";
    echo "You don't have permission to download Tchoukup excel lists.";
    echo "<hr />";
    echo "<em>" . VAR_LANG_ASSOCIATION_NAME . " - ";
    echo "<script>document.write('<a href=\"' + document.referrer + '\">Go Back</a>');</script>";
    echo "</em>";
    exit;
}

// Queries
$queries['envois-individuels'] =
    "SELECT p.`raisonSociale`,
           c.`descriptionCiviliteFr`,
           p.`nom`,
           p.`prenom`,
           p.`adresse`,
           p.`cp`,
           p.`npa`,
           p.`ville`,
           pa.`descriptionPaysFr`
    FROM `DBDPersonne` p, `DBDCivilite` c, `DBDPays` pa, `ClubsFstb` cl
    WHERE `idCHTB` = 2
    AND p.`idCivilite` = c.`idCivilite`
    AND p.`idPays` = pa.`idPays`
    AND p.`idClub` = cl.`nbIdClub`
    AND (p.`idStatus` = 5 -- Membre soutien (club ou hors-club)
         OR p.`idStatus` = 23 -- Membre vip (club ou hors-club)
         OR (p.`idStatus` != 4 AND p.`idClub` = 15) -- Membre non-passif hors-club
         OR p.`idClub` = 4 -- Membres du TBC Genève
         OR (p.`idClub` = 29 AND p.`idStatus` = 6)) -- Membres juniors du TBC Vernier
    ORDER BY `nom`, `prenom`";
$listsHeaders['envois-individuels'] = "Raison sociale \t Civilité \t Nom \t Prénom \t Adresse (ligne 1)" .
    "\t Adresse (ligne 2) \t NPA \t Localité \t Pays\n";
$listsAttributes['envois-individuels'] = [
    'raisonSociale',
    'descriptionCiviliteFr',
    'nom',
    'prenom',
    'adresse',
    'cp',
    'npa',
    'ville',
    'descriptionPaysFr'
];

$queries['nb-tchoukup-colis-par-club'] =
    "SELECT COUNT(*) AS 'nbTchoukup',
            c.`club`,
            p.`nom`,
            p.`prenom`,
            p.`adresse`,
            p.`numPostal`,
            p.`ville`
    FROM `DBDPersonne` dbd, `ClubsFstb` c, `Personne` p
    WHERE dbd.`idCHTB` = 2
    AND dbd.`idClub` = c.`nbIdClub`
    AND c.`id` = p.`idClub`
    AND p.`contactClub` = 1
    AND c.`actif` = 1 -- Membre d'un club actif
    AND (dbd.`idStatus` = 3 OR dbd.`idStatus` = 6) -- membre actif ou junior
    AND dbd.`idClub` != 4 -- Pas membre du TBC Genève (car dans les envois individuels)
    AND !(dbd.`idClub` = 29 AND dbd.`idStatus` = 6) -- Pas membre junior du TBC Vernier (car dans les envois individuels)
    GROUP BY dbd.`idClub`
    ORDER BY c.`club`";
$listsHeaders['nb-tchoukup-colis-par-club'] = "Nombre de Tchoukup \t Club \t Nom \t Prénom \t Adresse" .
    "\t NPA \t Localité\n";
$listsAttributes['nb-tchoukup-colis-par-club'] = [
    'nbTchoukup',
    'club',
    'nom',
    'prenom',
    'adresse',
    'numPostal',
    'ville'
];



if (isset($_GET['query']) && isset($queries[$_GET['query']])) {
    $query = $queries[$_GET['query']];
    $listHeaders = $listsHeaders[$_GET['query']];
    $listAttributes = $listsAttributes[$_GET['query']];
    $fileName = $_GET['query'] . '.tsv';
} else {
    header("HTTP/1.0 400 Bad Request");
    echo "<h1>Bad Request</h1>";
    echo "Your query does not match any available list to download";
    echo "<hr />";
    echo "<em>" . VAR_LANG_ASSOCIATION_NAME . " - ";
    echo "<script>document.write('<a href=\"' + document.referrer + '\">Go Back</a>');</script>";
    echo "</em>";
    exit;
}


$result = mysql_query($query) or die(mysql_error());

// Entêtes des colones dans le fichier Excel
$excel = $listHeaders;

//Les resultats de la requette
$lastIndex = sizeof($listAttributes) - 1;
while($row = mysql_fetch_array($result)) {
    foreach ($listAttributes as $index => $attribute) {
        $excel .= $row[$attribute];
        if ($index < $lastIndex) {
            $excel .= " \t ";
        } else {
            // Last attribute
            $excel .= "\n";
        }
    }
}

header("Content-type: text/tab-separated-values");
header("Content-disposition: attachment; filename=" . $fileName);
print $excel;
exit;
?>