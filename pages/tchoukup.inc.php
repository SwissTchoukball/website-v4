<?php
//! Texte
$request = "SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '21' ORDER BY paragrapheNum;";
$recordset = mysql_query($request) or die ("<H3>Paragraphe inconnu</H3>");

while ($text = mysql_fetch_array($recordset)) { // Affichage de tout les paragraphes
    echo markdown($text["paragraphe" . $_SESSION["__langue__"]]);
}
?>
<div class="newsletterForm">
    <script type="text/javascript"
            src="https://newsletter.infomaniak.com/external/webform-script/eyJpdiI6ImFXXC9KaVJ2czRUbW1VV3p0NUEwQVJOVU1ZSFQrbW1xcnFsZXJhYVh4VitJPSIsInZhbHVlIjoiU3E4QWJvWTJTM25xOHBkVzRGY3VEc2Q1S0E4UGg0bk1IMGJNMkg2QUQzQT0iLCJtYWMiOiI1YThmZWRhOWJlNzU5NDBmYmI2MWM4ZWQ2MTkxOGUxOTVmZjcxM2VlMDE3NTA3YzQxN2Y5Yzg1MWY2YzE5YzRiIn0="></script>
</div>
<?php
echo $textAppondu;

echo '<p>';
echo "Email" . " : ";
echo emailperso('redaction@tchoukball.ch', 'redaction@tchoukball.ch', 'Contact depuis tchoukball.ch');
echo '</p>';

//! Archives
$requete = "SELECT * FROM `Download` WHERE idType=8 AND visible=1 ORDER BY `id` DESC";
$recordset = mysql_query($requete) or die ("<H3>Aucune date existe</H3>");

echo '<ul class="tchoukupList">';
while ($record = mysql_fetch_assoc($recordset)) {

    $imageFile = PATH_DOCUMENTS . $_SESSION["__langue__"] . "_" . substr($record["fichier"], 0,
            strlen($record["fichier"]) - 3) . "jpg";
    $pdfFile = PATH_DOCUMENTS . $_SESSION["__langue__"] . "_" . $record["fichier"];

    echo '<li>';
    echo "<h3 class='alt'>" . $record["titre" . $_SESSION["__langue__"]] . "</h3>";
    echo "<p class='moisTchoukup'>" . date_sql2MonthYear($record["date"], $VAR_G_MOIS) . "</p>";
    echo "<a href='$pdfFile'><img border='0' src='" . $imageFile . "'/></a>";
    echo "<p align='center'><a href='$pdfFile'>" . VAR_LANG_TELECHARGER . "</a></p>";
    echo '</li>';
    echo ' '; //Espace nécessaire pour l'alignement justifié.
}
echo '</ul>';

?>
