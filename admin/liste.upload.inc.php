<?php
statInsererPageSurf(__FILE__);

// chemin d'accs au rŽpertoire d'upload (vers o le fichier uploadŽ temporaire sera transfŽrŽ)
// ce rŽpertoire doit EXISTER et tre ACCESSIBLE EN ECRITURE !!
$destination_dir = '/home/www/63b135ce53397c1b0bcb278bb4fc6df0/web/uploads/';
$destination_dir_thumb = '/home/www/63b135ce53397c1b0bcb278bb4fc6df0/web/uploads/vignettes/';


include('supprimer.upload.inc.php');

if (isset($_GET['classement'])) {
    if ($_GET['classement'] == 'A') {
        $classement = 'A'; // Classer par type d'information
    } else {
        $classement = 'B'; // Classer par type de fichier
    }
} else {
    $classement = 'B'; // Classer par type de fichier
}
if ($classement == 'B') {
    echo "<p class='center'>Classer par <a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&classement=A'>type d'information</a> / type de fichier</p>";
    $typeSource = 'TypeFichiers';
    $sansTout = '';
} elseif ($classement == 'A') {
    echo "<p class='center'>Classer par type d'information / <a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&classement=B'>type de fichier</a></p>";
    $typeSource = 'TypeInformation';
    $sansTout = 'WHERE id>0';
}

$i = 0;
$retour = mysql_query("SELECT * FROM " . $typeSource . " " . $sansTout . " ORDER BY description" . $_SESSION["__langue__"] . "");
echo "<p class='center'>";
while ($donnees = mysql_fetch_array($retour)) {
    if ($i != 0) {
        echo " - ";
    }
    $i++;
    echo "<a href='#cat" . $donnees['id'] . "'>" . $donnees['description' . $_SESSION["__langue__"]] . "</a>";
}
echo "</p>";

?>
<br/>
<table class="tableauUploads">
    <tr>
        <th>
            <?php echo VAR_LANG_TITRE; ?>
        </th>
        <th width="65px">
            <?php echo VAR_LANG_FORMAT; ?>
        </th>
        <th width="65px">
            <?php echo VAR_LANG_TAILLE; ?>
        </th>
        <th width="65px">
            <?php echo VAR_LANG_MODIFIER; ?>
        </th>
        <th width="65px">
            <?php echo VAR_LANG_SUPPRIMER; ?>
        </th>
    </tr>
    <?php
    $retour = mysql_query("SELECT * FROM " . $typeSource . " " . $sansTout . " ORDER BY description" . $_SESSION["__langue__"] . "");
    while ($donnees = mysql_fetch_array($retour)) {
        echo "<tr>";
        echo "<td id='cat" . $donnees['id'] . "' colspan='5' align='center'>";
        echo "<strong>" . $donnees["description" . $_SESSION["__langue__"]] . "</strong>";
        echo "</td>";
        echo "</tr>";
        if ($classement == 'B') {
            $retourbis = mysql_query("SELECT * FROM Uploads, TypeExtensionFichiers WHERE TypeExtensionFichiers.type='" . $donnees['id'] . "' AND TypeExtensionFichiers.extension = Uploads.type");
        } elseif ($classement == 'A') {
            $retourbis = mysql_query("SELECT * FROM Uploads, TypeInformation WHERE TypeInformation.idUnique=Uploads.typeInfo");
        }
        while ($donneesbis = mysql_fetch_array($retourbis)) {
            echo "<tr>";
            echo "<td>";
            echo "<a href='" . PATH_UPLOADS . $donneesbis['fichier'] . "'>" . $donneesbis['titre'] . "</a>";
            echo "</td>";
            echo "<td>";
            echo $donneesbis['nom'];
            echo "</td>";
            echo "<td>";
            echo tailleFichier(PATH_UPLOADS . $donneesbis['fichier']);
            echo "</td>";
            echo "<td>";
            echo "<a href='admin.php?menuselection=" . $menuselection . "&smenuselection=2&modif=" . $donneesbis['id'] . "'>" . VAR_LANG_MODIFIER . "</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='admin.php?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&suppr=" . $donneesbis['id'] . "' onclick='return confirm(\"Vous &ecirc;tes sur le point de supprimer cet upload. OK pour supprimer, Annuler pour abandonner.\")'>" . VAR_LANG_SUPPRIMER . "</a>";
            echo "</td>";
            echo "</tr>";
        }
    }
    ?>
</table>
