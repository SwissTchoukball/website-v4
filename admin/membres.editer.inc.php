<script lang="javascript">

    var couleurErreur;
    couleurErreur = '#<?php echo VAR_LOOK_COULEUR_ERREUR_SAISIE; ?>';
    var couleurValide;
    couleurValide = '#<?php echo VAR_LOOK_COULEUR_SAISIE_VALIDE; ?>';

    $(function () {
        refereeLevelSelect = $("#DBDArbitre");
        refereeLevelSelect.change(updateRefereeFields);

        function updateRefereeFields() {
            if (refereeLevelSelect.val() > 1) {
                $("label[for=arbitrePublic]").show();
                $("#arbitrePublic").show();
                $("label[for=startCountingPointsOnEvenYears]").show();
                $("#startCountingPointsOnEvenYears").show();
            } else {
                $("label[for=arbitrePublic]").hide();
                $("#arbitrePublic").hide();
                $("label[for=startCountingPointsOnEvenYears]").hide();
                $("#startCountingPointsOnEvenYears").hide();
            }
        }

        updateRefereeFields();
    });

    function checkMemberForm() {

        var nbError = 0;

        //nom et prénom OU raison sociale
        if (memberEdit.lastname.value.length == 0 &&
            memberEdit.firstname.value.length == 0 &&
            memberEdit.companyName.value.length != 0) {
            memberEdit.lastname.style.background = couleurValide;
            memberEdit.firstname.style.background = couleurValide;
            memberEdit.companyName.style.background = couleurValide;
        } else if (memberEdit.lastname.value.length == 0 &&
            memberEdit.firstname.value.length == 0 &&
            memberEdit.companyName.value.length == 0) {
            memberEdit.lastname.style.background = couleurErreur;
            memberEdit.firstname.style.background = couleurErreur;
            memberEdit.companyName.style.background = couleurErreur;
            if (nbError == 0)memberEdit.lastname.focus();
            nbError++;
        } else {
            // nom
            if (memberEdit.lastname.value.length == 0) {
                memberEdit.lastname.style.background = couleurErreur;
                if (nbError == 0)memberEdit.lastname.focus();
                nbError++;
            }
            else {
                memberEdit.lastname.style.background = couleurValide;
            }

            // prenom
            if (memberEdit.firstname.value.length == 0) {
                memberEdit.firstname.style.background = couleurErreur;
                if (nbError == 0)memberEdit.firstname.focus();
                nbError++;
            }
            else {
                memberEdit.firstname.style.background = couleurValide;
            }
        }

        // NPA
        var regZipCode = new RegExp("^.*?[0-9]{4,}$", "g");

        if (memberEdit.zipCode.value.length > 0 && !regZipCode.test(memberEdit.zipCode.value)) {
            memberEdit.zipCode.style.background = couleurErreur;
            if (nbError == 0)memberEdit.zipCode.focus();
            nbError++;
        } else {
            memberEdit.zipCode.style.background = couleurValide;
        }

        // Ville
        /*
         // Il en fait valide qu'une ville puisse contenir des chiffres. p.ex. : Genève 26
         var regCityContainNumber = new RegExp("^.*[0-9]+.*$");

         if(memberEdit.city.value.length == 0 || regCityContainNumber.test(memberEdit.city.value)){
         memberEdit.city.style.background=couleurErreur;
         if(nbError==0)memberEdit.city.focus();
         nbError++;
         }
         else{
         memberEdit.city.style.background=couleurValide;
         }
         */

        //email
        var regEmail = new RegExp("^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$", "g");

        if (regEmail.test(memberEdit.email.value) || memberEdit.email.value == "") {
            memberEdit.email.style.background = couleurValide;
        }
        else {
            memberEdit.email.style.background = couleurErreur;
            if (nbError == 0)memberEdit.email.focus();
            alert("L'adresse e-mail est invalide. Elle peut contenir des caractères interdits.");
            nbError++;
        }

        //date de naissance
        var dateN = new Date(memberEdit.birthDateYear.value,
            memberEdit.birthDateMonth.value - 1,
            memberEdit.birthDateDay.value);

        if (memberEdit.birthDateYear.value == 0 &&
            memberEdit.birthDateMonth.value == 0 &&
            memberEdit.birthDateDay.value == 0 &&
            memberEdit.statutID.value != 2) {
            // Si la date de naissance n'est pas précisé et le statut du membre n'est pas actif/junior alors c'est ok.
            memberEdit.birthDateYear.style.background = couleurValide;
            memberEdit.birthDateMonth.style.background = couleurValide;
            memberEdit.birthDateDay.style.background = couleurValide;

        } else if (dateN.getFullYear() != memberEdit.birthDateYear.value ||
            (dateN.getMonth() != memberEdit.birthDateMonth.value - 1) ||
            dateN.getDate() != memberEdit.birthDateDay.value) {
            memberEdit.birthDateYear.style.background = couleurErreur;
            memberEdit.birthDateMonth.style.background = couleurErreur;
            memberEdit.birthDateDay.style.background = couleurErreur;
            if (nbError == 0)memberEdit.birthDateMonth.focus();
            nbError++;
        } else {
            memberEdit.birthDateYear.style.background = couleurValide;
            memberEdit.birthDateMonth.style.background = couleurValide;
            memberEdit.birthDateDay.style.background = couleurValide;
        }

        //statut
        if (memberEdit.statutID.value == 1) {
            memberEdit.statutID.style.background = couleurErreur;
            if (nbError == 0)memberEdit.statutID.focus();
            nbError++;
        } else {
            memberEdit.statutID.style.background = couleurValide;
        }

        return nbError == 0;
    }

    function restreindreNumeroTelFax(input) {
        var posCurseur = input.selectionStart;
        var newString = "";
        var regExpSpace = new RegExp("[\,-\.\;\:_\*]", "g");
        var regExpDelete = new RegExp("[a-zA-Z]", "g");
        for (var i = 0; i < input.value.length; i++) {
            if (input.value.charAt(i).match(regExpSpace)) {
                newString += " ";
            }
            else if (input.value.charAt(i).match(regExpDelete)) {
                newString += "";
            }
            else {
                newString += input.value.charAt(i);
            }
        }
        input.value = newString;
        input.selectionStart = posCurseur;
        input.selectionEnd = posCurseur;
    }

    function autoStatutUpdate() {
        autoStatut = document.getElementById("autoStatut");
        if (memberEdit.statutID.value == 2) {
            if (<?php echo date('Y'); ?> -memberEdit.birthDateYear.value >= 21) {
                autoStatut.innerHTML = "Membre actif";
            } else {
                autoStatut.innerHTML = "Membre junior";
            }
        } else {
            autoStatut.innerHTML = "<button type='button' onclick='resetBirthdate();'>Réinitialiser</button>";
        }

        if (memberEdit.statutID.value == 4) { // Membre passif
            memberEdit.DBDCHTB.value = 5; // TUP E-mail
            memberEdit.DBDCHTB.disabled = true;
        } else {
            memberEdit.DBDCHTB.disabled = false;
        }
        updateTchoukupDelivery();
    }

    function resetBirthdate() {
        memberEdit.birthDateYear.value = 0;
        memberEdit.birthDateMonth.value = 0;
        memberEdit.birthDateDay.value = 0;
    }

    function updateAddressPreview() {
        addressPreview = document.getElementById("addressPreview");
        companyName = document.getElementById("companyName");
        title = document.getElementById("DBDCivilite");
        firstname = document.getElementById("firstname");
        lastname = document.getElementById("lastname");
        address1 = document.getElementById("address1");
        address2 = document.getElementById("address2");
        zipCode = document.getElementById("zipCode");
        city = document.getElementById("city");
        country = document.getElementById("DBDPays");

        var text = "<strong>Aperçu de l'adresse</strong> :<br />";
        if (companyName.value != "") {
            text += companyName.value + "<br />";
        }

        if (firstname.value != "" || lastname.value != "") {
            if (title.value != 1) {
                text += title.options[title.selectedIndex].innerHTML + "<br />";
            }
            text += firstname.value + " " + lastname.value + "<br />";
        }

        text += address1.value + "<br />";

        if (address2.value != "") {
            text += address2.value + "<br />";
        }

        text += zipCode.value + " " + city.value + "<br />";

        if (country.value != "42") {
            text += country.options[country.selectedIndex].innerHTML;
        }

        addressPreview.innerHTML = text;
    }

    function updateTchoukupDelivery() {
        tupd = $("#tchoukupDelivery");
        if (memberEdit.DBDCHTB.value == 2) { // TUP Papier + E-mail
            tupd.show();
            if (memberEdit.statutID.value == 2) { // Membre actif ou junior
                tupd.find("p").html("tchouk<sup>up</sup> envoyé au club");
            } else {
                tupd.find("p").html("tchouk<sup>up</sup> envoyé à l'adresse indiqué ci-dessus.");
            }
        } else {
            tupd.hide();
        }
    }
</script>
<?php
/** @var integer $idMemberToEdit */

$canEdit = false;
$canDelete = false;

if ($newMember) {
    $formLegend = "Nouveau membre";
    $sendButtonValue = VAR_LANG_INSERER;
    $postType = "newMember";
    $canEdit = true;

    $memberID = 0;
    if ($nbError == 0) { //Initialisation uniquement si premier remplissage.
        $statutID = 3; //Membre actif
        if (hasAllMembersManagementAccess()) {
            $clubID = 15;
        } else {
            $clubID = $_SESSION['__nbIdClub__'];
        }
        $languageID = 1; //Français
        $sexID = 1; //Non spécifié
        $titleID = 2; //Monsieur
        $lastname = "";
        $firstname = "";
        $address1 = "";
        $address2 = "";
        $zipCode = "";
        $city = "";
        $privatePhone = "";
        $workPhone = "";
        $mobile = "";
        $fax = "";
        $email = "";
        $emailFSTB = "";
        $birthDate = "0000-00-00";
        $companyName = "";
        $countryID = 42; //Suisse
        $tchoukupID = 2; //Oui
        $refereeLevelId = "1";
        $refereeLevelName = "Pas arbitre";
        $isPublicReferee = true;
        $startCountingPointsOnEvenYears = date('Y') % 2 + 1;
        $isSuspended = false;
        $isCommitteeMember = false;
        $isCommissionMember = false;
        $isSwissTeamMember = false;
        $isJSExpert = false;
        $typeCompte = 1;
        $numeroCompte = "";
        $remarques = "";
    }
} else {
    // Le LIMIT 1 de la requête permet de n'avoir qu'une seule entrée car il pourrait y en avoir plusieurs si le
    // membre a, par exemple, été a plusieurs postes au comité. Pour nous il est juste intéressant de savoir s'il a
    // été au comité, mais pas à quels postes.
    // Toute modification de cette requête devrait être aussi appliquée dans le fichier membres.supprimer.inc.php
    // TODO: Make it DRY
    $memberRequest = "SELECT idStatus, derniereModification, modificationPar, p.idClub, idLangue, idSexe, idCivilite,
                             nom, prenom, adresse, cp, npa, ville, telPrive, telProf, portable, fax, email, emailFSTB,
                             dateNaissance, raisonSociale, idPays, idCHTB, a.levelId AS niveauArbitreID,
                             dbda.descriptionArbitre" . $_SESSION['__langue__'] . " AS niveauArbitre, a.public AS arbitrePublic,
                             a.startCountingPointsOnEvenYears,
                             suspendu, typeCompte, numeroCompte, remarque, c.idFonction AS idFonctionComite,
                             cm.idNom AS idCommissionMembre, cn.id AS idCommissionResponsable,
                             cnm.idEquipe AS idEquipeMembre, exp.idPersonne AS idExpert,
                             cj.id AS idParticipationChampionnat,
                             ce.idEquipe AS idEquipeChampionnatResponsable
                      FROM DBDPersonne p
                      LEFT OUTER JOIN Arbitres a ON p.idDbdPersonne = a.personId
                      LEFT OUTER JOIN DBDArbitre dbda ON a.levelId = dbda.idArbitre
                      LEFT OUTER JOIN Comite_Membres c ON p.idDbdPersonne = c.idPersonne
                      LEFT OUTER JOIN Commission_Membre cm ON p.idDbdPersonne = cm.idPersonne
                      LEFT OUTER JOIN Commission_Nom cn ON p.idDbdPersonne = cn.idResponsable
                      LEFT OUTER JOIN CadreNational_Membres cnm ON p.idDbdPersonne = cnm.idPersonne
                      LEFT OUTER JOIN ExpertsJS exp ON p.idDbdPersonne = exp.idPersonne
                      LEFT OUTER JOIN Championnat_Joueurs cj ON p.idDbdPersonne = cj.personId
                      LEFT OUTER JOIN Championnat_Equipes ce ON p.idDbdPersonne = ce.idResponsable
                      WHERE idDbdPersonne=" . $idMemberToEdit . "
                      LIMIT 1";
    $memberResult = mysql_query($memberRequest);
    if (!$memberResult) {
        printErrorMessage('Erreur lors de la récupération des données du membre.<br />
                           Message : ' . mysql_error() . '<br />
                           Requête : ' . $memberRequest);
    } else {
        $member = mysql_fetch_assoc($memberResult);
        if (($_SESSION['__nbIdClub__'] == $member['idClub'] && $_SESSION["__gestionMembresClub__"]) ||
            hasAllMembersManagementAccess()
        ) {
            if (hasAllMembersManagementAccess()) {
                $canDelete = true;
            } else {
                // Any changes to the definition of deletion period should be applied in membres.supprimer.inc.php
                // TODO: Make it DRY

                // Retrieving information to know if we are in the deletion period
                $deletionPeriodQuery =
                    "SELECT ccLastYear.datePaiement AS datePaiementAnneePassee, c.delaiSupprimerMembres
                     FROM Cotisations c
                     LEFT OUTER JOIN Cotisations_Clubs ccLastYear
                      ON ccLastYear.annee = c.annee - 1
                      AND ccLastYear.idClub = " . $member['idClub'] . "
                     WHERE c.annee <= '" . date('Y') . "'
                     ORDER BY c.annee DESC
                     LIMIT 1";

                $deletionPeriodResult = mysql_query($deletionPeriodQuery);
                $deletionPeriodData = mysql_fetch_assoc($deletionPeriodResult);

                $today = date('Y-m-d');

                $canDelete = $today < $deletionPeriodData['delaiSupprimerMembres'] &&
                    $deletionPeriodData['datePaiementAnneePassee'] != null;
            }

            $sendButtonValue = VAR_LANG_MODIFIER;
            $postType = "editMember";
            $canEdit = true;

            $memberID = $idMemberToEdit;
            $statutID = $member['idStatus'];
            $lastEditBy = $member['modificationPar'];
            $lastEdit = $member['derniereModification'];
            $clubID = $member['idClub'];
            $languageID = $member['idLangue'];
            $sexID = $member['idSexe'];
            $titleID = $member['idCivilite'];
            $lastname = $member['nom'];
            $firstname = $member['prenom'];
            $address1 = $member['adresse'];
            $address2 = $member['cp'];
            $zipCode = $member['npa'];
            $city = $member['ville'];
            $privatePhone = $member['telPrive'];
            $workPhone = $member['telProf'];
            $mobile = $member['portable'];
            $fax = $member['fax'];
            $email = $member['email'];
            $emailFSTB = $member['emailFSTB'];
            $birthDate = $member['dateNaissance'] == null ? '0000-00-00' : $member['dateNaissance'];
            $companyName = $member['raisonSociale'];
            $countryID = $member['idPays'];
            $tchoukupID = $member['idCHTB'];
            $refereeLevelId = $member['niveauArbitreID'] == null ? 1 : $member['niveauArbitreID'];
            $refereeLevelName = $member['niveauArbitre'] == null ? 'Pas arbitre' : $member['niveauArbitre'];
            if ($member['arbitrePublic'] != null) {
                $isPublicReferee = $member['arbitrePublic'] == 1;
            } else {
                $isPublicReferee = true;
            }
            if ($member['startCountingPointsOnEvenYears'] != null) {
                $startCountingPointsOnEvenYears = $member['startCountingPointsOnEvenYears'] == 1;
            } else {
                $startCountingPointsOnEvenYears = date('Y') % 2 + 1;
            }
            $isSuspended = $member['suspendu'] == 1;
            $isCommitteeMember = $member['idFonctionComite'] != null;
            $isCommissionMember = $member['idCommissionMembre'] != null || $member['idCommissionResponsable'] != null;
            $isSwissTeamMember = $member['idEquipeMembre'] != null;
            $isJSExpert = $member['idExpert'] != null;
            $isChampionshipPlayer = $member['idParticipationChampionnat'] != null;
            $isChampionshipTeamManager = $member['idEquipeChampionnatResponsable'] != null;
            $typeCompte = $member['typeCompte'];
            $numeroCompte = $member['numeroCompte'];
            $remarques = $member['remarque'];
            $formLegend = "Modification de ";
            if ($firstname != "" && $lastname != "") {
                $name .= $firstname . " " . $lastname;
            } else {
                $name .= $companyName;
            }
            $formLegend .= $name;
        } else {
            printErrorMessage("Vous n'êtes pas le responsable de la gestion des membres du club de la personne que vous
                               souhaitez éditer.");
        }
    }
}
if ($isSuspended) {
    printMessage($name . ' est suspendu des activités ' . VAR_LANG_ASSOCIATION_NAME . '.
                 Vous ne pouvez donc pas modifier ses informations.');
    $canEdit = false || hasAllMembersManagementAccess();
}

if ($canEdit) {
    // Any changes to the definition of the variable $isInvolvedInFederation should be applied in membres.supprimer.inc.php
    // TODO: Make it DRY
    $isInvolvedInFederation = $refereeLevelId > 1 ||
        $isCommitteeMember ||
        $isCommissionMember ||
        $isSwissTeamMember ||
        $isJSExpert ||
        $isChampionshipPlayer ||
        $isChampionshipTeamManager;
    ?>
    <h3><?php echo $formLegend; ?></h3>
    <form method="post"
          onsubmit="return checkMemberForm();"
          name="memberEdit"
          action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&details"
          class="adminForm">
        <fieldset>
            <?php
            if ($isInvolvedInFederation && !hasAllMembersManagementAccess()) {
                $canEditName = false;
            } else {
                $canEditName = true;
                echo '<span class="infobulle">Donner soit le nom et le prénom, soit la raison sociale,
                      ou bien les deux.</span>';
            }
            ?>
            <label for="companyName">Raison sociale</label>
            <input type="text" id="companyName" id="companyName" onkeyup="updateAddressPreview();" name="companyName"
                   value="<?php echo $companyName; ?>" <?php echo $canEditName ? '' : 'readonly="readonly"'; ?> />
            <label for="DBDCivilite">Civilité</label>
            <?php
            afficherdropDownListeDesactivable(
                "DBDCivilite",
                "idCivilite",
                "descriptionCivilite",
                $titleID,
                true,
                !$canEditName
            );
            ?>
            <label for="firstname">Prénom</label>
            <input type="text" id="firstname" name="firstname" onkeyup="updateAddressPreview();"
                   value="<?php echo $firstname; ?>" <?php echo $canEditName ? '' : 'readonly="readonly"'; ?> />
            <label for="lastname">Nom</label>
            <input type="text" id="lastname" name="lastname" onkeyup="updateAddressPreview();"
                   value="<?php echo $lastname; ?>" <?php echo $canEditName ? '' : 'readonly="readonly"'; ?> />
        </fieldset>
        <fieldset>
            <?php
            if ($isCommitteeMember && !hasAllMembersManagementAccess()) {
                $canEditDetails = false;
            } else {
                $canEditDetails = true;
                echo '<span class="infobulle">Au moins la première des deux lignes d\'adresse doit être remplie.
                      Mettre le numéro sur la même ligne que la rue.</span>';
            }
            ?>
            <span id="addressPreview"><!-- rempli avec du Javascript --></span>
            <label for="address1">Adresse</label>
            <input type="text" id="address1" name="address1" onkeyup="updateAddressPreview();"
                   value="<?php echo $address1; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label></label>
            <input type="text" id="address2" name="address2" onkeyup="updateAddressPreview();"
                   value="<?php echo $address2; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label for="zipCode">NPA</label>
            <input type="text" id="zipCode" name="zipCode" onkeyup="updateAddressPreview();"
                   value="<?php echo $zipCode; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label for="city">Ville</label>
            <input type="text" id="city" name="city" onkeyup="updateAddressPreview();"
                   value="<?php echo $city; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label for="DBDPays">Pays</label>
            <?php

            $requeteSQLOptions = "SELECT * FROM DBDPays ORDER BY descriptionPays" . $_SESSION["__langue__"];
            $recordsetOptions = mysql_query($requeteSQLOptions) or
            die("<H1>afficherListe: mauvaise requete sur : $nomIdOption </H1>");

            echo "<select id='DBDPays' name='DBDPays' onchange='updateAddressPreview();'";
            echo $canEditDetails ? '' : 'disabled="disabled"';
            echo " >";

            while ($recordOption = mysql_fetch_array($recordsetOptions)) {
                $option = $recordOption["descriptionPays" . $_SESSION["__langue__"]];
                if ($option == "") {
                    $option = VAR_LANG_NON_SPECIFIE;
                }

                if ($recordOption['idPays'] == $countryID) {
                    echo "<option selected value='" . $recordOption['idPays'] . "'>" . $option . "</option>";
                } else {
                    echo "<option value='" . $recordOption['idPays'] . "'>" . $option . "</option>";
                }
            }
            echo "</select>";

            ?>
        </fieldset>
        <fieldset>
            <label for="privatePhone">Tél. privé</label>
            <input type="text" id="privatePhone" name="privatePhone" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $privatePhone; ?>" <?php echo $canEditDetails ? '' : 'disabled="disabled"'; ?> />
            <label for="workPhone">Tél. prof.</label>
            <input type="text" id="workPhone" name="workPhone" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $workPhone; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label for="mobile">Tél. port.</label>
            <input type="text" id="mobile" name="mobile" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $mobile; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <!--<label>Fax</label>-->
            <input type="hidden" id="fax" name="fax" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $fax; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label for="email">E-mail</label>
            <input type="text" id="email" id="email" name="email" value="<?php echo $email; ?>"/>
            <?php
            if ($emailFSTB != '') {
                ?>
                <label for="emailFSTB">E-mail FSTB</label>
                <?php
                if (hasAllMembersManagementAccess()) {
                    echo '<input type="text" id="emailFSTB" id="emailFSTB" name="emailFSTB" value="' . $emailFSTB . '" />';
                } else {
                    echo '<p><a href="mailto:' . $emailFSTB . '">' . $emailFSTB . '</a></p>';
                    echo '<input type="hidden" name="emailFSTB" value="' . $emailFSTB . '" />';
                }
            }
            ?>
        </fieldset>
        <fieldset>
            <span class="infobulle">Pour les membres actifs et juniors, la date de naissance est obligatoire.</span>
            <label for="statutID">Statut</label>
            <?php
            // Out of the deletion period, we can change the status of a member only if he's not active.
            if ($canDelete || ($statutID != 3 && $statutID != 6) || $postType == 'newMember') {
                ?>
                <select id="statutID" name="statutID" onchange="autoStatutUpdate();">
                    <option value="1">Non spécifié</option>
                    <option value="2"<?php echo ($statutID == 3 || $statutID == 6) ? "selected" : ""; ?>>
                        Membre actif/junior
                    </option>
                    <?php
                    $queryStatut = "SELECT `idStatus` AS id, `descriptionStatus" . $_SESSION['__langue__'] . "` AS nom
                                        FROM `DBDStatus`
                                        WHERE `idStatus`!=1 AND `idStatus`!=3 AND `idStatus`!=6 ORDER BY nom";
                    if ($dataStatut = mysql_query($queryStatut)) {
                        while ($statut = mysql_fetch_assoc($dataStatut)) {
                            if ($statutID == $statut['id']) {
                                $statutSelected = "selected";
                            } else {
                                $statutSelected = "";
                            }
                            if (hasAllMembersManagementAccess() ||
                                $statut['id'] == 4 ||
                                $statut['id'] == 5 ||
                                $statut['id'] == 23
                            ) {
                                echo '<option value="' . $statut['id'] . '" ' . $statutSelected . '>' . $statut['nom'] . '</option>';
                            }
                        }
                    } else {
                        echo '<option value="null">ERREUR</option>';
                    }
                    ?>
                </select>
                <?php
            } else {
                echo '<p class="givenData">Membre actif/junior</p>';
                echo '<input type="hidden" name="statutID" value="' . $statutID . '" />';
            }
            ?>
            <label for="birthDateDay">Date de naiss.</label>
            <div class="birthDate">
                <select id="birthDateDay" name="birthDateDay" onchange="autoStatutUpdate();">
                    <option value="0">-</option>
                    <?php echo modif_liste_jour(jour($birthDate)); ?>
                </select>

                <select id="birthDateMonth" name="birthDateMonth" onchange="autoStatutUpdate();">
                    <option value="0">-</option>
                    <?php echo modif_liste_mois(mois($birthDate)); ?>
                </select>

                <select id="birthDateYear" name="birthDateYear" onchange="autoStatutUpdate();">
                    <option value="0">-</option>
                    <?php
                    for ($i = date('Y'); $i >= 1900; $i--) {
                        if ($i == annee($birthDate)) {
                            echo "<option selected value='$i'>$i</option>\n";
                        } else {
                            echo "<option value='$i'>$i</option>\n";
                        }
                    }
                    ?>
                </select>
                <span id="autoStatut">
                    <?php
                    if (($statutID == 3 || $statutID == 6) && $birthDate != "0000-00-00") {
                        if (date('Y') - annee($birthDate) >= 21) {
                            echo "Membre actif";
                        } else {
                            echo "Membre junior";
                        }
                    }
                    ?>
                </span>
            </div>
            <label for="DBDSexe">Genre</label>
            <?php
            afficherdropDownListe("DBDSexe", "idSexe", "descriptionSexe", $sexID, true);
            ?>
            <label for="DBDLangue">Langue</label>
            <?php
            afficherdropDownListe("DBDLangue", "idLangue", "descriptionLangue", $languageID, true);

            if (hasAllMembersManagementAccess()) {
                ?>
                <label>Club</label>
                <?php
                afficherListeClubs($clubID, "nbIdClub");
            } else {
                ?>
                <input type="hidden" name="ClubsFstb" value="<?php echo $clubID; ?>"/>
                <?php
            }
            ?>
            <label for="DBDCHTB">tchouk<sup>up</sup></label>
            <?php
            $requeteSQLOptions = "SELECT * FROM DBDCHTB ORDER BY descriptionCHTB" . $_SESSION["__langue__"];
            $recordsetOptions = mysql_query($requeteSQLOptions) or
            die("<H1>afficherListe: mauvaise requete sur : idCHTB </H1>");

            ?>
            <select id="DBDCHTB" name='DBDCHTB' <?php echo $statutID == 4 ? "readonly='readonly'" : ""; ?>
                    onchange="updateTchoukupDelivery();">
                <?php

                while ($recordOption = mysql_fetch_array($recordsetOptions)) {
                    $option = $recordOption["descriptionCHTB" . $_SESSION["__langue__"]];
                    if ($option == "") {
                        $option = VAR_LANG_NON_SPECIFIE;
                    }

                    if ($recordOption['idCHTB'] == $tchoukupID) {
                        echo "<option selected value='" . $recordOption['idCHTB'] . "'>" . $option . "</option>";
                    } else {
                        echo "<option value='" . $recordOption['idCHTB'] . "'>" . $option . "</option>";
                    }
                }
                ?>
            </select>
            <div id="tchoukupDelivery">
                <label></label>
                <p class="givenData"></p>
            </div>

            <label>Niveau d'arbitre</label>
            <?php
            if (hasRefereeManagementAccess()) {
                afficherdropDownListe("DBDArbitre", "idArbitre", "descriptionArbitre", $refereeLevelId, true);
            } else {
                echo '<p class="givenData">' . $refereeLevelName . '</p>';
                echo '<input type="hidden" name="idArbitre" value="' . $refereeLevelId . '" />';
            }
            ?>

            <label for="arbitrePublic">Arbitre public</label>
            <?php
            if (hasRefereeManagementAccess()) {
                if ($isPublicReferee) {
                    $publicRefereeChecked = 'checked';
                } else {
                    $publicRefereeChecked = '';
                }
                echo '<input type="checkbox" name="arbitrePublic" id="arbitrePublic" ' . $publicRefereeChecked . ' />';
            } else {
                echo '<p id="arbitrePublic" class="givenData">';
                if ($isPublicReferee) {
                    echo 'Oui';
                } else {
                    echo 'Non';
                }
                echo '</p>';
            }

            if (hasRefereeManagementAccess()) {
                ?>
                <label for="startCountingPointsOnEvenYears">Volée d'arbitres</label>
                <?php

                // Computing examples
                $currentYear = date('Y');
                $oneEvenYear = 0;
                $oneOddYear = 1;
                $isCurrentYearEven = $currentYear % 2 == 0;
                if ($isCurrentYearEven) {
                    $oneEvenYear = $currentYear;
                    $oneOddYear = $currentYear - 1;
                } else {
                    $oneEvenYear = $currentYear - 1;
                    $oneOddYear = $currentYear;
                }
                $evenYearsExample = $oneEvenYear . '-' . ($oneEvenYear + 2);
                $oddYearsExample = $oneOddYear . '-' . ($oneOddYear + 2);

                // Defining which one is selected
                $evenYearsSelected = '';
                $oddYearsSelected = '';
                if ($startCountingPointsOnEvenYears) {
                    $evenYearsSelected = 'selected="selected"';
                } else {
                    $oddYearsSelected = 'selected="selected"';
                }
                ?>
                <select name="startCountingPointsOnEvenYears" id="startCountingPointsOnEvenYears">
                    <option value="1" <?php echo $evenYearsSelected; ?>>Années paires
                        (p.ex. <?php echo $evenYearsExample; ?>)
                    </option>
                    <option value="0" <?php echo $oddYearsSelected; ?>>Années impaires
                        (p.ex. <?php echo $oddYearsExample; ?>)
                    </option>
                </select>
                <?php
            }

            if ($isSuspended || hasAllMembersManagementAccess()) {
                ?>
                <label>Suspendu</label>
                <?php
                if (hasAllMembersManagementAccess()) {
                    if ($isSuspended) {
                        $suspendedChecked = 'checked';
                    } else {
                        $suspendedChecked = '';
                    }
                    echo '<input type="checkbox" name="suspendu" ' . $suspendedChecked . ' />';
                } else {
                    echo '<p>Oui</p>';
                    //Si le membre n'est pas suspendu, on affiche rien.
                }
            }
            ?>
        </fieldset>
        <?php
        if (hasAllMembersManagementAccess()) {
            ?>
            <fieldset>
                <label>Type de compte</label>
                <?php afficherdropDownListe("DBDTypeCompte", "idTypeCompte", "TypeCompte", $typeCompte, false); ?>
                <label>Numéro de compte</label>
                <textarea name="numeroCompte"><?php echo $numeroCompte; ?></textarea>
            </fieldset>
            <fieldset>
                <label>Remarques</label>
                <textarea name="remarques"><?php echo $remarques; ?></textarea>
            </fieldset>
            <?php
        }
        ?>
        <input type="hidden" name="memberID" value="<?php echo $memberID; ?>"/>
        <input type="hidden" name="postType" value="<?php echo $postType; ?>"/>

        <input type="submit" value="<?php echo $sendButtonValue; ?>"/>
    </form>
    <?php
    if (!$newMember) {
        echo '<p><a href="?menuselection=' . $menuselection . '&smenuselection=' . $smenuselection .
            '&transfer-request=' . $memberID . '">Faire une demande pour transférer ' . $name . ' dans un autre club.
             </a></p>';

        if ($canDelete) {
            if ($isInvolvedInFederation) {
                echo '<p>' . $name . ' est, ou a été :</p>';
                echo '<ul>';
                echo $isCommitteeMember ? '<li>Membre du Comité exécutif</li>' : '';
                echo $isCommissionMember ? '<li>Membre d\'une Commission</li>' : '';
                echo $isJSExpert ? '<li>Expert J+S</li>' : '';
                echo $isSwissTeamMember ? '<li>Membre du Cadre national</li>' : '';
                echo $refereeLevelId > 1 ? '<li>' . $refereeLevelName . '</li>' : '';
                echo $isChampionshipPlayer ? '<li>Joueur de championnat</li>' : '';
                echo $isChampionshipTeamManager ? '<li>Responsable d\'équipe de championnat</li>' : '';
                echo '</ul>';
                echo '<p>et ne peut donc pas être supprimé.</p>';
            }

            if (!$isInvolvedInFederation) {
                ?>
                <p class="delete-member">
                    <a href="?menuselection=<?php echo $menuselection;
                    ?>&smenuselection=<?php echo $smenuselection;
                    ?>&delete=<?php echo $memberID; ?>"
                       onclick="return confirm('Voulez-vous vraiment supprimer <?php echo $name; ?> ?');">
                        Supprimer <?php echo $name; ?>
                    </a>
                    <span>
                        <strong>Attention !</strong> Il ne faut supprimer un membre que s'il sort complètement du
                        tchoukball.<br/>
                        Si le membre change de club, effectuez une demande de transfert (voir le lien ci-dessus).
                    </span>
                </p>
                <?php
            }
        }

        ?>
        <p>Dernière modification le <?php echo date_sql2date($lastEdit); ?> par <?php echo $lastEditBy; ?></p>
        <?php
    }
}
?>
<script lang="javascript">
    updateAddressPreview();
    autoStatutUpdate();
</script>
