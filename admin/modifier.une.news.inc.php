<?php statInsererPageSurf(__FILE__);

$idNewsCourrante = $_GET['modifierNewsID'];
$requeteSQL = "SELECT * FROM News WHERE id = '$idNewsCourrante'";
$recordset = mysql_query($requeteSQL);
$record = mysql_fetch_array($recordset);
?>
<form name="form1" method="post" action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
    <table class="tableauInsererNews">
        <?php
        for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
            ?>
            <tr>
                <td colspan="2"><h3><?php echo $VAR_TABLEAU_DES_LANGUES[$i][1]; ?></h3></td>
            </tr>
            <tr>
                <td width="60"><p>titre :</p></td>
                <td width=""><input name="titre<?php echo $VAR_TABLEAU_DES_LANGUES[$i][0]; ?>" type="text" size="70"
                                    maxlength="80"
                                    value="<?php echo htmlspecialchars($record["titre" . $VAR_TABLEAU_DES_LANGUES[$i][0]],
                                        ENT_QUOTES, 'ISO-8859-1') ?>"></td>
            </tr>
            <tr>
                <td valign="top"><p>corps :</p></td>
                <td><textarea name="corps<?php echo $VAR_TABLEAU_DES_LANGUES[$i][0]; ?>" cols="67"
                              rows="20"><?php echo $record["corps" . $VAR_TABLEAU_DES_LANGUES[$i][0]] ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <h4>Explication rapide :</h4>
                    <p>Lien : <strong>[voici le lien](http://www.tchoukball.ch)</strong> donnera : <a
                            href="http://www.tchoukball.ch" target="_blank">voici le lien</a><br/>
                        Italique : <strong>*</strong>text en italique<strong>*</strong> donnera : <em>text en
                            italic</em><br/>
                        Gras : <strong>**</strong>text en gras<strong>**</strong> donnera : <strong>text en
                            gras</strong><br/><br/></p>
                </td>
            </tr>

            <?php
        }
        ?>
    </table>
    <br/><br/>
    <!-- Ce qui se trouve dans la colonne à gauche commence ici -->
    <div class="listeImagesNews">
        Image :<br/>
        <input type='radio' name='image' value='false' id='aucun' <?php if ($record["image"] == 0) {
            echo "checked='checked'";
        } ?> /> <label for='aucune'>Aucune</label><br/>
        <?php
        $retour = mysql_query("SELECT * FROM Uploads WHERE type='jpg' OR type='jpeg' OR type='png' OR type='gif' ORDER BY date DESC");
        while ($donnees = mysql_fetch_array($retour)) {
            if ($record["image"] == $donnees['id']) {
                $checked = " checked='checked'";
            } else {
                $checked = "";
            }
            echo "<input type='radio' name='image' value='" . $donnees['id'] . "' id='" . $donnees['id'] . "'" . $checked . "> <label for='" . $donnees['id'] . "'><a href='http://www.tchoukball.ch/uploads/" . $donnees['fichier'] . "' target='_blank'>" . $donnees['titre'] . "</a></label><br />";
        }
        ?>
    </div>
    <div class="optionsNews">
        <p><input type="checkbox" name="premiereNews"
                  class="couleurCheckBox" <?php echo $record["premiereNews"] == 0 ? "" : "checked"; ?>>
            Toujours comme premi&egrave;re news</p>
        <p>Type d'information :</p>
        <select name="selectInformation[]" size="10" multiple>
            <?php

            $requeteSQL = "SELECT * FROM `RegroupementNews` WHERE `idNews`='" . $idNewsCourrante . "'";
            $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");
            $tab_inf = "";
            for ($i = 0; $record = mysql_fetch_array($recordset); $i++) {
                $tab_inf[$i] = $record["idInformation"];
            }

            $requeteSQL = "SELECT * FROM `TypeInformation` WHERE `id`>'0' ORDER BY `description" . $_SESSION["__langue__"] . "`";
            $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

            while ($record = mysql_fetch_array($recordset)) {
                if (in_array($record["id"], $tab_inf)) {
                    echo "<option selected value='" . $record["id"] . "'>" . $record["description" . $_SESSION["__langue__"]] . "</option>";
                } else {
                    echo "<option value='" . $record["id"] . "'>" . $record["description" . $_SESSION["__langue__"]] . "</option>";
                }
            }
            ?>
        </select>
        <p>ctrl+click : multi-selection</p>
    </div>
    <!-- Ce qui se trouve dans la colonne à gauche fini ici -->

    <p align="center" style="clear: both;">
        <input name="action" type="hidden" id="action" value="modifierNews">
        <input name="idNews" type="hidden" id="idNews" value="<?php echo $idNewsCourrante; ?>">
        <input type="submit" name="Submit" class="button button--primary" value="<?php echo VAR_LANG_MODIFIER; ?>">
    </p>
</form>
