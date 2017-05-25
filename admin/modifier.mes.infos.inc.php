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

        if ($_POST["ancienPass"] != "" || $_POST["nouveauPass"] != "") {
            if (md5($_POST["ancienPass"]) == $record["password"]) {
                $requeteModifierMotDePasse = "UPDATE `Personne` SET `password`='" . md5($_POST["nouveauPass"]) . "' WHERE `Personne`.`id`='" . $record["id"] . "'";
                mysql_query($requeteModifierMotDePasse);
                echo "<h4>Modification du mot de passe réussi</h4>";
            } else {
                echo "<h4>Votre ancien mot de passe n'est pas valide, impossible de modifier votre mot de passe</h4>";
            }
        }
    }
    ?>



    <?php
    $requeteSQL = "SELECT * FROM `Personne` WHERE `Personne`.`nom`='" . addslashes($_SESSION["__nom__"]) . "' AND `Personne`.`prenom`='" . addslashes($_SESSION["__prenom__"]) . "'";

    $recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete</H1>");

    $record = mysql_fetch_array($recordset);
    /*
    action="<?php echo VAR_HREF_PATH_ADMIN; ?>enregistrer.modification.infos.inc.php"
    */
    echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#" . VAR_LOOK_COULEUR_ERREUR_SAISIE . "';
	 var couleurValide; couleurValide='#" . VAR_LOOK_COULEUR_SAISIE_VALIDE . "';
	 </SCRIPT>";
    //<SCRIPT language='JavaScript'>
    ?>
    <SCRIPT language='JavaScript'>

        function controlerSaisie() {

            var nbErreur;
            nbErreur = 0;

            mesInfos.adresse.style.background = couleurValide;
            mesInfos.ville.style.background = couleurValide;
            mesInfos.ancienPass.style.background = couleurValide;

            if (mesInfos.email.value != "" && (mesInfos.email.value.indexOf("@") < 1 || mesInfos.email.value.indexOf("@") >= (mesInfos.email.value.lastIndexOf(".")))) {
                nbErreur++;
                mesInfos.email.style.background = couleurErreur;
            }
            else {
                mesInfos.email.style.background = couleurValide;
            }
            if (mesInfos.numPostal.value == "") {
                mesInfos.numPostal.style.background = couleurValide;
            }
            else {
                if (isNaN(mesInfos.numPostal.value) || mesInfos.numPostal.value < 1000 || mesInfos.numPostal.value.length != 4) {
                    nbErreur++;
                    mesInfos.numPostal.style.background = couleurErreur;

                }
                else {
                    mesInfos.numPostal.style.background = couleurValide;
                }
            }

            var epressionReguliereMotDePasse = new RegExp(["^[a-zA-Z0-9_]{8,}"]);
            var invaliditePassword = !epressionReguliereMotDePasse.test(mesInfos.nouveauPass.value) && mesInfos.nouveauPass.value.length != 0;
            if (mesInfos.nouveauPass.value != mesInfos.nouveauPassBis.value || invaliditePassword) {
                if (invaliditePassword)alert("Les caractères spéciaux ne sont pas admis dans le mot de passe (sont également exclus les caractères à accents)");
                nbErreur++;
                mesInfos.nouveauPass.style.background = couleurErreur;
                mesInfos.nouveauPassBis.style.background = couleurErreur;
            }
            else {
                mesInfos.nouveauPass.style.background = couleurValide;
                mesInfos.nouveauPassBis.style.background = couleurValide;
            }

            var dateN = new Date(mesInfos.annee.value, mesInfos.mois.value - 1, mesInfos.jour.value);
            if (dateN.getFullYear() != mesInfos.annee.value || (dateN.getMonth() != mesInfos.mois.value - 1) || dateN.getDate() != mesInfos.jour.value) {
                nbErreur++;
                mesInfos.annee.style.background = couleurErreur;
                mesInfos.mois.style.background = couleurErreur;
                mesInfos.jour.style.background = couleurErreur;
            }
            else {
                mesInfos.annee.style.background = couleurValide;
                mesInfos.mois.style.background = couleurValide;
                mesInfos.jour.style.background = couleurValide;
            }

            return nbErreur == 0;
        }
    </SCRIPT>

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
            <label>Email</label>
            <input name="email" type="text" value="<?php echo $record["email"]; ?>" size="35" maxlength="80"
                   autocomplete="off">
        </fieldset>
        <fieldset>
            <label>Club</label>
            <?php
            if ($_SESSION["__userLevel__"] < 10) {
                $requeteSQLClub = "SELECT * FROM ClubsFstb ORDER BY club";
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
                $requeteSQLClub = "SELECT * FROM `Personne`, `ClubsFstb` WHERE `Personne`.`id`='" . $record["id"] . "' AND `Personne`.`idClub`=`ClubsFstb`.`id`";
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
