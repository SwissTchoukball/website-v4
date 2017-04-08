<?php
statInsererPageSurf(__FILE__);
?>

<?php
$requeteSQL = "SELECT *, p.adresse, p.ville, p.email, p.telephone FROM Personne p, ClubsFstb c WHERE p.id='" . $modificationId . "' AND p.idClub=c.id";
$recordset = mysql_query($requeteSQL) or die ("<H1>mauvaise requete A</H1>");
$record = mysql_fetch_array($recordset);

echo "<SCRIPT language='JavaScript'>
	 var couleurErreur; couleurErreur='#" . VAR_LOOK_COULEUR_ERREUR_SAISIE . "';
	 var couleurValide; couleurValide='#" . VAR_LOOK_COULEUR_SAISIE_VALIDE . "';
	 </SCRIPT>";
?>
<SCRIPT language='JavaScript'>

    function controlerSaisie() {

        var nbErreur;
        nbErreur = 0;

        mesInfos.adresse.style.background = couleurValide;
        mesInfos.ville.style.background = couleurValide;

        <?php include "includes/javascript.controle.telephone.inc.php"; ?>

        if (mesInfos.email.value != "" && (mesInfos.email.value.indexOf("@") < 1 || mesInfos.email.value.indexOf("@") >= (mesInfos.email.value.lastIndexOf(".")))) {
            nbErreur++;
            mesInfos.email.style.background = couleurErreur;
        }
        else {
            mesInfos.email.style.background = couleurValide;
        }

        var epressionReguliereMotDePasse = new RegExp(["^[a-zA-Z0-9_]{8,}"]);
        var invaliditePassword = !epressionReguliereMotDePasse.test(mesInfos.nouveauPass.value) && mesInfos.nouveauPass.value.length != 0
        if (mesInfos.nouveauPass.value != mesInfos.nouveauPassBis.value || invaliditePassword) {
            if (invaliditePassword) {
                alert("Les caractères spéciaux ne sont pas admis dans le mot de passe (sont également exclus les caractères à accents)");
            }
            mesInfos.nouveauPass.style.background = couleurErreur;
            mesInfos.nouveauPassBis.style.background = couleurErreur;
            nbErreur++;
        }
        else {
            mesInfos.nouveauPass.style.background = couleurValide;
            mesInfos.nouveauPassBis.style.background = couleurValide;
        }

        return nbErreur == 0;
    }
</SCRIPT>

<form name="mesInfos" class="st-form" method="post" onSubmit="return controlerSaisie();"
      action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>">
    <fieldset>
        <label>Nom d'utilisateur</label>
        <div class="givenData"><?php echo stripslashes($record["username"]); ?></div>
        <?php
        if (isAdmin()) {
            ?>
            <label>Niveau d'accès</label>
            <div class="givenData"><?php echo $record["userLevel"]; ?></div>
            <?php
        }
        ?>
        <label>Nom</label>
        <div class="givenData"><?php echo stripslashes($record["nom"]); ?></div>
        <label>Prénom</label>
        <div class="givenData"><?php echo stripslashes($record["prenom"]); ?></div>
    </fieldset>
    <fieldset>
        <label>Email</label>
        <input name="email" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
               value="<?php echo $record["email"]; ?>" size="35" maxlength="80">
    </fieldset>
    <fieldset>
        <label>Club</label>
        <?php
        afficherListeClubs($record["idClub"], "id");
        ?>
    </fieldset>
    <fieldset>
        <span class="st-form__side-info tooltip">Minimum 8 caractères</span>
        <label>Nouveau mot de passe</label>
        <input name="nouveauPass" type="password" maxlength="255" size="35" autocomplete="off">
        <label>Encore une fois</label>
        <input name="nouveauPassBis" type="password" maxlength="255" size="35" autocomplete="off">
    </fieldset>
    <input type="hidden" name="action" value="modifierContact">
    <input type="hidden" name="idPersonne" value="<?php echo $modificationId; ?>">
    <input type="submit" class="button button--primary" value="Modifier">
</form>
