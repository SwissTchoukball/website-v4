<?php
statInsererPageSurf(__FILE__);
?>
<div>
    <?php

    if ($_POST["action"] == "inserer") {

        $nom = validiteInsertionTextBd($_POST["nom"]);
        $prenom = validiteInsertionTextBd($_POST["prenom"]);
        $username = validiteInsertionTextBd($_POST["username"]);
        $password = md5($_POST["motDePasse"]);
        $email = validiteInsertionTextBd($_POST["email"]);
        $idClub = validiteInsertionTextBd($_POST["clubs"]);

        $requeteSQL = "INSERT INTO `Personne` ( `nom` , `prenom`, `username`,
						`password` , `email` , `idClub`)
					VALUES (
						'$nom', '$prenom', '$username', '$password',
					 '$email', '$idClub')";


        if (mysql_query($requeteSQL) === false) {
            printErrorMessage("Erreur d'insertion. Contactez le webmaster.<br />" . mysql_error() . "<br />" . $requeteSQL);
        } else {
            printSuccessMessage("Insertion réussie");
        }
    }
    ?>

    <SCRIPT language='JavaScript'>

        function controlerSaisie() {

            var nbErreur;
            nbErreur = 0;

            if (mesInfos.email.value !== "" &&
                (
                    mesInfos.email.value.indexOf("@") < 1 ||
                    mesInfos.email.value.indexOf("@") >= (mesInfos.email.value.lastIndexOf("."))
                )
            ) {
                nbErreur++;
                mesInfos.email.classList.add('st-invalid');
            } else {
                mesInfos.email.classList.remove('st-invalid');
            }

            if (mesInfos.prenom.value.length === 0) {
                nbErreur++;
                mesInfos.prenom.classList.add('st-invalid');
            } else {
                mesInfos.prenom.classList.remove('st-invalid');
            }

            if (mesInfos.nom.value.length === 0) {
                nbErreur++;
                mesInfos.nom.classList.add('st-invalid');
            } else {
                mesInfos.nom.classList.remove('st-invalid');
            }

            if (mesInfos.username.value.length === 0) {
                nbErreur++;
                mesInfos.username.classList.add('st-invalid');
            } else {
                mesInfos.username.classList.remove('st-invalid');
            }

            if (mesInfos.motDePasse.value.length < 3) {
                nbErreur++;
                mesInfos.motDePasse.classList.add('st-invalid');
            } else {
                mesInfos.motDePasse.classList.remove('st-invalid');
            }

            if (mesInfos.contactClub.checked && mesInfos.idClub.value === 0) {
                nbErreur++;
                mesInfos.idClub.classList.add('st-invalid');
            } else {
                mesInfos.idClub.classList.remove('st-invalid');
            }

            return nbErreur === 0;
        }
    </SCRIPT>

    <form class="st-form" name="mesInfos" method="post"
          action="<?php echo "?menuselection=$menuselection&smenuselection=$smenuselection"; ?>"
          onSubmit="return controlerSaisie();">
        <label for="firstNameInput">Prénom</label>
        <input name="prenom" id="firstNameInput" type="text" value="" size="35" maxlength="35">
        <label for="lastNameInput">Nom</label>
        <input name="nom" id="lastNameInput" type="text" value="" size="35" maxlength="35">
        <label for="usernameInput">Nom d'utilisateur</label>
        <input name="username" id="usernameInput" type="text" value="" size="35" maxlength="35" autocomplete="off" autocorrect="off"
               autocapitalize="off" spellcheck="false">
        <br/>
        <label for="emailInput">E-mail</label>
        <input name="email" id="emailInput" type="text" value="" size="35" maxlength="80" autocomplete="off">
        <br/>
        <label>Club</label>
        <?php
        // attention, pour garder une validité des données, les présidents
        // de club ne peuvent pas modifier leur club s'il sont de simple utilsiateur

        /*$requeteSQLClub="SELECT * FROM clubs ORDER BY club";
        $recordsetClub = mysql_query($requeteSQLClub) or die ("<H1>mauvaise requete</H1>");

        echo "<select name='idClub'>";

            while($recordClub = mysql_fetch_array($recordsetClub)){

                $club = $recordClub["club"];
                if($club==""){
                    $club=VAR_LANG_NON_SPECIFIE;
                }

                if($recordClub["id"] == $record["idClub"]){
                    echo "<option selected value='".$recordClub["id"]."'>".$club."</option>";
                }
                else{
                    echo "<option value='".$recordClub["id"]."'>".$club."</option>";
                }

            }
        echo "</select>";	*/
        afficherListeClubs(0, "id");


        ?>
        <br/>
        <label for="passwordInput">Mot de passe<br/>(min 8 caractères)</label>
        <input name="motDePasse" id="passwordInput" type="password" maxlength="255" size="35" autocomplete="off">
        <br/>
        <input type="hidden" name="action" value="inserer">
        <input type="submit" class="button button--primary" value="insérer">
    </form>
</div>
