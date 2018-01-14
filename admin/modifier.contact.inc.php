<?php
statInsererPageSurf(__FILE__);
?>

<?php
$user = UserService::getUserById($modificationId)

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
            mesInfos.nouveauPass.classList.remove('st-invalid');
            mesInfos.nouveauPassBis.classList.remove('st-invalid');
        }

        return nbErreur === 0;
    }
</script>

<form name="mesInfos" class="st-form" method="post" onSubmit="return controlerSaisie();"
      action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
    <fieldset>
        <label>Nom d'utilisateur</label>
        <div class="givenData"><?php echo stripslashes($user["username"]); ?></div>
        <?php
        if (isAdmin()) {
            ?>
            <label>Niveau d'accès</label>
            <div class="givenData"><?php echo $user["userLevel"]; ?></div>
            <?php
        }
        ?>
        <label>Nom</label>
        <div class="givenData"><?php echo stripslashes($user["nom"]); ?></div>
        <label>Prénom</label>
        <div class="givenData"><?php echo stripslashes($user["prenom"]); ?></div>
    </fieldset>
    <fieldset>
        <label for="emailField">Email</label>
        <input name="email" id="emailField" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
               value="<?php echo $user["email"]; ?>" size="35" maxlength="80">
    </fieldset>
    <fieldset>
        <label>Club</label>
        <?php
        afficherListeClubs($user["idClub"], "id");
        ?>
    </fieldset>
    <fieldset>
        <span class="st-form__side-info tooltip">Minimum 8 caractères</span>
        <label for="nouveauPass">Nouveau mot de passe</label>
        <input name="nouveauPass" id="nouveauPass" type="password" maxlength="255" size="35" autocomplete="off">
        <label for="nouveauPassBis">Encore une fois</label>
        <input name="nouveauPassBis" id="nouveauPassBis" type="password" maxlength="255" size="35" autocomplete="off">
    </fieldset>
    <input type="hidden" name="action" value="modifierContact">
    <input type="hidden" name="idPersonne" value="<?php echo $modificationId; ?>">
    <input type="submit" class="button button--primary" value="Modifier">
</form>
