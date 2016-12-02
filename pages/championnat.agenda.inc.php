<?php
include "includes/agenda.utility.inc.php";
$categorie = 7; // ==> catergorie championnat
?>

<form name="affichage" method="post" action="">
    <table class="formagenda">
        <tr>
            <td align="right" width="50%"><p><?php echo $agenda_annee; ?> :</p></td>
            <td align="left"><select name="annee" id="select" onChange="affichage.submit();">
                    <?php
                    // recherche de la premiere date
                    $requeteAnnee = "SELECT MIN( Agenda_Evenement.dateDebut ) FROM `Agenda_Evenement`";
                    $recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
                    $dateMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");
                    $anneeMin = annee($dateMin[0]);
                    $anneeMinAffichee = $anneeMin - annee(date_actuelle());

                    // championnat de aout à aout => deux date de différence => il y a deux années.
                    $nbChampionnatExistant = -$anneeMinAffichee;

                    // si on est en aout, on peut afficher une option en plus pour le nouveau championnat
                    if (mois(date_actuelle()) > 8) {
                        $nbChampionnatExistant++;
                    }

                    $anneDebutChampionnat = $anneeMin;

                    if ($annee == "") {
                        $annee = "Avenir";
                    }

                    if ($annee == "Avenir") {
                        echo "<option selected value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                    } else {
                        echo "<option value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                    }

                    for ($i = 0; $i < $nbChampionnatExistant; $i++) {
                        if ($annee == $anneDebutChampionnat) {
                            echo "<option selected value='$anneDebutChampionnat'>" . VAR_LANG_CHAMPIONNAT . " $anneDebutChampionnat-" . ($anneDebutChampionnat + 1) . "</option>";
                        } else {
                            echo "<option value='$anneDebutChampionnat'>" . VAR_LANG_CHAMPIONNAT . " $anneDebutChampionnat-" . ($anneDebutChampionnat + 1) . "</option>";
                        }
                        $anneDebutChampionnat++;
                    }
                    ?></select>
            </td>
        </tr>
    </table>
</form>

<?php
// affichage des dates
if ($annee == "Avenir") {
    $annee = date_actuelle();
} else {
    $annee = "$annee-08-00";
    $jusqua = $annee + 1;
    $jusqua .= "-08-00";
}

$sousRequete = "SELECT `Agenda_Evenement_Categorie`.`id_TypeEve` FROM `Agenda_Evenement_Categorie` WHERE
								`Agenda_Evenement_Categorie`.`id_Categorie` = $categorie";
//echo "<BR>Voici la sousRequete : $sousRequete<BR>";
$recordSetId_TypeEve = mysql_query($sousRequete) or die ("<H3>Cette catégorie n'existe pas.</H3>");
//$recordset = mysql_fetch_array($recordSetId_TypeEve);
$valeursIN = "";
while ($record = mysql_fetch_array($recordSetId_TypeEve)) {
    $eve = $record["id_TypeEve"];
    $valeursIN .= $eve . ",";
}
if ($valeursIN != "") {
    $valeursIN = substr($valeursIN, 0, strlen($valeursIN) - 1);
}

$requeteSelect = "SELECT * FROM `Agenda_Evenement`, `Agenda_TypeEvent` WHERE
					`Agenda_Evenement`.`id_TypeEve` = `Agenda_TypeEvent`.`id_TypeEve` AND
					(`dateDebut` >= '" . $annee . "' OR `dateFin` >= '" . $annee . "')";

if (isset($jusqua)) {
    $requeteSelect .= " AND (`dateDebut` <= '" . $jusqua . "' OR `dateFin` <= '" . $jusqua . "')";
}
$requeteSelect .= " AND `Agenda_TypeEvent`.`id_TypeEve` IN ($valeursIN)";
$requeteSelect .= " ORDER BY `dateDebut` ASC, 'heureDebut' ASC";

$recordset = mysql_query($requeteSelect) or die ("<H3>Cette catégorie n'existe pas.</H3>");
?>

<table class="agenda">
    <tr>
        <th width="60px"><?php echo $agenda_date; ?></th>
        <th><?php echo $agenda_description; ?></th>
        <th><?php echo $agenda_lieu; ?></th>
        <th width="40px"><?php echo $agenda_debut; ?></th>
        <th width="40px"><?php echo $agenda_fin; ?></th>
    </tr>
    <?php

    // afficher le resultat de la requete
    while ($record = mysql_fetch_array($recordset)) {
        $couleur = $record['couleur'];
        $dateDebut = date_sql2date($record['dateDebut']);
        $heureDebut = $record['heureDebut'];
        $dateFin = date_sql2date($record['dateFin']);
        $heureFin = $record['heureFin'];
        $nomType = $record['nomType'];
        $description = $record['description'];
        $lieu = $record['lieu'];
        $utilisateur = $record['utilisateur'];
        $idEvent = $record['NumeroEvenement'];

        if ($description == "") {
            $description = "&nbsp;";
        }
        if ($lieu == "") {
            $lieu = "&nbsp;";
        }
        ?>
        <tr bgcolor="#<?php echo $couleur; ?>">
            <td align="center"><?php echo $dateDebut; ?></td>
            <td><?php echo $description; ?></td>
            <td><?php echo $lieu; ?></td>
            <td align="center"><?php echo heure($heureDebut); ?>:<?php echo minute($heureDebut); ?></td>
            <td align="center"><?php echo heure($heureFin); ?>:<?php echo minute($heureFin); ?></td>
        </tr>
        <?php
    }
    ?>

    <tr>
        <th><?php echo $agenda_date; ?></th>
        <th><?php echo $agenda_description; ?></th>
        <th><?php echo $agenda_lieu; ?></th>
        <th><?php echo $agenda_debut; ?></th>
        <th><?php echo $agenda_fin; ?></th>
    </tr>
</table>
