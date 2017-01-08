<?php
echo "<tr>";

$entete_download = array(
    $agenda_heure,
    $agenda_categories,
    VAR_LANG_EQUIPE,
    "-",
    VAR_LANG_EQUIPE,
    VAR_LANG_MATCH,
    VAR_LANG_RESULTAT
);

for ($i = 0; $i < count($entete_download); $i++) {
    echo "<th>" . $entete_download[$i] . "</th>";
}

echo "</tr>";

$requeteSQL = "SELECT `International_Matchs`.`Date` , `International_Matchs`.`Heure` , `International_Categorie`.`nomCat" . $_SESSION["__langue__"] . "` nomCat,
												`International_Participant`.`nomPart" . $_SESSION["__langue__"] . "` nomPartA,
												`International_ParticipantB`.`nomPart" . $_SESSION["__langue__"] . "` nomPartB,
												`Final_Nomination_Match`.`nomFinale" . $_SESSION["__langue__"] . "` nomFinale,
												`Final_Nomination_Match`.`id` idFinale,
												`International_Matchs`.`ScoreA`, `International_Matchs`.`ScoreB`, `International_Categorie`.`couleur`,
												`International_Matchs`.`idParticipantA`, `International_Matchs`.`idParticipantB`
												FROM `International_Matchs` , `International_Categorie`, `International_Participant`,
														 `International_Participant` `International_ParticipantB`, `Final_Nomination_Match`
												WHERE `International_Matchs`.`idCategorie` = `International_Categorie`.`id`
															AND `International_Matchs`.`idEvent` = '$idEvent'
															AND `International_Matchs`.`idParticipantA`= `International_Participant`.`id`
															AND `International_Matchs`.`idParticipantB`= `International_ParticipantB`.`id`
															AND `International_Matchs`.`idFinal_Nomination_Match` = `Final_Nomination_Match`.`id`
												ORDER BY `International_Matchs`.`Date` , `International_Matchs`.`Heure`";

$recordset = @mysql_query($requeteSQL);
$lastColor = "";
$dateCourrante = "";
$nomFinale = "";
$premiereFois = true;

while ($record = mysql_fetch_array($recordset)) {


    if ($dateCourrante != $record["Date"] || $premiereFois) {
        $premiereFois = false;
        echo "<tr><td colspan='" . ($nbColonne) . "'><p class='titresectiontext'>";
        echo date_sql2date($record["Date"]);
        echo "</p></td></tr>";
    }
    $dateCourrante = $record["Date"];
    $lastColor = $record["couleur"];
    echo "<tr bgcolor=" . $record["couleur"] . ">";
    echo "<td  class='center'><p>" . substr($record["Heure"], 0, 5) . "</p></td>";
    echo "<td><p>" . $record["nomCat"] . "</p></td>";
    echo "<td class='right'><p>";
    if ($record["idParticipantA"] == 1) {
        echo "<font color='#FF0000'><strong>" . $record["nomPartA"] . "</strong></font>";
    } else {
        echo $record["nomPartA"];
    }
    echo "</p></td>";
    echo "<td><p>-</p></td>";
    echo "<td><p>";
    if ($record["idParticipantB"] == 1) {
        echo "<font color='#FF0000'><strong>" . $record["nomPartB"] . "</strong></font>";
    } else {
        echo $record["nomPartB"];
    }
    echo "</p></td>";
    if ($record["idFinale"] > 0 && $record["idFinale"] != 8) {
        echo "<td><p><strong>" . $record["nomFinale"] . "</strong></p></td>";
    } else {
        echo "<td><p>" . $record["nomFinale"] . "</p></td>";
    }
    if ($record["ScoreA"] == 0 && $record["ScoreB"] == 0) {
        echo "<td>&nbsp;</td>";
    } else {
        echo "<td class='center'>" . $record["ScoreA"] . " - " . $record["ScoreB"] . "</td>";
    }
    echo "</tr>";
}
?>
