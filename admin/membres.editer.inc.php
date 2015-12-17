
<script lang="javascript">

    var couleurErreur;
    couleurErreur='#<?php echo VAR_LOOK_COULEUR_ERREUR_SAISIE; ?>';
    var couleurValide;
    couleurValide='#<?php echo VAR_LOOK_COULEUR_SAISIE_VALIDE; ?>';

    function checkMemberForm() {

        var nbError = 0;

        //nom et pr�nom OU raison sociale
        if (memberEdit.lastname.value.length == 0 &&
                memberEdit.firstname.value.length == 0 &&
                memberEdit.companyName.value.length != 0) {
            memberEdit.lastname.style.background=couleurValide;
            memberEdit.firstname.style.background=couleurValide;
            memberEdit.companyName.style.background=couleurValide;
        } else if(memberEdit.lastname.value.length == 0 &&
                memberEdit.firstname.value.length == 0 &&
                memberEdit.companyName.value.length == 0) {
            memberEdit.lastname.style.background=couleurErreur;
            memberEdit.firstname.style.background=couleurErreur;
            memberEdit.companyName.style.background=couleurErreur;
            if(nbError==0)memberEdit.lastname.focus();
            nbError++;
        } else {
            // nom
            if(memberEdit.lastname.value.length == 0){
                memberEdit.lastname.style.background=couleurErreur;
                if(nbError==0)memberEdit.lastname.focus();
                nbError++;
            }
            else{
                memberEdit.lastname.style.background=couleurValide;
            }

            // prenom
            if(memberEdit.firstname.value.length == 0){
                memberEdit.firstname.style.background=couleurErreur;
                if(nbError==0)memberEdit.firstname.focus();
                nbError++;
            }
            else{
                memberEdit.firstname.style.background=couleurValide;
            }
        }

        // NPA
        var regZipCode = new RegExp("^.*?[0-9]{4,}$","g");

        if(memberEdit.zipCode.value.length > 0 && !regZipCode.test(memberEdit.zipCode.value)){
            memberEdit.zipCode.style.background=couleurErreur;
            if(nbError==0)memberEdit.zipCode.focus();
            nbError++;
        } else{
            memberEdit.zipCode.style.background=couleurValide;
        }

        // Ville
        /*
        // Il en fait valide qu'une ville puisse contenir des chiffres. p.ex. : Gen�ve 26
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
        var regEmail = new RegExp("^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$","g");

        if(regEmail.test(memberEdit.email.value) || memberEdit.email.value==""){
            memberEdit.email.style.background=couleurValide;
        }
        else{
            memberEdit.email.style.background=couleurErreur;
            if(nbError==0)memberEdit.email.focus();
            alert("L'adresse e-mail est invalide. Elle peut contenir des caract�res interdits.");
            nbError++;
        }

        //date de naissance
        var dateN = new Date(memberEdit.birthDateYear.value,
                             memberEdit.birthDateMonth.value-1,
                             memberEdit.birthDateDay.value);

        if (memberEdit.birthDateYear.value == 0 &&
                memberEdit.birthDateMonth.value == 0 &&
                memberEdit.birthDateDay.value == 0 &&
                memberEdit.statutID.value != 2) {
            // Si la date de naissance n'est pas pr�cis� et le statut du membre n'est pas actif/junior alors c'est ok.
            memberEdit.birthDateYear.style.background=couleurValide;
            memberEdit.birthDateMonth.style.background=couleurValide;
            memberEdit.birthDateDay.style.background=couleurValide;

        } else if (dateN.getFullYear() != memberEdit.birthDateYear.value ||
                (dateN.getMonth() != memberEdit.birthDateMonth.value-1) ||
                dateN.getDate() != memberEdit.birthDateDay.value){
            memberEdit.birthDateYear.style.background=couleurErreur;
            memberEdit.birthDateMonth.style.background=couleurErreur;
            memberEdit.birthDateDay.style.background=couleurErreur;
            if(nbError==0)memberEdit.birthDateMonth.focus();
            nbError++;
        } else {
            memberEdit.birthDateYear.style.background=couleurValide;
            memberEdit.birthDateMonth.style.background=couleurValide;
            memberEdit.birthDateDay.style.background=couleurValide;
        }

        //statut
        if (memberEdit.statutID.value == 1) {
            memberEdit.statutID.style.background=couleurErreur;
            if(nbError==0)memberEdit.statutID.focus();
            nbError++;
        } else {
            memberEdit.statutID.style.background=couleurValide;
        }

        return nbError==0;
    }

    function restreindreNumeroTelFax(input) {
        var posCurseur = input.selectionStart;
        var newString = "";
        var regExpSpace=new RegExp("[\,-\.\;\:_\*]", "g");
        var regExpDelete=new RegExp("[a-zA-Z]", "g");
        for(var i=0;i<input.value.length;i++){
            if(input.value.charAt(i).match(regExpSpace)){
                newString += " ";
            }
            else if(input.value.charAt(i).match(regExpDelete)){
                newString += "";
            }
            else{
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
            if (<?php echo date('Y'); ?> - memberEdit.birthDateYear.value >= 21) {
                autoStatut.innerHTML = "Membre actif";
            } else {
                autoStatut.innerHTML = "Membre junior";
            }
        } else {
                autoStatut.innerHTML = "<button type='button' onclick='resetBirthdate();'>R�initialiser</button>";
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

        var text = "<strong>Aper�u de l'adresse</strong> :<br />";
        if (companyName.value != "") {
            text += companyName.value+"<br />";
        }

        if (firstname.value != "" || lastname.value != "") {
            if (title.value != 1) {
                text += title.options[title.selectedIndex].innerHTML+"<br />";
            }
            text += firstname.value+" "+lastname.value+"<br />";
        }

        text += address1.value+"<br />";

        if (address2.value != "") {
            text += address2.value+"<br />";
        }

        text += zipCode.value+" "+city.value+"<br />";

        if (country.value != "42") {
            text += country.options[country.selectedIndex].innerHTML;
        }

        addressPreview.innerHTML = text;
    }

    function updateTchoukupDelivery() {
        tupd = document.getElementById("tchoukupDelivery");
        if (memberEdit.DBDCHTB.value == 2) { // TUP Papier + E-mail
            $(tupd).show();
            if (memberEdit.statutID.value == 2) { // Membre actif ou junior
                tupd.innerHTML = "tchouk<sup>up</sup> envoy� au club";
            } else {
                tupd.innerHTML = "tchouk<sup>up</sup> envoy� � l'adresse indiqu� ci-dessus."
            }
        } else {
            $(tupd).hide();
        }
    }
</script>
<?php

$canEdit = false;

if ($newMember) {
    $memberID = 0;
    if ($nbError == 0) { //Initialisation uniquement si premier remplissage.
        $statutID = 3; //Membre actif
        if (hasAllMembersManagementAccess()) {
            $clubID = 15;
        } else {
            $clubID = $_SESSION['__nbIdClub__'];
        }
        $languageID = 1; //Fran�ais
        $sexID = 1; //Non sp�cifi�
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
        $isSuspended = false;
        $isCommitteeMember = false;
        $isCommissionMember = false;
        $isSwissTeamMember = false;
        $isJSExpert = false;
        $typeCompte = 1;
        $numeroCompte = "";
        $remarques = "";
    }
    $formLegend = "Nouveau membre";
    $sendButtonValue = VAR_LANG_INSERER;
    $postType = "newMember";
    $canEdit = true;
} else {
    // Le LIMIT 1 de la requ�te permet de n'avoir qu'une seule entr�e car il pourrait y en avoir plusieurs si le
    // membre a, par exemple, �t� a plusieurs postes au comit�. Pour nous il est juste int�ressant de savoir s'il a
    // �t� au comit�, mais pas � quels postes.
    $memberRequest = "SELECT idStatus, derniereModification, modificationPar, idClub, idLangue, idSexe, idCivilite,
                             nom, prenom, adresse, cp, npa, ville, telPrive, telProf, portable, fax, email, emailFSTB,
                             dateNaissance, raisonSociale, idPays, idCHTB, a.idArbitre AS niveauArbitreID,
                             a.descriptionArbitre".$_SESSION['__langue__']." AS niveauArbitre, arbitrePublic, suspendu,
                             typeCompte, numeroCompte, remarque, c.idFonction AS idFonctionComite,
                             cm.idNom AS idCommissionMembre, cn.id AS idCommissionResponsable,
                             cnm.idEquipe AS idEquipeMembre, exp.idPersonne AS idExpert
                      FROM DBDPersonne p
                      LEFT OUTER JOIN DBDArbitre a ON p.idArbitre = a.idArbitre
                      LEFT OUTER JOIN Comite_Membres c ON p.idDbdPersonne = c.idPersonne
                      LEFT OUTER JOIN Commission_Membre cm ON p.idDbdPersonne = cm.idPersonne
                      LEFT OUTER JOIN Commission_Nom cn ON p.idDbdPersonne = cn.idResponsable
                      LEFT OUTER JOIN CadreNational_Membres cnm ON p.idDbdPersonne = cnm.idPersonne
                      LEFT OUTER JOIN ExpertsJS exp ON p.idDbdPersonne = exp.idPersonne
                      WHERE idDbdPersonne=".$idMemberToEdit."
                      LIMIT 1";
    $memberResult = mysql_query($memberRequest);
    if (!$memberResult) {
        printErrorMessage('Erreur lors de la r�cup�ration des donn�es du membre.<br />
                           Message : ' . mysql_error() . '<br />
                           Requ�te : ' . $memberRequest);
    } else {
        $member = mysql_fetch_assoc($memberResult);
        if (($_SESSION['__nbIdClub__'] == $member['idClub'] && $_SESSION["__gestionMembresClub__"]) ||
                hasAllMembersManagementAccess()) {
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
            $refereeLevelId = $member['niveauArbitreID'];
            $refereeLevelName = $member['niveauArbitre'];
            $isPublicReferee = $member['arbitrePublic'] == 1;
            $isSuspended = $member['suspendu'] == 1;
            $isCommitteeMember = $member['idFonctionComite'] != null;
            $isCommissionMember = $member['idCommissionMembre'] != null || $member['idCommissionResponsable'] != null;
            $isSwissTeamMember = $member['idEquipeMembre'] != null;
            $isJSExpert = $member['idExpert'] != null;
            $typeCompte = $member['typeCompte'];
            $numeroCompte = $member['numeroCompte'];
            $remarques = $member['remarque'];
            $formLegend = "Modification de ";
            if ($firstname != "" && $lastname != "") {
                $name .= $firstname." ".$lastname;
            } else {
                $name .= $companyName;
            }
            $formLegend = $name;
            $sendButtonValue = VAR_LANG_MODIFIER;
            $postType = "editMember";
            $canEdit = true;
        } else {
            printErrorMessage("Vous n'�tes pas le responsable de la gestion des membres du club de la personne que vous
                               souhaitez �diter.");
        }
    }
}
if ($isSuspended) {
    printMessage($name . ' est suspendu des activit�s ' . VAR_LANG_ASSOCIATION_NAME . '.
                 Vous ne pouvez donc pas modifier ses informations.');
    $canEdit = false || hasAllMembersManagementAccess();
}

if ($canEdit) {
    $isFSTBVolunteer = $refereeLevelId > 1 ||
                       $isCommitteeMember ||
                       $isCommissionMember ||
                       $isSwissTeamMember ||
                       $isJSExpert;
    ?>
    <h3><?php echo $formLegend; ?></h3>
    <form method="post"
          onsubmit="return checkMemberForm();"
          name="memberEdit"
          action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>&details"
          class="adminForm">
        <fieldset>
            <?php
            if ($isFSTBVolunteer && !hasAllMembersManagementAccess()) {
                $canEditName = false;
            } else {
                $canEditName = true;
                echo '<span class="infobulle">Donner soit le nom et le pr�nom, soit la raison sociale,
                      ou bien les deux.</span>';
            }
            ?>
            <label for="companyName">Raison sociale</label>
            <input type="text" id="companyName" id="companyName" onkeyup="updateAddressPreview();" name="companyName"
                   value="<?php echo $companyName; ?>" <?php echo $canEditName ? '' : 'readonly="readonly"'; ?> />
            <label for="DBDCivilite">Civilit�</label>
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
            <label for="firstname">Pr�nom</label>
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
                echo '<span class="infobulle">Au moins la premi�re des deux lignes d\'adresse doit �tre remplie.
                      Mettre le num�ro sur la m�me ligne que la rue.</span>';
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

            $requeteSQLOptions="SELECT * FROM DBDPays ORDER BY descriptionPays".$_SESSION["__langue__"];
            $recordsetOptions = mysql_query($requeteSQLOptions) or
                                die("<H1>afficherListe: mauvaise requete sur : $nomIdOption </H1>");

            echo "<select id='DBDPays' name='DBDPays' onchange='updateAddressPreview();'";
            echo $canEditDetails ? '' : 'disabled="disabled"';
            echo " >";

            while ($recordOption = mysql_fetch_array($recordsetOptions)) {
                $option = $recordOption["descriptionPays".$_SESSION["__langue__"]];
                if ($option=="") {
                    $option=VAR_LANG_NON_SPECIFIE;
                }

                if ($recordOption['idPays'] == $countryID) {
                    echo "<option selected value='".$recordOption['idPays']."'>".$option."</option>";
                } else {
                    echo "<option value='".$recordOption['idPays']."'>".$option."</option>";
                }
            }
            echo "</select>";

            ?>
        </fieldset>
        <fieldset>
            <label for="privatePhone">T�l. priv�</label>
            <input type="text" id="privatePhone" name="privatePhone" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $privatePhone; ?>" <?php echo $canEditDetails ? '' : 'disabled="disabled"'; ?> />
            <label for="workPhone">T�l. prof.</label>
            <input type="text" id="workPhone" name="workPhone" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $workPhone; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label for="mobile">T�l. port.</label>
            <input type="text" id="mobile" name="mobile" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $mobile; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <!--<label>Fax</label>-->
            <input type="hidden" id="fax" name="fax" onKeyUp="restreindreNumeroTelFax(this);"
                   onChange="restreindreNumeroTelFax(this);"
                   value="<?php echo $fax; ?>" <?php echo $canEditDetails ? '' : 'readonly="readonly"'; ?> />
            <label for="email">E-mail</label>
            <input type="text" id="email" id="email" name="email" value="<?php echo $email; ?>" />
            <?php
            if ($emailFSTB != '') {
                ?>
                <label for="emailFSTB">E-mail FSTB</label>
                <?php
                if (hasAllMembersManagementAccess()) {
                    echo '<input type="text" id="emailFSTB" id="emailFSTB" name="emailFSTB" value="'.$emailFSTB.'" />';
                } else {
                    echo '<p><a href="mailto:'.$emailFSTB.'">'.$emailFSTB.'</a></p>';
                    echo '<input type="hidden" name="emailFSTB" value="'.$emailFSTB.'" />';
                }
            }
            ?>
        </fieldset>
        <fieldset>
            <span class="infobulle">Pour les membres actifs et juniors, la date de naissance est obligatoire.</span>
            <label for="statutID">Statut</label>
            <select id="statutID" name="statutID" onchange="autoStatutUpdate();">
                <option value="1">Non sp�cifi�</option>
                <option value="2"<?php echo ($statutID == 3 || $statutID == 6) ? "selected" : ""; ?>>
                    Membre actif/junior
                </option>
                <?php
                $queryStatut = "SELECT `idStatus` AS id, `descriptionStatus".$_SESSION['__langue__']."` AS nom
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
                            $statut['id'] == 23) {
                            echo '<option value="'.$statut['id'].'" '.$statutSelected.'>'.$statut['nom'].'</option>';
                        }
                    }
                } else {
                    echo '<option value="null">ERREUR</option>';
                }
                ?>
            </select>
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
                        if (date('Y')-annee($birthDate) >=  21) {
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
                <input type="hidden" name="ClubsFstb" value="<?php echo $clubID; ?>" />
                <?php
            }
            ?>
            <label for="DBDCHTB">tchouk<sup>up</sup></label>
            <?php
            $requeteSQLOptions="SELECT * FROM DBDCHTB ORDER BY descriptionCHTB".$_SESSION["__langue__"];
            $recordsetOptions = mysql_query($requeteSQLOptions) or
                                die("<H1>afficherListe: mauvaise requete sur : idCHTB </H1>");

            ?>
            <select id="DBDCHTB" name='DBDCHTB' <?php echo $statutID == 4 ? "readonly='readonly'":""; ?>
                    onchange="updateTchoukupDelivery();">
            <?php

            while ($recordOption = mysql_fetch_array($recordsetOptions)) {
                $option = $recordOption["descriptionCHTB".$_SESSION["__langue__"]];
                if ($option=="") {
                    $option=VAR_LANG_NON_SPECIFIE;
                }

                if ($recordOption['idCHTB'] == $tchoukupID) {
                    echo "<option selected value='".$recordOption['idCHTB']."'>".$option."</option>";
                } else {
                    echo "<option value='".$recordOption['idCHTB']."'>".$option."</option>";
                }
            }
            ?>
            </select>
            <div id="tchoukupDelivery" class="inlineinfo"></div>

            <label>Niveau d'arbitre</label>
            <?php
            if (hasAllMembersManagementAccess()) {
                afficherdropDownListe("DBDArbitre", "idArbitre", "descriptionArbitre", $refereeLevelId, true);
            } else {
                echo '<p>'.$refereeLevelName.'</p>';
                echo '<input type="hidden" name="idArbitre" value="'.$refereeLevelId.'" />';
            }

            if ($refereeLevelId > 1) {
                // Il n'est ainsi pas possible de d�finir si un membre est suspendu ou arbitre public lors de son
                // insertion ou lorsqu'il est fait arbitre.
                // Cela �vite aussi de peupler le formulaire de champs inutiles pour les non-arbitres
                ?>

                <label>Arbitre public</label>
                <?php
                if (hasAllMembersManagementAccess()) {
                    if ($isPublicReferee) {
                        $publicRefereeChecked = 'checked';
                    } else {
                        $publicRefereeChecked = '';
                    }
                    echo '<input type="checkbox" name="arbitrePublic" '.$publicRefereeChecked.' />';
                } else {
                    echo '<p>';
                    if ($isPublicReferee) {
                        echo 'Oui';
                    } else {
                        echo 'Non';
                    }
                    echo '</p>';
                }
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
                    echo '<input type="checkbox" name="suspendu" '.$suspendedChecked.' />';
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
                <label>Num�ro de compte</label>
                <textarea name="numeroCompte"><?php echo $numeroCompte; ?></textarea>
            </fieldset>
            <fieldset>
                <label>Remarques</label>
                <textarea name="remarques"><?php echo $remarques; ?></textarea>
            </fieldset>
            <?php
        }
        ?>
        <input type="hidden" name="memberID" value="<?php echo $memberID; ?>" />
        <input type="hidden" name="postType" value="<?php echo $postType; ?>" />

        <input type="submit" value="<?php echo $sendButtonValue; ?>" />
    </form>
    <?php
    if (!$newMember) {
        echo '<p><a href="?menuselection=' . $menuselection . '&smenuselection=' . $smenuselection .
             '&transfer-request=' . $memberID . '">Faire une demande pour transf�rer ' . $name . ' dans un autre club.
             </a></p>';

        if ($isFSTBVolunteer) {
            echo '<p>'.$name.' est, ou a �t� :</p>';
            echo '<ul>';
            echo $isCommitteeMember ? '<li>Membre du Comit� ex�cutif</li>' : '';
            echo $isCommissionMember ? '<li>Membre d\'une Commission</li>' : '';
            echo $isJSExpert ? '<li>Expert J+S</li>' : '';
            echo $isSwissTeamMember ? '<li>Membre du Cadre national</li>' : '';
            echo $refereeLevelId > 1 ? '<li>'.$refereeLevelName.'</li>' : '';
            echo '</ul>';
            echo '<p>et ne peut donc pas �tre supprim�.</p>';
        }

        if (!$isFSTBVolunteer) {
            ?>
            <p class="delete-member">
                <a href="?menuselection=<?php echo $menuselection;
                       ?>&smenuselection=<?php echo $smenuselection;
                       ?>&delete=<?php echo $memberID; ?>"
                   onclick="return confirm('Voulez-vous vraiment supprimer <?php echo $name; ?> ?');">
                    Supprimer <?php echo $name; ?>
                </a>
                <span>
                    <strong>Attention !</strong> Il ne faut supprimer un membre que s'il sort compl�tement du
                    tchoukball.<br />
                    Si le membre change de club, effectuez une demande de transfert (voir le lien ci-dessus).
                </span>
            </p>
            <?php

        }

        ?>
        <p>Derni�re modification le <?php echo date_sql2date($lastEdit);?> par <?php echo $lastEditBy;?></p>
        <?php
    }
}
?>
<script lang="javascript">
    updateAddressPreview();
    autoStatutUpdate();
</script>
