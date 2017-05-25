<div class="insererNews">
    <?php statInsererPageSurf(__FILE__);

    // insertion news
    if (isset($_POST["action"]) && $_POST["action"] == "insererNews") {

        // mise en forme
        $nomChamp = "";
        for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
            $nomChamp .= "`titre" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "`, `corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "`";
            if ($i < count($VAR_TABLEAU_DES_LANGUES) - 1) {
                $nomChamp .= ",";
            }
        }
        $valeurChamp = "";
        for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
            $valeurChamp .= "'" . addslashes(validiteInsertionTextAvecTagHTML($_POST["titre" . $VAR_TABLEAU_DES_LANGUES[$i][0]])) . "','" . addslashes(validiteInsertionTextAvecTagHTML($_POST["corps" . $VAR_TABLEAU_DES_LANGUES[$i][0]])) . "'";
            if ($i < count($VAR_TABLEAU_DES_LANGUES) - 1) {
                $valeurChamp .= ",";
            }
        }
        $premiereNews = $_POST["premiereNews"] == "on" ? 1 : 0;


        $dateNewsServeur = date_actuelle();
        $requeteSQL = "INSERT INTO `News` ( `id`, `date`,`utilisateur`,`premiereNews`,`image`," . $nomChamp . ") " .
            "VALUES ('','" . $dateNewsServeur . "','" . $_SESSION["__nom__"] . " " . $_SESSION["prenom"] . "','" . $premiereNews . "','" . $_POST['image'] . "'," . $valeurChamp . ")";
        // echo "req = ".$requeteSQL;
        mysql_query($requeteSQL);

        $idNews = mysql_insert_id();
        if (is_array($selectInformation)) {
            while (list(, $val) = each($selectInformation)) {
                $requeteSQL = "INSERT INTO `RegroupementNews` (`idNews`,`idInformation`) VALUES ('$idNews','$val')";
                mysql_query($requeteSQL);
            }
        }
        echo "<h4>News ajoutée</h4>";
    }

    echo '<script language="JavaScript">var nblangue=' . count($VAR_TABLEAU_DES_LANGUES) . ';</script>';

    ?>
    <script language="JavaScript">
        function controleInsertion() {

            var nberreur = 0;
            if (form.elements[0].value == "") {
                nberreur++;
                alert("Insérer un titre pour la première langue");
            }
            if (form.elements[1].value == "") {
                nberreur++;
                alert("Insérer un corps pour la première langue");
            }
            if (nberreur == 0 && form.elements[nblangue * 2 + 1].value == "") {
                if (!confirm("Vous n'avez pas sélectionné de type d'information, voulez-vous continuer ?")) {
                    nberreur++;
                }
            }
            return nberreur == 0;
        }

    </script>
    <form name="form" method="post"
          action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"
          onSubmit="return controleInsertion();">
        <table class="tableauInsererNews">
            <?php
            for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                $titleId = 'titre' . $VAR_TABLEAU_DES_LANGUES[$i][0];
                $bodyId = 'corps' . $VAR_TABLEAU_DES_LANGUES[$i][0];
                ?>
                <tr>
                    <td colspan="2"><h3><?php echo $VAR_TABLEAU_DES_LANGUES[$i][1]; ?></h3></td>
                </tr>
                <tr>
                    <td width="60"><label for="<?php echo $titleId; ?>">Titre</label></td>
                    <td width=""><input name="<?php echo $titleId; ?>" id="<?php echo $titleId; ?>" type="text" size="70"
                                        maxlength="80"></td>
                </tr>
                <tr>
                    <td valign="top"><label for="<?php echo $bodyId; ?>">Corps</label></td>
                    <td><textarea name="<?php echo $bodyId; ?>" id="<?php echo $bodyId; ?>" cols="67"
                                  rows="20"></textarea></td>
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
            <h2>Image</h2>
            <input type='radio' name='image' value='false' id='aucune' checked='checked'/>
            <label for='aucune'>Aucune</label><br/>
            <?php
            $retour = mysql_query("SELECT * FROM Uploads WHERE type='jpg' OR type='jpeg' OR type='png' OR type='gif' ORDER BY date DESC");
            while ($donnees = mysql_fetch_array($retour)) {
                echo "<input type='radio' name='image' value='" . $donnees['id'] . "' id='" . $donnees['id'] . "'> <label for='" . $donnees['id'] . "'><a href='http://www.tchoukball.ch/uploads/" . $donnees['fichier'] . "' target='_blank'>" . $donnees['titre'] . "</a></label><br />";
            }
            ?>
        </div>
        <div class="optionsNews">
            <input type="checkbox" name="premiereNews" id="firstNewsCheckbox" class="couleurCheckBox">
            <label for="firstNewsCheckbox">Toujours comme première news</label>
        </div>
        <div class="optionsNews">
            <label for="informationTypeSelector">Type d'information</label><br>
            <select name="selectInformation[]" id="informationTypeSelector" size="10" multiple>
                <?php

                $requeteSQL = "SELECT * FROM `TypeInformation` WHERE `id`>'0' ORDER BY `description" . $_SESSION["__langue__"] . "`";
                $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

                while ($record = mysql_fetch_array($recordset)) {
                    echo "<option value='" . $record["id"] . "'>" . $record["description" . $_SESSION["__langue__"]] . "</option>";
                }
                ?>
            </select>
        </div>
        <p>ctrl+click : multi-selection</p>
        <!-- Ce qui se trouve dans la colonne à gauche fini ici -->

        <p style="clear: both; " align="center">
            <input name="action" type="hidden" id="action" value="insererNews">
            <input type="submit" class="button button--primary" name="Submit" value="<?php echo VAR_LANG_INSERER; ?>">
        </p>
    </form>
</div>
