<?php
statInsererPageSurf(__FILE__);
?>
<div>
    <?php
    if ($_POST["action"] == "modifier") {

        $requeteSQL = "SELECT * FROM `Personne` WHERE `Personne`.`nom`='" . addslashes($_SESSION["__nom__"]) . "' AND `Personne`.`prenom`='" . addslashes($_SESSION["__prenom__"]) . "'";

        $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");
        $record = mysql_fetch_array($recordset);

        $requeteModifierInfos = "UPDATE `Personne` SET `adresse`='" . $_POST["adresse"] . "',
            `numPostal`='" . $_POST["numPostal"] . "',
            `ville`='" . $_POST["ville"] . "',
            `telephone`='" . $_POST["telephone"] . "',
            `portable`='" . $_POST["portable"] . "',
            `email`='" . $_POST["email"] . "',
            `idClub`='" . $_POST["idClub"] . "',
            `dateNaissance`='" . $_POST["annee"] . "-" . $_POST["mois"] . "-" . $_POST["jour"] . "'
			WHERE `Personne`.`id`='" . $record["id"] . "'";
        mysql_query($requeteModifierInfos);


        if (md5($_POST["ancienPass"]) == $record["password"]) {
            try {
                UserService::updatePassword($record["id"], $_POST['nouveauPass'], $_POST['nouveauPassBis']);
                printSuccessMessage('Modification du mot de passe réussi');
            }
            catch (Exception $e) {
                $errorMessage = $e->getMessage();
                if ($e->getCode() == 400) {
                    $errorMessage .= ' Veuillez réessayer.';
                } else if ($e->getCode() == 500) {
                    $errorMessage .= ' Contactez le <a href="mailto:webmaster@tchoukball.ch">webmaster</a>';
                }
                printErrorMessage($errorMessage);
            }
        } else {
            printErrorMessage('Votre ancien mot de passe n\'est pas valide, impossible de modifier votre mot de passe');
        }
    }
    ?>



    <?php
    $requeteSQL = "SELECT * FROM `Personne` WHERE `Personne`.`nom`='" . addslashes($_SESSION["__nom__"]) . "' AND `Personne`.`prenom`='" . addslashes($_SESSION["__prenom__"]) . "'";

    $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

    $record = mysql_fetch_array($recordset);

    ?>
    <script language='javascript'>
        // TODO: Check each field separatly and on keypress
        function controlerSaisie() {
            var nbErreur = 0;

            if (mesInfos.email.value !== "" && (mesInfos.email.value.indexOf("@") < 1 || mesInfos.email.value.indexOf("@") >= (mesInfos.email.value.lastIndexOf(".")))) {
                nbErreur++;
                mesInfos.email.classList.add('st-invalid');
            }
            else {
                mesInfos.email.classList.remove('st-invalid');
            }

            if (mesInfos.nouveauPass.value !== mesInfos.nouveauPassBis.value || mesInfos.nouveauPass.value.length < 8) {
                // TODO: Do the same validation check as done in PHP.
                nbErreur++;
                mesInfos.nouveauPass.classList.add('st-invalid');
                mesInfos.nouveauPassBis.classList.add('st-invalid');
            }
            else {
                mesInfos.nouveauPass.remove('st-invalid');
                mesInfos.nouveauPassBis.remove('st-invalid');
            }

            return nbErreur === 0;
        }
    </script>

    <form name="mesInfos" class="st-form" method="post"
          action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"
          onSubmit="return controlerSaisie();">
        <fieldset>
            <label>Nom d'utilisateur</label>
            <div class="givenData"><?php echo stripslashes($record["username"]); ?></div>
            <label>Nom</label>
            <div class="givenData"><?php echo stripslashes($record["nom"]); ?></div>
            <label>Prénom</label>
            <div class="givenData"><?php echo stripslashes($record["prenom"]); ?></div>
        </fieldset>
        <fieldset>
            <label for="emailField">Email</label>
            <input name="email"
                   id="emailField"
                   type="text"
                   value="<?php echo $record["email"]; ?>"
                   size="35"
                   autocomplete="off">
        </fieldset>
        <fieldset>
            <label>Club</label>
            <?php
            if ($_SESSION["__userLevel__"] < 10) {
                $requeteSQLClub = "SELECT * FROM clubs ORDER BY club";
                $recordsetClub = mysql_query($requeteSQLClub) or die ("<H1>mauvaise requete</H1>");

                echo "<select name='idClub'>";

                while ($recordClub = mysql_fetch_array($recordsetClub)) {

                    $club = $recordClub["club"];
                    if ($club == "") {
                        $club = VAR_LANG_NON_SPECIFIE;
                    }

                    if ($recordClub["id"] == $record["idClub"]) {
                        echo "<option selected value='" . $recordClub["id"] . "'>" . $club . "</option>";
                    } else {
                        echo "<option value='" . $recordClub["id"] . "'>" . $club . "</option>";
                    }

                }
                echo "</select>";
            } // interdiction de modifier le club
            else {
                $requeteSQLClub = "SELECT * FROM `Personne`, `clubs` WHERE `Personne`.`id`='" . $record["id"] . "' AND `Personne`.`idClub`=`clubs`.`id`";
                $recordsetClub = mysql_query($requeteSQLClub) or die ("<H1>mauvaise requete</H1>");

                $recordClub = mysql_fetch_array($recordsetClub);
                $club = $recordClub["club"];
                if ($club == "") {
                    $club = VAR_LANG_NON_SPECIFIE;
                }
                echo "<input name='idClub' type='hidden' value='" . $recordClub["id"] . "'>";
                // affiche le club
                echo "<div class=\"givenData\">" . $club . "</div>";
            }

            ?>
        </fieldset>
        <fieldset>
            <span class="st-form__side-info tooltip">Pour changer de mot de passe, sinon laisser vide. Minimum 8 caractères</span>
            <label for="ancienPass">Ancien mot de passe</label>
            <input id="ancienPass" name="ancienPass" type="password" maxlength="255" size="35" autocomplete="off">
            <label for="nouveauPass">Nouveau mot de passe</label>
            <input id="nouveauPass" name="nouveauPass" type="password" maxlength="255" size="35" autocomplete="off">
            <label for="nouveauPassBis">Encore une fois</label>
            <input id="nouveauPassBis" name="nouveauPassBis" type="password" maxlength="255" size="35" autocomplete="off">
        </fieldset>
        <input type="hidden" name="action" value="modifier">
        <input type="submit" class="button button--primary" value="modifier">
    </form>
</div>
