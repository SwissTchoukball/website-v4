<div class="traductionCorps">
    <?php statInsererPageSurf(__FILE__); ?>

    <?php
    //save var
    $SAVE_VAR_TABLEAU_DES_LANGUES = $VAR_TABLEAU_DES_LANGUES;
    //usefull when you add a new lang.
    $VAR_TABLEAU_DES_LANGUES = array(
        array("Fr", "Français"),
        array("De", "Deutsch"),
        array("En", "English"),
        array("It", "Italiano")
    );
    ?>

    <?php
    // modification
    if (isset($_POST["action"]) && $_POST["action"] == "modifierText" && isset($_POST['nbSection']) && is_numeric($_POST['nbSection'])) {
        $nbSection = $_POST['nbSection'];
        for ($section = 1; $section <= $nbSection; $section++) {

            for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {

                $textModified = $_POST["corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $section];


                // if data are modified
                if ($textModified == "modified") {
                    $textId = $_POST["corpsId" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $section];
                    $textValue = addslashes($_POST["corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $section]);

                    $requeteSQL = "SELECT * FROM TextCorpPage WHERE idUniqueText=$textId";
                    $recordset = mysql_query($requeteSQL) or die ("<H3>ERROR B1: impossible to update data.</H3>");
                    $record = mysql_fetch_array($recordset);
                    $ancienText = validiteInsertionTextBd($record["paragraphe" . $VAR_TABLEAU_DES_LANGUES[$i][0]]);

                    $requeteSQL = "UPDATE TextCorpPage SET paragraphe" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "='$textValue' WHERE idUniqueText=$textId";
                    mysql_query($requeteSQL) or die ("<H3>ERROR B2: impossible to update data.</H3>");

                    // log modification
                    $dateLog = date("Y-m-d");
                    $timeLog = date("H:i:s");
                    $requeteSQL = "INSERT INTO logTraduction ( `idUser` , `date` , `time` , `langue` , `idText` , `ancienText` , `text`, `table` ) VALUES ('" . $_SESSION["__idUser__"] . "','$dateLog','$timeLog','" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "','$textId', '$ancienText', '$textValue', 'TextCorpPage')";
                    mysql_query($requeteSQL) or die ("<H3>ERROR B3: impossible to log data.</H3>");
                    $atLestOneModification = true;
                }
            }
        }
        /*
        // regenere les menus en mode embedded
        if($atLestOneModification){
            $silentMode=true;
            $embedded=true;
            $initialFilePath="includes/langue/generationFichier/";
            include "includes/langue/generationFichier/gen.fichier.bd.php";
        }
        */
    }
    ?>

    <SCRIPT language='JavaScript'>

        JS_GLOBAL_dataModified = 0;

        // data are modified ?
        function isDataModified() {
            return JS_GLOBAL_dataModified > 0;
        }

        function validForm() {
            if (isDataModified()) {
                response = confirm("Des donn&eacute;es ont &eacute;t&eacute; modifi&eacute;e, &ecirc;tes-vous s&ucirc;r de vouloir continuer sans enregister ?");
                return response;
            }
            return true;
        }

        function updateTextAreaStyle(inputTextArea) {
            if (inputTextArea.defaultValue == inputTextArea.value) {
                inputTextArea.style.background = "transparent";
                if (inputTextArea.modified) {
                    JS_GLOBAL_dataModified--;
                    inputTextArea.modified = false;
                }
            }
            else {
                inputTextArea.style.background = "#FF9155";
                if (inputTextArea.modified == null || !inputTextArea.modified) {
                    JS_GLOBAL_dataModified++;
                    inputTextArea.modified = true;
                }
            }
        }
    </SCRIPT>

    <!-- combbox -->
    <form name="translateType" action="" method="post">
        <table border="0" align="center">
            <tr>
                <td><p><?php echo "Page à traduire"; ?> :</p></td>
                <td><select name="translatePage" id="select" onChange="if(validForm())translateType.submit();">
                        <?php
                        if (isset($_POST['translatePage']) && is_numeric($_POST['translatePage'])) {
                            $translatePage = $_POST['translatePage'];
                        }
                        $requeteSQL = "SELECT distinct id, Description FROM IdTextCorpPage, TextCorpPage WHERE IdTextCorpPage.id = TextCorpPage.IdTextCorpPage ORDER BY IdTextCorpPage.Description";
                        $recordset = mysql_query($requeteSQL) or die ("<H3>ERROR B4: no entry to translate</H3>");

                        while ($record = mysql_fetch_array($recordset)) {
                            $translateId = $record["id"];
                            $translateDesc = $record["Description"];

                            // first as default
                            if (!isset($translatePage)) {
                                $translatePage = $translateId;
                            }

                            if ($translatePage == $translateId) {
                                echo "<option selected value='$translateId'>$translateDesc</option>";
                            } else {
                                echo "<option value='$translateId'>$translateDesc</option>";
                            }
                        }
                        ?></select></td>
            </tr>
        </table>
    </form>

    <table>
        <form name="translateText"
              action="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>" method="post"
              onsubmit="if(!isDataModified()){alert('rien à modifier'); return false;}">
            <table border="0" align="center">

                <?php

                //onsubmit="if(!isDataModified()){alert('rien à modifier'); return false;}"
                //onsubmit="alert("rien à modifier");"
                $requeteSQL = "SELECT * FROM TextCorpPage WHERE IdTextCorpPage = $translatePage ORDER BY paragrapheNum";
                $recordset = mysql_query($requeteSQL) or die ("<H3>ERROR B5: no entry to translate</H3>");
                $sectionNum = 1;
                while ($record = mysql_fetch_array($recordset)) {
                    $paragrapheId = $record["idUniqueText"];
                    echo "<tr><td colspan='2'><h3>Section/Paragraphe $sectionNum</h3></td></tr>";
                    for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                        $textpage = $record["paragraphe" . $VAR_TABLEAU_DES_LANGUES[$i][0]];
                        echo "<tr>";
                        echo "<td valign='top'><p>" . $VAR_TABLEAU_DES_LANGUES[$i][1] . "</p></td>";
                        echo "<td><textarea id='corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' name='corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' cols='100' rows='7' onChange='translateText.corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . ".value=\"modified\";updateTextAreaStyle(this);'>$textpage</textarea><input type='hidden' id='corpsId" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' name='corpsId" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' value='$paragrapheId'/><input type='hidden' id='corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' name='corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' value='initial'/></td>";
                        //updateTextAreaStyle
                        //echo "<td><textarea id='corps".$VAR_TABLEAU_DES_LANGUES[$i][0].$sectionNum."' name='corps".$VAR_TABLEAU_DES_LANGUES[$i][0].$sectionNum."' cols='120' rows='7' onChange='this.style.background=\"#FF9155\";translateText.corpsModified".$VAR_TABLEAU_DES_LANGUES[$i][0].$sectionNum.".value=\"modified\";JS_GLOBAL_dataModified = true;'>$textpage</textarea><input type='hidden' id='corpsId".$VAR_TABLEAU_DES_LANGUES[$i][0].$sectionNum."' name='corpsId".$VAR_TABLEAU_DES_LANGUES[$i][0].$sectionNum."' value='$paragrapheId'/><input type='hidden' id='corpsModified".$VAR_TABLEAU_DES_LANGUES[$i][0].$sectionNum."' name='corpsModified".$VAR_TABLEAU_DES_LANGUES[$i][0].$sectionNum."' value='initial'/></td>";
                        echo "</tr>";
                    }
                    $sectionNum++;
                }
                $sectionNum--;
                ?>
                </tr>
            </table>
            <br/>
            <input type="hidden" name="nbSection" value="<?php echo $sectionNum; ?>"/>
            <input type="hidden" name="action" value="modifierText"/>
            <p align="center"><input type="submit" class="button button--primary" value="<?php echo VAR_LANG_MODIFIER; ?>"/></p>
        </form>
        <div align="center">
            <form name="annuler" onsubmit="return validForm();" method="post"
                  action="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>"><input
                    type="submit" class="button button--cancel" value="<?php echo VAR_LANG_ANNULER ?>"></form>
        </div>

        <?php
        //restore var
        $VAR_TABLEAU_DES_LANGUES = $SAVE_VAR_TABLEAU_DES_LANGUES;
        ?>
</div>
