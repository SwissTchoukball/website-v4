<?php
statInsererPageSurf(__FILE__);

echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#" . VAR_LOOK_COULEUR_ERREUR_SAISIE . "';
	 var couleurValide; couleurValide='#" . VAR_LOOK_COULEUR_SAISIE_VALIDE . "';
	 </SCRIPT>";

/* RECEPTION ET ENREGISTREMENT DE L'UPLOAD */
/**
 * function formatFileName
 * @access public
 * @param string - nom de fichier à formater
 * @param int - longueur maximale autorisée pour le nom de fichier
 * @return string - nom de fichier formaté
 * @desc Tronque éventuellement le nom de fichier, le convertit en minuscules et
 *           y élimine les caractères potentiellement dangereux.
 */
function formatFileName($aFileName, $aMaxLength = 50)
{
    $aFileName = strToLower(subStr($aFileName, 0, $aMaxLength));
    $aFileName = ereg_replace('[^a-zA-Z0-9,._\+\()\-]', '_', $aFileName);

    return $aFileName;
} // end of function formatFileName() /2


/* PARAMETRES DE CONFIGURATION DU SCRIPT
 */

// chemin d'accès au répertoire d'upload (vers où le fichier uploadé temporaire sera transféré)
// ce répertoire doit EXISTER et être ACCESSIBLE EN ECRITURE !!
$destination_dir = $_SERVER["DOCUMENT_ROOT"] . '/uploads';
$destination_dir_thumb = $_SERVER["DOCUMENT_ROOT"] . '/uploads/vignettes/';

// taille maximale en octets du fichier à uploader
$file_max_size = 48000000;

// extensions de fichiers autorisées

/*$authorized_extensions = array('jpg', 'jpeg', 'gif', 'png', 'xls', 'doc', 'pdf', 'rtf', 'sit', 'zip', 'txt', 'mp3', 'mp4', 'm4a', 'm4v', 'mpg', 'mpeg', 'mov', 'movie', 'avi', 'wmv');*/ // Ancienne technique

$authorized_extensions = array();

$retour = mysql_query("SELECT extension FROM TypeExtensionFichiers ORDER BY extension");
while ($donnees = mysql_fetch_array($retour)) {
    $authorized_extensions[] .= $donnees["extension"];
}

$uploadReussi = false;


if ($_POST["action"] == "insererUpload") {

    echo "<p class='center'>";

    /* TRAITEMENT PRINCIPAL
     */

    // vérifie l'existence du répertoire de destination
    if (!is_dir($destination_dir)) {
        echo 'Veuillez indiquer un r&eacute;pertoire destination correct !';
        die();
    }

    // vérifie que répertoire de destination a des droits en écriture
    if (!is_writeable($destination_dir)) {
        echo 'Veuillez sp&eacute;cifier des droits en &eacute;criture pour le r&eacute;pertoire destination !';
        die();
    }

    // réception du formulaire
    if (isSet($_POST['submitFile'])) {

        // vérifie qu'un fichier a bien été soumis
        if (isSet($_FILES) && is_array($_FILES)) {

            // pas d'erreur lors de l'upload
            if ($_FILES['aFile']['error'] == UPLOAD_ERR_OK) {

                // vérifie la taille en octets
                if ($_FILES['aFile']['size'] <= $file_max_size) {

                    // vérifie l'extension du fichier recu
                    // il est aussi possible (et sans doute mieux) de se baser sur $_FILES['aFile']['type']
                    // qui retourne le type MIME correspondant (par exemple: image/pjpeg)
                    $lastPos = strRChr($_FILES['aFile']['name'], ".");
                    if ($lastPos !== false && in_array(strToLower(subStr($lastPos, 1)), $authorized_extensions)) {

                        // définit un nom de fichier destination unique à partir du nom du fichier original formaté
                        $destination_file = time() . formatFileName($_FILES['aFile']['name']);

                        // déplace le fichier uploadé du répertoire temporaire
                        // vers les répertoire/fichier destination spécifiés
                        if (move_uploaded_file($_FILES['aFile']['tmp_name'],
                            $destination_dir . DIRECTORY_SEPARATOR . $destination_file)) {
                            echo 'Fichier valide et upload&eacute; correctement.<br />';
                            echo 'Format : ' . $lastPos . '<br />';
                            $uploadReussi = true;
                            ?>
                            Votre fichier se trouve &agrave; l'adresse suivante: <a
                                href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/uploads/<?php echo $destination_file; ?>"
                                target="_blank">http://<?php echo $_SERVER["HTTP_HOST"]; ?>
                                /uploads/<?php echo $destination_file; ?></a><br/>
                            <?php

                            $url_entier = 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/' . $destination_file . '';

                            $thumb_destination_file = 'thumb_' . $destination_file;


                            // CrÈation de la miniature
                            if ($lastPos == '.png' OR $lastPos == '.gif' OR $lastPos == '.jpg' OR $lastPos == '.jpeg') {
                                if ($lastPos == '.png') {
                                    $source = imagecreatefrompng($url_entier);
                                } elseif ($lastPos == '.gif') {
                                    $source = imagecreatefromgif($url_entier);
                                } elseif ($lastPos == '.jpg' OR $lastPos == '.jpeg') {
                                    $source = imagecreatefromjpeg($url_entier);
                                }

                                $largeur_source = imagesx($source);
                                $hauteur_source = imagesy($source);
                                if ($largeur_source > $hauteur_source) { // paysage
                                    $largeur_destination = 200;
                                    $facteur = $largeur_source / 200;
                                    $hauteur_destination = $hauteur_source / $facteur;
                                } elseif ($largeur_source < $hauteur_source) { // portrait
                                    $hauteur_destination = 200;
                                    $facteur = $hauteur_source / 200;
                                    $largeur_destination = $largeur_source / $facteur;
                                } else { // carrÈ
                                    $hauteur_destination = 200;
                                    $largeur_destination = 200;
                                }

                                $destination = imagecreatetruecolor($largeur_destination, $hauteur_destination);

                                $largeur_destination = imagesx($destination);
                                $hauteur_destination = imagesy($destination);

                                imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination,
                                    $hauteur_destination, $largeur_source, $hauteur_source);

                                if ($lastPos == '.png') {
                                    $miniature = imagepng($destination,
                                        $destination_dir_thumb . $thumb_destination_file);
                                } elseif ($lastPos == '.gif') {
                                    $miniature = imagegif($destination,
                                        $destination_dir_thumb . $thumb_destination_file);
                                } elseif ($lastPos == '.jpg' OR $lastPos == '.jpeg') {
                                    $miniature = imagejpeg($destination,
                                        $destination_dir_thumb . $thumb_destination_file);
                                }
                                ?>
                                <img
                                    src="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/uploads/vignettes/<?php echo $thumb_destination_file; ?>"
                                    alt="Miniature de l'image uploadÈe"/><br/><br/>
                                <?php
                                echo 'Vignette valide et upload&eacute;e correctement.<br />';

                            }


                        } else { // error sur move_uploaded_file
                            echo 'Le fichier n\'a pas &eacute;t&eacute; upload&eacute; correctement !';
                            $uploadReussi = false;
                        }
                    } else { // pas d'extension ou mauvaise extension
                        echo 'Mauvaise extension !';
                        $uploadReussi = false;
                    }
                } else { // Taille maximale dépassée
                    echo 'Fichier trop volumineux !';
                    $uploadReussi = false;
                }
            } else { // Erreur lors de l'upload
                switch ($_FILES['aFile']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        echo 'Le fichier upload&eacute; d&eacute;passe la valeur sp&eacute;cifi&eacute;e
                       pour upload_max_filesize dans php.ini.';
                        $uploadReussi = false;
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        echo 'Le fichier upload&eacute; d&eacute;passe la valeur sp&eacute;cifi&eacute;e
                       pour MAX_FILE_SIZE dans le formulaire d\'upload.';
                        $uploadReussi = false;
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        echo 'Le fichier n\'a &eacute;t&eacute que partiellement upload&eacute;.';
                        $uploadReussi = false;
                        break;
                    default:
                        echo 'Aucun fichier n\'a &eacute;t&eacute upload&eacute;.';
                        $uploadReussi = false;
                } // switch
            }
        } else { // aucun fichier reçu
            echo 'Pas de fichier recu';
            $uploadReussi = false;
        }
    } // fin de réception de formulaire
    echo "</p>";
    ?>

    <?php

    if ($uploadReussi AND isset($url_entier) AND isset($_POST['titre']) AND isset($_POST['description']) AND isset($_POST['typeInfo'])) {
        //On renomme les variables
        $titre = addslashes($_POST['titre']);
        $fichier = addslashes($destination_file);
        $type = substr($lastPos, 1);
        $description = addslashes($_POST['description']);
        $descritpion = nl2br($description);
        $typeInfo = addslashes($_POST['typeInfo']);
        $date = date('Y\-m\-d');

        mysql_query("INSERT INTO Uploads VALUES('', '" . $titre . "', '" . $fichier . "', '" . $type . "', '" . $description . "', '" . $typeInfo . "', '" . $date . "')");
        echo '<div class="center">L\'upload a &eacute;t&eacute; ajout&eacute; dans la base de donnée.</div><p />';
    } else {
        echo '<div class="center">L\'upload n\'a pas &eacute;t&eacute; ajout&eacute; dans la base de données.</div><p />';
    }
} // Fin if action = insererUpload
elseif ($_POST["action"] == "modifierUpload") {
    if (isset($_POST['titre']) AND isset($_POST['description']) AND isset($_POST['typeInfo']) AND isset($_POST['id'])) {
        //On renomme les variables
        $titre = addslashes($_POST['titre']);
        $description = addslashes($_POST['description']);
        $descritpion = nl2br($description);
        $typeInfo = addslashes($_POST['typeInfo']);
        $id = $_POST['id'];

        mysql_query("UPDATE Uploads SET titre='" . $titre . "', description='" . $description . "', typeInfo='" . $typeInfo . "' WHERE id=" . $id . "");
        echo '<div class="center">L\'upload a &eacute;t&eacute; correctement modifié.</div><p />';
    } else {
        echo '<div class="center">L\'upload n\'a pas &eacute;t&eacute; modifié.</div><p />';
    }
}

/* FIN RECEPTION ET ENREGISTREMENT DE L'UPLOAD */
?>

<SCRIPT language='JavaScript'>

    function controlerSaisie() {

        var nbError;
        nbError = 0;

        // titre
        if (modificationUpload.titre.value.length == 0) {
            modificationUpload.titre.style.background = couleurErreur;
            if (nbError == 0)modificationUpload.titre.focus();
            nbError++;
        }
        else {
            modificationUpload.titre.style.background = couleurValide;
        }

        // description
        if (modificationUpload.description.value.length == 0) {
            modificationUpload.description.style.background = couleurErreur;
            if (nbError == 0)modificationUpload.description.focus();
            nbError++;
        }
        else {
            modificationUpload.description.style.background = couleurValide;
        }
        return nbError == 0;
    }

</SCRIPT>


<div class="center">

    <form name="modificationUpload" method="post" onSubmit="return controlerSaisie();"
          action="admin.php?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>"
          enctype="multipart/form-data">
        <?php
        if (!isset($_GET['modif'])) {
            ?>
            <h3>Upload de fichiers</h3><p/>
            Votre fichier de doit pas exc&eacute;der 48Mo.<br/>
            Formats accept&eacute;s:
            <?php // liste des extensions
            for ($i = 0; $i < count($authorized_extensions); $i++) {
                if ($i != 0) {
                    echo ", ";
                }
                echo $authorized_extensions[$i];
            }
            ?>
            <br/><br/>
            <!-- Taille maximale en octets. Non sécurisé car facilement contournable !! -->
            <input type="hidden" name="MAX_FILE_SIZE" value="48000000"/>
            Veuillez s&eacute;lectionner un fichier &agrave; uploader: <br/>
            <input type="file" name="aFile" size="25" maxlength="48000000"/><p/>
            <?php
            $titre = '';
            $description = '';
            $typeInfo = '';
        } else {
            $retour = mysql_query("SELECT * FROM Uploads WHERE id=" . $_GET['modif'] . "");
            $donnees = mysql_fetch_array($retour);

            $titre = stripslashes($donnees['titre']);
            $description = stripslashes($donnees['description']);
            $typeInfo = stripslashes($donnees['typeInfo']);
            $fichier = $donnees['fichier'];
            $type = $donnees['type'];
            $id = $donnees['id'];
            $retourA = mysql_query("SELECT type FROM TypeExtensionFichiers WHERE extension='" . $type . "'");
            $donneesA = mysql_fetch_array($retourA);
            if ($donneesA['type'] == 1) {
                $image = true;
            } else {
                $image = false;
            }
            ?>
            <h3>Modification d'un fichier uploadÈ</h3><p/>
            <?php
        }
        if ($image) {
            echo "<img src='" . PATH_UPLOADS . "/vignettes/thumb_" . $fichier . "' alt='" . $titre . "' />";
        }
        ?>
</div><br/>
<table width="80%" border="0" align="center">
    <tr>
        <td align="right"><p>Titre:</p></td>
        <td><input type="text" name="titre" size="30" value="<?php echo $titre; ?>"/><span
                style="color:red;font-size:10px">*Obligatoire</span></td>
    </tr>
    <tr>
        <td align="right">Description:<br/><span style="color:red;font-size:10px">*Obligatoire</span></td>
        <td><textarea name="description" cols="50" rows="10"><?php echo $description; ?></textarea></td>
    </tr>
    <td align="right">Type d'information:</td>
    <td><select name="typeInfo">
            <?php
            $retourbis = mysql_query("SELECT * FROM TypeInformation WHERE id>0 ORDER BY description" . $_SESSION["__langue__"] . "");
            while ($donneesbis = mysql_fetch_array($retourbis)) {
                ?>
                <option <?php if ($donneesbis['idUnique'] == $typeInfo) {
                    echo "selected='selected'";
                } ?>
                    value="<?php echo $donneesbis["idUnique"]; ?>"><?php echo $donneesbis["description" . $_SESSION["__langue__"]]; ?></option>
                <?php
            }
            ?>
        </select>
    </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <?php if (!isset($_GET['modif'])) { ?>
            <input type="hidden" name="action" value="insererUpload">
            <input type="submit" name="submitFile" value="Envoyer le fichier" class="button button--primary"/></td>
        <?php } else { ?>
            <input type="hidden" name="action" value="modifierUpload">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="submitFile" value="Modifier" class="button button--primary"/></td>
        <?php } ?>
    </tr>
</table>
</form><p/>
