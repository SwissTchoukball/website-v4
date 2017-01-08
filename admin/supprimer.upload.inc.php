<?php
if (isset($_GET['suppr'])) { // SUPPRESSION DETECTÉE
    $retour = mysql_query("SELECT * FROM Uploads WHERE id=" . $_GET['suppr'] . "");
    while ($donnees = mysql_fetch_array($retour)) {

        $fichier = $destination_dir . $donnees['fichier'];
        unlink($fichier);
        if ($donnees['type'] == 'png' OR $donnees['type'] == 'gif' OR $donnees['type'] == 'jpg' OR $donnees['type'] == 'jpeg') {
            $vignette = $destination_dir_thumb . "thumb_" . $donnees['fichier'];
            unlink($vignette);
        }
        mysql_query("DELETE FROM Uploads WHERE id=" . $_GET['suppr'] . "");
        echo "<imp>Suppression effectuée avec succès.</imp>";
    }
}
?>
