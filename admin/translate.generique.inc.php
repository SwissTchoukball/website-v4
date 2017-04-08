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
if (isset($_POST["subaction"]) && $_POST["subaction"] == "modifierText") {
    for ($section = 1; $section <= $nbSection; $section++) {

        for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {

            $textModified = $_POST["corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $section];


            // if data are modified
            if ($textModified == "modified") {
                $textId = $_POST["corpsId" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $section];
                $textValue = addslashes($_POST["corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $section]);

                $requeteSQL = "SELECT * FROM $table WHERE $identifier=$textId";
                $recordset = mysql_query($requeteSQL) or die ("<H3>ERROR A1: impossible to update data.</H3>");
                $record = mysql_fetch_array($recordset);
                $ancienText = validiteInsertionTextBd($record["$champ" . $VAR_TABLEAU_DES_LANGUES[$i][0]]);

                $requeteSQL = "UPDATE $table SET $champ" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "='$textValue' WHERE $identifier=$textId";
                // echo $requeteSQL;
                mysql_query($requeteSQL) or die ("<H3>ERROR A2: impossible to update data.</H3>");

                // log modification
                $dateLog = date("Y-m-d");
                $timeLog = date("H:i:s");
                $requeteSQL = "INSERT INTO logTraduction ( `idUser` , `date` , `time` , `langue` , `idText` , `ancienText` , `text`, `table` ) VALUES ('" . $_SESSION["__idUser__"] . "','$dateLog','$timeLog','" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "','$textId', '$ancienText', '$textValue', '$table')";
                mysql_query($requeteSQL) or die ("<H3>ERROR A3: impossible to log data.</H3>");
                $atLestOneModification = true;
            }
        }
    }

    // regenere les menus en mode embedded
    /*
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

<h4><?php echo "$titre"; ?></h4>
<div align="center">
    <form name="annuler" onsubmit="return validForm();" method="post"
          action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"><input type="submit"
                                                                                                        class="button button--cancel"
                                                                                                        value="<?php echo VAR_LANG_ANNULER ?>">
    </form>
</div>

<table>
    <form name="translateText" action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"
          method="post" onsubmit="if(!isDataModified()){alert('rien à modifier'); return false;}">
        <table border="0" align="center">

            <?php

            $requeteSQL = "SELECT * FROM $table";
            $recordset = mysql_query($requeteSQL) or die ("<H3>ERROR A4: no entry to translate</H3>");
            $sectionNum = 1;
            while ($record = mysql_fetch_array($recordset)) {
                $paragrapheId = $record["$identifier"];
                echo "<tr><td colspan='2'><h3>Ligne $sectionNum</h3></td></tr>";
                for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                    $textpage = $record["$champ" . $VAR_TABLEAU_DES_LANGUES[$i][0]];
                    echo "<tr>";
                    echo "<td valign='top'><p>" . $VAR_TABLEAU_DES_LANGUES[$i][1] . "</p></td>";
                    echo "<td><textarea id='corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' name='corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' cols='80' rows='7' onChange='translateText.corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . ".value=\"modified\";updateTextAreaStyle(this);'>$textpage</textarea><input type='hidden' id='corpsId" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' name='corpsId" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' value='$paragrapheId'/><input type='hidden' id='corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' name='corpsModified" . $VAR_TABLEAU_DES_LANGUES[$i][0] . $sectionNum . "' value='initial'/></td>";
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
        <input type="hidden" name="action" value="afficherTableChamp"/>
        <input type="hidden" name="subaction" value="modifierText"/>
        <input type="hidden" name="table" value="<?php echo $table; ?>"/>
        <input type="hidden" name="champ" value="<?php echo $champ; ?>"/>
        <input type="hidden" name="identifier" value="<?php echo $identifier; ?>"/>
        <input type="hidden" name="titre" value="<?php echo $titre; ?>"/>
        <p align="center"><input type="submit" value="<?php echo VAR_LANG_APPLIQUER; ?>" class="button button--primary"/></p>
    </form>
    <div align="center">
        <form name="annuler" onsubmit="return validForm();" method="post"
              action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"><input
                type="submit" value="<?php echo VAR_LANG_ANNULER ?>"></form>
    </div>

    <?php
    //restore var
    $VAR_TABLEAU_DES_LANGUES = $SAVE_VAR_TABLEAU_DES_LANGUES;
    ?>
