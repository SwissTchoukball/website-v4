<?php
session_start();

require('../config.php');

include('../includes/fonctions.inc.php');

if ($_SESSION['__userLevel__'] != 0) {
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
    FROM `DBDPersonne` p, `DBDCivilite` c, `DBDPays` pa, `clubs` cl
    WHERE `idCHTB` = 2
    AND p.`dateOfDeath` IS NULL
    AND p.`idCivilite` = c.`idCivilite`
    AND p.`idPays` = pa.`idPays`
    AND p.`idClub` = cl.`nbIdClub`
    AND (p.`idStatus` = 5 -- Membre soutien (club ou hors-club)
         OR p.`idStatus` = 23 -- Membre vip (club ou hors-club)
         OR (p.`idStatus` != 4 AND p.`idClub` = 15) -- Membre non-passif hors-club
         OR p.`idClub` = 4 -- Membres du TBC Gen�ve
         OR (p.`idClub` = 29 AND p.`idStatus` = 6)) -- Membres juniors du TBC Vernier
    ORDER BY `nom`, `prenom`";
$listsHeaders['envois-individuels'] = "Raison sociale \t Civilit� \t Nom \t Pr�nom \t Adresse (ligne 1)" .
    "\t Adresse (ligne 2) \t NPA \t Localit� \t Pays\n";
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
            dest.`nom`,
            dest.`prenom`,
            dest.`adresse`,
            dest.`npa`,
            dest.`ville`
    FROM `DBDPersonne` membres, `clubs` c, `DBDPersonne` dest
    WHERE membres.`idCHTB` = 2
    AND membres.`dateOfDeath` IS NULL
    AND membres.`idClub` = c.`nbIdClub`
    AND dest.`idDbdPersonne` = c.`idPresident`
    AND c.`statusId` = 1 -- Membre d'un club actif
    AND (membres.`idStatus` = 3 OR membres.`idStatus` = 6) -- membre actif ou junior
    AND membres.`idClub` != 4 -- Pas membre du TBC Gen�ve (car dans les envois individuels)
    AND !(membres.`idClub` = 29 AND membres.`idStatus` = 6) -- Pas membre junior du TBC Vernier (car dans les envois individuels)
    GROUP BY membres.`idClub`
    ORDER BY c.`club`";
$listsHeaders['nb-tchoukup-colis-par-club'] = "Nombre de Tchoukup \t Club \t Nom \t Pr�nom \t Adresse" .
    "\t NPA \t Localit�\n";
$listsAttributes['nb-tchoukup-colis-par-club'] = [
    'nbTchoukup',
    'club',
    'nom',
    'prenom',
    'adresse',
    'npa',
    'ville'
];


$queries['all-individuels'] =
    "SELECT p.`raisonSociale`,
           c.`descriptionCiviliteFr`,
           p.`nom`,
           p.`prenom`,
           p.`adresse`,
           p.`cp`,
           p.`npa`,
           p.`ville`,
           pa.`descriptionPaysFr`
    FROM `DBDPersonne` p, `DBDCivilite` c, `DBDPays` pa, `clubs` cl
    WHERE `idCHTB` = 2
    AND p.`dateOfDeath` IS NULL
    AND p.`idCivilite` = c.`idCivilite`
    AND p.`idPays` = pa.`idPays`
    AND p.`idClub` = cl.`nbIdClub`
    AND (p.`idStatus` = 5 -- Membres soutiens (club ou hors-club)
         OR p.`idStatus` = 23 -- Membres VIPs (club ou hors-club)
         OR (cl.`statusId` = 1 AND (p.`idStatus` = 3 OR p.`idStatus` = 6)) -- Membres actifs ou juniors d'un club actif
         OR (p.`idStatus` != 4 AND p.`idClub` = 15)) -- Membre non-passif hors-club
    ORDER BY `nom`, `prenom`";
$listsHeaders['all-individuels'] = "Raison sociale \t Civilit� \t Nom \t Pr�nom \t Adresse (ligne 1)" .
    "\t Adresse (ligne 2) \t NPA \t Localit� \t Pays\n";
$listsAttributes['all-individuels'] = [
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

$queries['individuels-juniors'] =
    "SELECT p.`raisonSociale`,
           c.`descriptionCiviliteFr`,
           p.`nom`,
           p.`prenom`,
           p.`adresse`,
           p.`cp`,
           p.`npa`,
           p.`ville`,
           pa.`descriptionPaysFr`
    FROM `DBDPersonne` p, `DBDCivilite` c, `DBDPays` pa, `clubs` cl
    WHERE `idCHTB` = 2
    AND p.`dateOfDeath` IS NULL
    AND p.`idCivilite` = c.`idCivilite`
    AND p.`idPays` = pa.`idPays`
    AND p.`idClub` = cl.`nbIdClub`
    AND (p.`idStatus` = 5 -- Membres soutiens (club ou hors-club)
         OR p.`idStatus` = 23 -- Membres VIPs (club ou hors-club)
         OR (cl.`statusId` = 1 AND p.`idStatus` = 6) -- Membres juniors d'un club actif
         OR (p.`idStatus` != 4 AND p.`idClub` = 15)) -- Membre non-passif hors-club
    ORDER BY `nom`, `prenom`";
$listsHeaders['individuels-juniors'] = "Raison sociale \t Civilit� \t Nom \t Pr�nom \t Adresse (ligne 1)" .
    "\t Adresse (ligne 2) \t NPA \t Localit� \t Pays\n";
$listsAttributes['individuels-juniors'] = [
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

// Ent�tes des colones dans le fichier Excel
$excel = $listHeaders;

//Les resultats de la requette
$lastIndex = sizeof($listAttributes) - 1;
while ($row = mysql_fetch_array($result)) {
    foreach ($listAttributes as $index => $attribute) {
        $excel .= $row[$attribute];
        if ($index < $lastIndex) {
            $excel .= "\t";
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
