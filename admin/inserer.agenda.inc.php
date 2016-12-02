<div class="insererAgenda">
    <?php
    statInsererPageSurf(__FILE__);

    if (isset($_POST["action"]) && $_POST["action"] == "insererEvent") {
        $dateDebut = $debutAnnee - $debutMois - $debutJour;
        $dateFin = $finAnnee . "-" . $finMois . "-" . $finJour;
        $heureDebut = $debutHeure . ":" . $debutMinute . ":00";
        $heureFin = $finHeure . ":" . $finMinute . ":00";

        $nbErreur = 0;

        // test de la validité de la date de début
        if (!checkdate($debutMois, $debutJour, $debutAnnee)) {
            echo "<h4>date incorrecte : debut = " . $debutJour . $debutMois . $debutAnnee . "</h4>";
            $nbErreur++;
        }

        // test de la validité de la date de fin
        if (!checkdate($finMois, $finJour, $finAnnee)) {
            echo "<h4>date incorrecte : fin = " . $finJour . $finMois . $finAnnee . "</h4>";
            $nbErreur++;
        }

        // teste de la chronologie des dates
        if (date1_sup_date2($dateDebut, $dateFin)) {
            echo "<h4>Chronologie des dates non respectée : debut = " . $debutJour . $debutMois . $debutAnnee . ", fin = " . $finJour . $finMois . $finAnnee . "</h4>";
            $nbErreur++;
        }

        // test si les heures sont dans un ordre chronologique
        if (($debutHeure > $finHeure) ||
            ($debutHeure == $finHeure && $debutMinute > $finMinute)
        ) {
            echo "<h4>Chronologie des heures non respectée : debut = $heureDebut, fin = $heureFin</h4>";
            $nbErreur++;
        }

        // test si l'event doit être affiché ou pas
        if (isset($affiche)) {
            $ouvert = 1;
        } else {
            $ouvert = 0;
        }

        if ($nbErreur == 0) {
            $utilisateur = $_SESSION["__nom__"] . $_SESSION["__prenom__"];
            $requeteInsertion = "INSERT INTO `Agenda_Evenement` (`description` , `lieu` ,
				`dateDebut` , `dateFin` , `heureDebut` , `heureFin` , `id_TypeEve` , `affiche`, `utilisateur` )
				VALUES ('" . $description . "','" . $lieu . "','" . $dateDebut . "','" . $dateFin . "','" . $heureDebut . "','" . $heureFin . "','" . $type . "','" . $ouvert . "','" . $utilisateur . "');";

            mysql_query($requeteInsertion) or die ("<h4>Erreur, contacter le webmaster pour une erreur d'insertion de date</h4>");
            echo "<h4>Insertion effectuée avec succès</h4><br />";
        }
    }


    ?>

    <SCRIPT language='JavaScript'>
        // java script pour lier les menus debut-fin

        function selectionAutomatiqueAnne() {
            insertion.finAnnee.value = insertion.debutAnnee.value;
        }
        function selectionAutomatiqueMois() {
            insertion.finMois.value = insertion.debutMois.value;
        }
        function selectionAutomatiqueJour() {
            insertion.finJour.value = insertion.debutJour.value;
        }
        function selectionAutomatiqueHeure() {
            insertion.finHeure.value = insertion.debutHeure.value;
        }
        function selectionAutomatiqueMinute() {
            insertion.finMinute.value = insertion.debutMinute.value;
        }
        function chronologieDate() {
            dateActuelle = new Date();
            dateDebut = new Date(insertion.debutAnnee.value,
                insertion.debutMois.value,
                insertion.debutJour.value,
                insertion.debutHeure.value,
                insertion.debutMinute.value,
                00);
            dateFin = new Date(insertion.finAnnee.value,
                insertion.finMois.value,
                insertion.finJour.value,
                insertion.finHeure.value,
                insertion.finMinute.value,
                00);

            if (dateDebut.getTime() > dateFin.getTime()) {
                alert('Erreur dans les dates : \n\n' +
                    'début : ' + dateDebut.toLocaleString() + '\n' +
                    'fin : ' + dateFin.toLocaleString() + '\n');
                return false;
            }
            if (dateActuelle.getTime() > dateDebut.getTime()) {
                alert('Erreur dans les dates, vous ne pouvez pas introduire une date dans le passé');
                return false;
            }

            return true;
        }

    </SCRIPT>
    <form name="insertion" method="post"
          action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"
          onSubmit="return chronologieDate();">
        <table width="400" border="0" align="center">
            <tr>
                <td><p><?php echo $agenda_description; ?></p></td>
                <td><input name="description" type="text" id="description3" size="70" maxlength="70"></td>
            </tr>
            <tr>
                <td><p><?php echo $agenda_lieu; ?></p></td>
                <td><input name="lieu" type="text" id="lieu4" size="50" maxlength="50"></td>
            </tr>
            <tr>
                <td><p><?php echo $agenda_debut; ?></p></td>
                <td><p><?php echo $agenda_date; ?> :
                        <select name="debutJour" id="debutJour" onChange="selectionAutomatiqueJour()">
                            <?php echo creation_liste_jour(); ?> </select> <select name="debutMois" id="debutMois"
                                                                                   onChange="selectionAutomatiqueMois()">
                            <?php echo creation_liste_mois(); ?> </select> <select name="debutAnnee" id="debutAnnee"
                                                                                   onChange="selectionAutomatiqueAnne()">
                            <?php echo creation_liste_annee(0, 5); ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="debutHeure" id="debutHeure" onChange="selectionAutomatiqueHeure()">
                            <?php echo creation_liste_heure(); ?> </select> <select name="debutMinute" id="debutMinute">
                            <?php echo creation_liste_minute(); ?> </select></p></td>
            </tr>
            <tr>
                <td><p><?php echo $agenda_fin; ?></p></td>
                <td><p><?php echo $agenda_date; ?> :
                        <select name="finJour" id="finJour">
                            <?php echo creation_liste_jour(); ?> </select> <select name="finMois" id="finMois">
                            <?php echo creation_liste_mois(); ?> </select> <select name="finAnnee" id="finAnnee">
                            <?php echo creation_liste_annee(0, 5); ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="finHeure" id="finHeure">
                            <?php echo creation_liste_heure(); ?> </select> <select name="finMinute" id="finMinute">
                            <?php echo creation_liste_minute(); ?> </select></p></td>
            </tr>
            <tr>
                <td><p>Type</p></td>
                <td><select name="type" id="type">
                        <?php
                        $requeteSelect = "SELECT * FROM `Agenda_TypeEvent` WHERE id_TypeEve<>'5000' ORDER BY `nomType` ASC";
                        $recordset = mysql_query($requeteSelect) or die ("<H1>mauvaise requete</H1>");
                        while ($record = mysql_fetch_array($recordset)) {
                            $nomType = $record['nomType'];
                            $idType = $record['id_TypeEve'];
                            echo "<option value='$idType'>$nomType</option>";
                        }

                        ?>
                    </select></td>
            </tr>
            <tr>
                <td><p>Affiché</p></td>
                <td>
                    <input type="checkbox" name="affiche"/>
                </td>
            </tr>
        </table>
        <p align="center">
            <input name="action" type="hidden" value="insererEvent">
            <input name='submit' type='submit' value='<?php echo VAR_LANG_INSERER; ?>'>
        </p>
    </form>
</div>
