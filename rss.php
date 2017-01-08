<?php

header('Content-type: application/xml; charset=utf-8');

require('config.php'); // Le fichier config doit exister ( include + puissant ) pour se connecter à MySQL
include "includes/markdown.php";

mysql_set_charset('utf8');

function date_sql2RFC_2822($date)
{
    $annee = substr($date, 0, 4);
    $mois = substr($date, 5, 2);
    $jour = substr($date, 8, 2);
    $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
    $date_RFC_2822 = date('r', $timestamp);
    return $date_RFC_2822;
}

if (in_array(strtolower($_GET['lang']), ['fr', 'de', 'it', 'en'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'fr';
}
$langForDb = ucfirst($lang);
$langIdentifier = $lang == 'en' ? $lang : $lang . '-CH';

// récupérations des 10 dernières données de la table 'News'
$query1 =
    "SELECT id, titreFr, titre$langForDb AS titre, date, corpsFr, corps$langForDb AS corps
     FROM News
     ORDER BY date DESC
     LIMIT 0, 10";
$result1 = mysql_query($query1);

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
<title>Swiss Tchoukball</title>
<link>https://tchoukball.ch/news</link>
<description>Swiss Tchoukball News</description>
<language>' . $langIdentifier . '</language>'; // ici on remplit les informations

// on veut 10 afficher news
for ($i = 0; $i < 10; $i++) {
    @$row = mysql_fetch_array($result1); // fonction pour la boucle

    // If title doesn't exist in that language, use french.
    $title = $row["titre"];
    if ($title == "") {
        $title = $row["titreFr"];
    }

    // If body doesn't exist in that language, use french.
    $description = $row["corps"];
    if ($description == "") {
        if ($lang == 'de') {
            $description = "**Diese Nachricht ist nicht auf Deutsch**<br />";
        } else if ($lang == 'it') {
            $description = "**Questa notizia non è disponibile in italiano**<br />";
        } else if ($lang == 'en') {
            $description = "**This news is not available in english**<br />";
        }
        $description .= $row["corpsFr"];
    }

    $title = htmlspecialchars($title, ENT_COMPAT, 'UTF-8'); // fonction pour le titre
    $author = "info@tchoukball.ch"; // fonction pour l'auteur
    $link = "https://tchoukball.ch/news/" . $row["id"]; // fonction pour le lien
    $description = Markdown($description); // fonction pour le contenu de la news
    $date = date_sql2RFC_2822($row["date"]); // fonction pour la date de publication de la news

    $id = $row["id"];

    if (!$id) {
        // si il y a moins de 10 news, afficher que le nombre de news disponibles
        echo '';
    } else {
        // sinon afficher les 10 dernières news
        echo '<item><title>' . $title . '</title>';
        echo '<link>' . $link . '</link>';
        echo '<guid isPermaLink="true">' . $link . '</guid>';
        echo '<description>' . $description . '</description>';
        echo '<author>' . $author . '</author>';
        echo '<pubDate>' . $date . '</pubDate></item>';
    }
}

echo '</channel></rss>'; // fin de la page rss

mysql_close(); // Déconnexion de MySQL
