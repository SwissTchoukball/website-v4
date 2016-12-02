<?php
statInsererPageSurf(__FILE__);

if (isset($_POST["action"]) && $_POST["action"] == "modifier") {
    include "modifier.une.event.inc.php";
} else {

    if (isset($_POST["action"]) && $_POST["action"] == "modifierEvent") {
        $idNews = $_POST["idNews"];

        // modification de la news
        $valeurChamp = "";
        for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
            $valeurChamp .= "`titre" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "` = '" . $_POST["titre" . $VAR_TABLEAU_DES_LANGUES[$i][0]] . "', `corps" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "` = '" . $_POST["corps" . $VAR_TABLEAU_DES_LANGUES[$i][0]] . "'";
            if ($i < count($VAR_TABLEAU_DES_LANGUES) - 1) {
                $valeurChamp .= ",";
            }
        }
        $premiereNews = $_POST["premiereNews"] == "on" ? 1 : 0;

        $requeteSQL = "UPDATE `News` SET $valeurChamp, `premiereNews`='$premiereNews' WHERE `id` = '$idNews'";
        echo "req : $requeteSQL";
        mysql_query($requeteSQL);

        // supprimer le regroupement et reinserer le nouevau.
        $requeteSQL = "DELETE FROM `RegroupementNews` WHERE `idNews`='$idNews'";
        mysql_query($requeteSQL);

        if (is_array($selectInformation)) {
            while (list(, $val) = each($selectInformation)) {
                $requeteSQL = "INSERT INTO `RegroupementNews` (`idNews`,`idInformation`) VALUES ('$idNews','$val')";
                mysql_query($requeteSQL);
            }
        }
    }


    include "modifier.selection.event.inc.php";
}
?>
