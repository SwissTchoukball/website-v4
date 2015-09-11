<h3>Upload de fichiers</h3><p />
<?php
  /** 
   * function formatFileName 
   * @access public 
   * @param string - nom de fichier � formater 
   * @param int - longueur maximale autoris�e pour le nom de fichier 
   * @return string - nom de fichier format� 
   * @desc Tronque �ventuellement le nom de fichier, le convertit en minuscules et 
   *           y �limine les caract�res potentiellement dangereux. 
   */          
   function formatFileName($aFileName, $aMaxLength = 50) { 
     $aFileName = strToLower(subStr($aFileName, 0, $aMaxLength)); 
     $aFileName = ereg_replace('[^a-zA-Z0-9,._\+\()\-]', '_', $aFileName); 
       
     return $aFileName; 
   } // end of function formatFileName() /2 






   /* PARAMETRES DE CONFIGURATION DU SCRIPT 
    */ 
    
   // chemin d'acc�s au r�pertoire d'upload (vers o� le fichier upload� temporaire sera transf�r�) 
   // ce r�pertoire doit EXISTER et �tre ACCESSIBLE EN ECRITURE !! 
   $destination_dir = $_SERVER["DOCUMENT_ROOT"].'/uploads'; 
   $destination_dir_thumb = $_SERVER["DOCUMENT_ROOT"].'/uploads/vignettes';
    
   // taille maximale en octets du fichier � uploader 
   $file_max_size = 48000000; 
    
   // extensions de fichiers autoris�es 
   $authorized_extensions = array('jpg', 'jpeg', 'gif', 'png', 'xls', 'doc', 'pdf', 'rtf', 'sit', 'zip', 'txt', 'mp3', 'mp4', 'm4a', 'm4v', 'mpg', 'mpeg', 'mov', 'movie', 'avi', 'wmv'); 

    $uploadReussi = false;




    
   /* TRAITEMENT PRINCIPAL 
    */    
    
   // v�rifie l'existence du r�pertoire de destination 
   if (!is_dir($destination_dir)) { 
     echo 'Veuillez indiquer un r&eacute;pertoire destination correct !';
     echo '<br />R�pertoire indiqu� : '.$destination_dir;
     die(); 
   } 

   // v�rifie que r�pertoire de destination a des droits en �criture 
   if (!is_writeable($destination_dir)) { 
     echo 'Veuillez sp&eacute;cifier des droits en &eacute;criture pour le r&eacute;pertoire destination !'; 
     die();       
   }    
    
   // r�ception du formulaire 
   if (isSet($_POST['submitFile'])) { 

     // v�rifie qu'un fichier a bien �t� soumis 
     if (isSet($_FILES) && is_array($_FILES)) { 
    
       // pas d'erreur lors de l'upload 
       if ($_FILES['aFile']['error'] == UPLOAD_ERR_OK) { 
          
         // v�rifie la taille en octets 
         if ($_FILES['aFile']['size'] <= $file_max_size) { 

           // v�rifie l'extension du fichier recu 
           // il est aussi possible (et sans doute mieux) de se baser sur $_FILES['aFile']['type'] 
           // qui retourne le type MIME correspondant (par exemple: image/pjpeg)          
           $lastPos = strRChr($_FILES['aFile']['name'], "."); 
           if ($lastPos !== false && in_array(strToLower(subStr($lastPos, 1)), $authorized_extensions)) { 
    
             // d�finit un nom de fichier destination unique � partir du nom du fichier original format� 
             $destination_file = time().formatFileName($_FILES['aFile']['name']);             

             // d�place le fichier upload� du r�pertoire temporaire 
             // vers les r�pertoire/fichier destination sp�cifi�s 
            if (move_uploaded_file($_FILES['aFile']['tmp_name'], 
                                         $destination_dir.DIRECTORY_SEPARATOR.$destination_file)) { 
                echo 'Fichier valide et upload&eacute; correctement.<br />';
                echo 'Format : '.$lastPos.'<br />';
                $uploadReussi = true;
                ?>
                Votre fichier se trouve &agrave; l'adresse suivante: <a href="http://www.tchoukball.ch/uploads/<? echo $destination_file; ?>" target="_blank">http://www.tchoukball.ch/uploads/<? echo $destination_file; ?></a><br />
                <?
               
                $url_entier = 'http://www.tchoukball.ch/uploads/'.$destination_file.'';
                
                
                // Cr�ation de la miniature
                if ($lastPos == 'png' OR $lastPos == 'gif' OR $lastPos == 'jpg' OR $lastPos == 'jpeg') {
                    if ($lastPos == 'png') {
                        $source = imagecreatefrompng($url_entier);
                    }
                    elseif ($lastPos == 'gif') {
                        $source = imagecreatefromgif($url_entier);
                    }
                    elseif ($lastPos == 'jpg' OR $lastPos == 'jpeg') {
                        $source = imagecreatefromjpeg($url_entier);
                    }
                        
                    $largeur_source = imagesx($source);
                    $hauteur_source = imagesy($source);
                    $largeur_destination = 200;
                    $facteur = $largeur_source/200;
                    $hauteur_destination = $hauteur_source/$facteur;
                    
                    $destination = imagecreatetruecolor($largeur_destination, $hauteur_destination);
                    
                    imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
                    
                    $thumb_destination_file = 'thumb_'.$destination_file;
                    
                    if ($lastPos == 'png') {
                        $miniature = imagepng($destination, $thumb_destination_file);
                    }
                    elseif ($lastPos == 'gif') {
                        $miniature = imagegif($destination, $thumb_destination_file);
                    }
                    elseif ($lastPos == 'jpg' OR $lastPos == 'jpeg') {
                        $miniature = imagejpeg($destination, $thumb_destination_file);
                    }
                    ?>
                        <img src="thumb_<? echo $destination_file; ?>" alt="Miniature de l'image upload�e" />
                    <?
                    if (move_uploaded_file($thumb_destination_file, $destination_dir_thumb.DIRECTORY_SEPARATOR.$thumb_destination_file)) { 
                        echo 'Vignette valide et upload&eacute;e correctement.<br />';
                    }
                        
                }
               
               
             } else { // error sur move_uploaded_file 
               echo 'Le fichier n\'a pas &eacute;t&eacute; upload&eacute; correctement !';
               $uploadReussi = false;
             } 
           } else { // pas d'extension ou mauvaise extension 
             echo 'Mauvaise extension !';
               $uploadReussi = false; 
           }       
         } else { // Taille maximale d�pass�e 
           echo 'Fichier trop volumineux !';
               $uploadReussi = false; 
         } 
       } else { // Erreur lors de l'upload 
         switch ($_FILES['aFile']['error']){ 
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
     } else { // aucun fichier re�u 
       echo 'Pas de fichier recu';
               $uploadReussi = false; 
     } 
   } // fin de r�ception de formulaire 
?>

<?

if ($uploadReussi AND isset($url_entier) AND isset($_POST['titre']) AND isset($_POST['description']) AND isset($_POST['typeInfo']))
{
    //On renomme les variables
    $titre = addslashes($_POST['titre']);
    $fichier = addslashes($destination_file);
    $type = $lastPos;
    $description = addslashes($_POST['description']);
    $descritpion = nl2br($description);
    $categorie = addslashes($_POST['typeInfo']);
    $date = date('Y\-m\-d');
    
    mysql_query("INSERT INTO uploads VALUES('', '".$titre."', '".$url."', '".$description."', '".$typeInfo."', '".$date."')");
    echo '<div class="center">L\'upload a &eacute;t&eacute; ajout&eacute; aux t&eacute;l&eacute;chargements.</div><p />';
}
else
{
    echo '<div class="center">L\'upload n\'a pas &eacute;t&eacute; ajout&eacute; aux t&eacute;l&eacute;chargements.</div><p />';
}
?>
<a href="index.php?page=upload">Retour &agrave; la page d'upload</a>