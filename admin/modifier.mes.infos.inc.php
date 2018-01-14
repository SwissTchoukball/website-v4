<?php
statInsererPageSurf(__FILE__);
?>
<div>
    <?php
    $user = UserService::getUserById($_SESSION['__idUser__']);

    if ($_POST["action"] == "modifier") {
        UserService::updateUser($_SESSION['__idUser__'], $_POST["email"], $_POST["idClub"]);
        printSuccessMessage('Profil modifié.');

        // Password update
        if (is_string($_POST['nouveauPass']) &&
            strlen($_POST['nouveauPass']) > 0 &&
            md5($_POST["ancienPass"]) == $user["password"]) {
            try {
                UserService::updatePassword($_SESSION['__idUser__'], $_POST['nouveauPass'], $_POST['nouveauPassBis']);
                printSuccessMessage('Mot de passe modifié');
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
        } else if (strlen($_POST['nouveauPass']) > 0) {
            printErrorMessage('Votre ancien mot de passe n\'est pas valide.');
        }
    }

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

            if (mesInfos.nouveauPass.value !== mesInfos.nouveauPassBis.value ||
                (mesInfos.nouveauPass.value.length < 8 && mesInfos.nouveauPass.value.length > 0)) {
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
            <div class="givenData"><?php echo stripslashes($_SESSION["__username__"]); ?></div>
            <label>Nom</label>
            <div class="givenData"><?php echo stripslashes($_SESSION["__nom__"]); ?></div>
            <label>Prénom</label>
            <div class="givenData"><?php echo stripslashes($_SESSION["__prenom__"]); ?></div>
        </fieldset>
        <fieldset>
            <label for="emailField">Email</label>
            <input name="email"
                   id="emailField"
                   type="text"
                   value="<?php echo $user["email"]; ?>"
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

                    if ($recordClub["id"] == $user["idClub"]) {
                        echo "<option selected value='" . $recordClub["id"] . "'>" . $club . "</option>";
                    } else {
                        echo "<option value='" . $recordClub["id"] . "'>" . $club . "</option>";
                    }

                }
                echo "</select>";
            } // interdiction de modifier le club
            else {
                $clubName = $user["clubName"];
                if ($clubName == "") {
                    $clubName = VAR_LANG_NON_SPECIFIE;
                }
                echo "<input name='idClub' type='hidden' value='" . $user["idClub"] . "'>";
                // affiche le club
                echo "<div class=\"givenData\">" . $clubName . "</div>";
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
