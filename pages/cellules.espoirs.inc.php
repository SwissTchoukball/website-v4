<?php
	$retour = mysql_query("SELECT * FROM TextCorpPage WHERE IdTextCorpPage = '25' ORDER BY paragrapheNum");
    // affiche le texte
    while($donnees = mysql_fetch_array($retour)) {
        /*if ($donnees['paragrapheNum'] == 2) {
            ?>
            <a href='http://www.tchoukball.ch/photoTchouk/main.php?g2_itemId=71753'><img class='imageFlottanteGauche' src='http://www.tchoukball.ch/photoTchouk/main.php?g2_view=core.DownloadItem&g2_itemId=71755&g2_serialNumber=2' alt='Cellules Espoirs M18' width='400px' height='267px' title="La s�lection suisse M18 compos�e des cellules espoirs lors des championnats d'Europe � Usti en R�publique Tch�que"/></a>
            <?php
        }*/
        if ($donnees['paragrapheNum'] == 4) {
            /*echo "<ul>";
            echo "<li>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</li>";*/
        } elseif ($donnees['paragrapheNum'] == 5) {
            /*echo "<li>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</li>";
            echo "</ul>";*/
        } elseif ($donnees['paragrapheNum'] == 3) {

        } else {
            echo "<p>";
            echo afficherAvecEncryptageEmail($donnees["paragraphe".$_SESSION["__langue__"]]);
            echo "</p>";
        }
    }
    $retour = mysql_query("SELECT * FROM Download WHERE id = '79'");
    $donnees = mysql_fetch_array($retour);
    echo "<p><a href='".PATH_DOCUMENTS."Fr_".$donnees["fichier"]."'>".$donnees["titre".$_SESSION["__langue__"]]."</a></p>";

    showCommissionHead(13);
?>

