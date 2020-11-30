<?php

if ($_POST['postType'] == "newMember" || $_POST['postType'] == "editMember") {

    // V�rification date de naissance
    if (checkdate($_POST['birthDateMonth'], $_POST['birthDateDay'], $_POST['birthDateYear'])) {
        $birthDate = $_POST['birthDateYear'] . "-" . $_POST['birthDateMonth'] . "-" . $_POST['birthDateDay'];
    } elseif ($_POST['birthDateMonth'] == 0 && $_POST['birthDateDay'] == 0 && $_POST['birthDateYear'] == 0 && $_POST['statutID'] != 2) {
        // Si la date de naissance n'est pas pr�cis� et le statut du membre n'est pas actif/junior, alors c'est ok.
        $birthDate = 'NULL';
    } else { //avertissement Javascript
        $birthDate = 'NULL';
        echo "<p class='notification notification--error'>Erreur dans la date de naissance.</p>";
        $nbError++;
    }

    //V�rification et calcul statut
    if ($_POST['statutID'] == 1) { // Non sp�cifi� (avertissement Javascript)
        $statutID = 3; //Membre actif
        echo "<p class='notification notification--error'>Statut non sp�cifi�.</p>";
        $nbError++;
    } elseif ($_POST['statutID'] != 2) { // Pas membre actif/junior
        $statutID = $_POST['statutID'];
    } elseif ($_POST['statutID'] == 2) { // Actif/Junior
        if ($nbError == 0) { //Pas calculable si erreur date
            if (date('Y') - $_POST['birthDateYear'] >= 21) {
                $statutID = 3; // Actif
            } else {
                $statutID = 6; // Junior
            }
        }
    } else {
        // Ne devrait jamais arriver.
    }


    //V�rification nom et pr�nom non vide
    $noName = false;
    $lastname = validiteInsertionTextBd(titleCase($_POST['lastname']));
    $firstname = validiteInsertionTextBd(titleCase($_POST['firstname']));
    $companyName = validiteInsertionTextBd($_POST['companyName']);
    if ($lastname == "" && $firstname == "" && $companyName != "") {
        //C'est ok de ne pas mettre de nom et pr�nom si raison sociale d�finie.
        $noName = true;
    } elseif ($lastname == "" && $firstname == "" && $companyName == "") {
        echo "<p class='notification notification--error'>Il faut pr�ciser un nom et un pr�nom OU une raison sociale.</p>";
    } elseif ($lastname == "" || $firstname == "") {
        echo "<p class='notification notification--error'>Nom ou pr�nom manquant.</p>";
        $nbError++;
    }

    if (!$noName) {
        // V�rification existance nom et pr�nom (+indication club)
        // TODO: G�rer de multiples personnes avec le m�me nom.
        $duplicateNameRequest = "SELECT club, idDbdPersonne FROM `DBDPersonne`, `clubs` WHERE `nom` LIKE '" . $lastname . "' AND `prenom` LIKE '" . $firstname . "' AND `nbIdClub`=`idClub` AND `idDbdPersonne`!=" . $_POST['memberID'] . " LIMIT 1";
        $duplicateNameResult = mysql_query($duplicateNameRequest);
        if (mysql_num_rows($duplicateNameResult) > 0) {
            $duplicateName = mysql_fetch_assoc($duplicateNameResult);
            echo "<p class='notification notification--error'>Un &laquo; " . $firstname . " " . $lastname . " &raquo; existe d�j� dans la base de donn�es et est membre du club &laquo; " . $duplicateName['club'] . " &raquo;.<br />";
            echo "<a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&transfer-request=" . $duplicateName['idDbdPersonne'] . "'>Demandez � le transf�rer</a>.</p>";
            $nbError++;
        }
    }


    $titleID = validiteInsertionTextBd($_POST['DBDCivilite']);
    $address1 = validiteInsertionTextBd($_POST['address1']);
    $address2 = validiteInsertionTextBd($_POST['address2']);
    $zipCode = validiteInsertionTextBd($_POST['zipCode']);
    $city = validiteInsertionTextBd($_POST['city']);
    $countryID = validiteInsertionTextBd($_POST['DBDPays']);
    $privatePhone = validiteInsertionTextBd($_POST['privatePhone']);
    $workPhone = validiteInsertionTextBd($_POST['workPhone']);
    $mobile = validiteInsertionTextBd($_POST['mobile']);
    $fax = validiteInsertionTextBd($_POST['fax']);
    $email = strtolower(validiteInsertionTextBd($_POST['email']));
    $emailFederation = strtolower(validiteInsertionTextBd($_POST['emailFederation']));
    $clubID = validiteInsertionTextBd($_POST['clubs']);
    $languageID = validiteInsertionTextBd($_POST['DBDLangue']);
    $sexID = validiteInsertionTextBd($_POST['DBDSexe']);
    $tchoukupID = validiteInsertionTextBd($_POST['DBDCHTB']);
    $refereeID = validiteInsertionTextBd($_POST['DBDArbitre']);
    $publicReferee = isset($_POST['arbitrePublic']) ? 1 : 0;
    if (isset($_POST['startCountingPointsOnEvenYears'])) {
        $startCountingPointsOnEvenYears = $_POST['startCountingPointsOnEvenYears'];
    } else {
        $startCountingPointsOnEvenYears = date('Y') % 2 + 1;
    }
    $suspended = isset($_POST['suspendu']) ? 1 : 0;
    $typeCompte = validiteInsertionTextBd($_POST['DBDTypeCompte']);
    $numeroCompte = validiteInsertionTextBd($_POST['numeroCompte']);
    $kidsSportCatId = validiteInsertionTextBd($_POST['kidsSportCat']);
    $remarques = validiteInsertionTextBd($_POST['remarques']);
    //Si membre passif => tchoukup = E-mail.
    if ($statutID == 4) {
        $tchoukupID = 5;
    }


    if ($nbError > 0) { // Erreur. Si c'�tait un ajout, on veut afficher le formulaire pour nouveau membre, sinon on affiche le formulaire de modification du membre.
        echo "<p class='notification notification--error'>Proc�dure annul�e.</p>";
        if ($_POST['memberID'] == 0) {
            $newMember = true;
        } else {
            $idMemberToEdit = $_POST['memberID'];
        }
    } else {
        if (hasAllMembersManagementAccess() || ($_SESSION['__nbIdClub__'] == $clubID && $_SESSION['__gestionMembresClub__'])) { // Pas d'erreur. On v�rifie bien que c'est une personne autoris�e qui proc�de � l'ajout ou la modification
            $newMember = false;
            if ($_POST['memberID'] == 0 && $_POST['postType'] == "newMember") { // New member to add
                $memberID = $_POST['memberID'];
                //TODO: Autoriser la modification du nom, pr�nom raison sociale que si ce n'est pas un b�n�vole Swiss Tchoukball
                //TODO: Autoriser la modification des coordonn�es que si ce n'est pas un membre du comit�.

                if (hasAllMembersManagementAccess()) {
                    $sensitiveAttributesForQuery =
                        ", `typeCompte`,
                           `numeroCompte`,
                           `remarque`";
                    $sensitiveValuesForQuery =
                        ", '" . $typeCompte . "',
                           '" . $numeroCompte . "',
                           '" . $remarques . "'";
                } else {
                    $sensitiveAttributesForQuery = "";
                    $sensitiveValuesForQuery = "";
                }


                $memberInsertRequest =
                    "INSERT INTO `DBDPersonne` (
                        `idStatus`,
                        `derniereModification`,
                        `modificationPar`,
                        `editor_id`,
                        `idClub`,
                        `idLangue`,
                        `idSexe`,
                        `idCivilite`,
                        `nom`,
                        `prenom`,
                        `adresse`,
                        `cp`,
                        `npa`,
                        `ville`,
                        `telPrive`,
                        `telProf`,
                        `portable`,
                        `fax`,
                        `email`,
                        `emailFederation`,
                        `dateNaissance`,
                        `raisonSociale`,
                        `idPays`,
                        `idCHTB`,
                        `kidsSportCatId`,
                        `dateAjout`
                        $sensitiveAttributesForQuery
                     )
                     VALUES (
                        '" . $statutID . "',
                        '" . date('Y-m-d') . "',
                        '" . $_SESSION['__nom__'] . " " . $_SESSION['__prenom__'] . "',
                        '" . $_SESSION['__idUser__'] . "',
                        '" . $clubID . "',
                        '" . $languageID . "',
                        '" . $sexID . "',
                        '" . $titleID . "',
                        '" . $lastname . "',
                        '" . $firstname . "',
                        '" . $address1 . "',
                        '" . $address2 . "',
                        '" . $zipCode . "',
                        '" . $city . "',
                        '" . $privatePhone . "',
                        '" . $workPhone . "',
                        '" . $mobile . "',
                        '" . $fax . "',
                        '" . $email . "',
                        '" . $emailFederation . "',
                        '" . $birthDate . "',
                        '" . $companyName . "',
                        '" . $countryID . "',
                        '" . $tchoukupID . "',
                        '" . $kidsSportCatId . "',
                        '" . date('Y-m-d') . "'
                        $sensitiveValuesForQuery
                     )";
                $memberInsertResult = mysql_query($memberInsertRequest);
                if ($memberInsertResult) { // Tout s'est bien pass�.
                    printSuccessMessage("Insertion de la personne r�ussie.");
                    $idMemberToEdit = mysql_insert_id();

                    // Insertion des informations d'arbitrage
                    if (hasRefereeManagementAccess() && $refereeID > 1) {
                        $startCountingPointsOnEvenYears = (date('Y') % 2) + 1;
                        $refereeDataInsertQuery =
                            "INSERT INTO Arbitres (personId, levelId, startCountingPointsOnEvenYears)
                             VALUES (" . $idMemberToEdit . ", " . $refereeID . ", " . $startCountingPointsOnEvenYears . ")";
                        if (!mysql_query($refereeDataInsertQuery)) {
                            printErrorMessage("Erreur lors de l'enregistrement des informations d'arbitre");
                        }
                    }

                } else {
                    $errorMessage = "<p class='notification notification--error'>Erreur lors de l'insertion dans la base de donn�es.";
                    $errorMessage .= "<br/>Requ�te: " . $memberInsertRequest;
                    $errorMessage .= "<br/>Message: " . mysql_error();
                    printErrorMessage($errorMessage);
                    $nbError++;
                    $newMember = true;
                }

            } elseif ($_POST['postType'] == "editMember") { // Modification of an already existing member
                $memberID = $_POST['memberID'];

                $memberUpdateRequest = "UPDATE DBDPersonne
                                    SET idStatus='" . $statutID . "',
                                        derniereModification='" . date('Y-m-d') . "',
                                        modificationPar='" . $_SESSION['__nom__'] . " " . $_SESSION['__prenom__'] . "',
                                        editor_id=" . $_SESSION["__idUser__"] . ",
                                        idLangue=" . $languageID . ",
                                        idSexe=" . $sexID . ",
                                        idCivilite=" . $titleID . ",
                                        nom='" . $lastname . "',
                                        prenom='" . $firstname . "',
                                        adresse='" . $address1 . "',
                                        cp='" . $address2 . "',
                                        npa='" . $zipCode . "',
                                        ville='" . $city . "',
                                        telPrive='" . $privatePhone . "',
                                        telProf='" . $workPhone . "',
                                        portable='" . $mobile . "',
                                        fax='" . $fax . "',
                                        email='" . $email . "',
                                        emailFederation='" . $emailFederation . "',
                                        dateNaissance='" . $birthDate . "',
                                        raisonSociale='" . $companyName . "',
                                        idPays='" . $countryID . "',
                                        idCHTB='" . $tchoukupID . "',
                                        kidsSportCatId=" . $kidsSportCatId;
                if (hasAllMembersManagementAccess()) {
                    $memberUpdateRequest .= ", idClub=" . $clubID . "
                                         , suspendu=" . $suspended . "
                                         , typeCompte='" . $typeCompte . "'
                                         , numeroCompte='" . $numeroCompte . "'
                                         , remarque='" . $remarques . "'";
                }
                $memberUpdateRequest .= " WHERE idDbdPersonne=" . $memberID;
                //echo "<p class='notification'>".$memberUpdateRequest."</p>";
                $memberUpdateResult = mysql_query($memberUpdateRequest);
                if ($memberUpdateResult) { // Tout s'est bien pass�.
                    echo "<p class='notification notification--success'>Modification r�ussie.</p>";

                    // Insertion des informations d'arbitrage
                    if (hasRefereeManagementAccess()) {
                        if ($refereeID > 1) {
                            // When updating a person, this could either be an insert or an update of the referee data
                            $refereeDataUpsertQuery =
                                "INSERT INTO Arbitres (personId, levelId, startCountingPointsOnEvenYears, public)
                                 VALUES (" . $memberID . ", " . $refereeID . ", " . $startCountingPointsOnEvenYears . ", " . $publicReferee . ")
                                 ON DUPLICATE KEY
                                 UPDATE levelId = " . $refereeID . ",
                                    startCountingPointsOnEvenYears = " . $startCountingPointsOnEvenYears . ",
                                    public = " . $publicReferee;
                            if (!mysql_query($refereeDataUpsertQuery)) {
                                printErrorMessage("Erreur lors de l'enregistrement des informations d'arbitre");
                            }
                        } else {
                            // The person is not a referee anymore.
                            // If it wasn't already before, this won't trigger an error
                            $refereeDataDeleteQuery = "DELETE FROM Arbitres WHERE personId = " . $memberID . " LIMIT 1";
                            if (!mysql_query($refereeDataDeleteQuery)) {
                                printErrorMessage("Erreur lors de l'enregistrement des informations d'arbitre");
                            }
                        }
                    }
                } else {
                    echo "<p class='notification notification--error'>Erreur lors de la modification dans la base de donn�es. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.</p>";
                    if ($_SESSION['__userLevel__'] == 0) {
                        printMessage(mysql_error());
                        printMessage($memberUpdateRequest);
                    }
                    $nbError++;
                }
                $idMemberToEdit = $_POST['memberID'];
            } else {
                echo '<p class="notification notification--error">Action ind�finie.</p>';
                //Ne devrait pas arriver
            }
        } else {
            echo "<p class='notification notification--error'>Vous n'avez pas le droit d'effectuer cet action.</p>";
        }
    }
} elseif ($_POST['postType'] == "transfer-request") {
    //On ne v�rifie pas que la personne qui fait la demande soit du club entrant ou sortant. Ce n'est qu'une demande.
    if (isValidClubID($_POST['currentClubID']) && isValidClubID($_POST['clubs'])) {
        $transferRequestQuery = "INSERT INTO `DBDRequetesChangementClub` (`userID`, `idDbdPersonne`, `from_clubID`, `to_clubID`, `datetime`)
                                 VALUES (" . $_SESSION['__idUser__'] . ", " . $_POST['memberID'] . ", " . $_POST['currentClubID'] . ", " . $_POST['clubs'] . ", '" . date('Y-m-d H:i:s') . "')";
        if (mysql_query($transferRequestQuery)) {
            $requestID = mysql_insert_id();
            //Envoi d'un e-mail pour avertir le webmaster
            $destinataireMail = "webmaster@tchoukball.ch";
            $objectMail = "Auto: Demande de transfert No " . $requestID;
            $messageMail = $_SESSION['__prenom__'] . " " . $_SESSION['__nom__'] . " demande un transfert pour <strong>" . htmlentities($_POST['memberName'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1') . "</strong>.<br /><br />";
            $messageMail .= "Club d'origine : <strong>" . htmlentities($_POST['currentClubName'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1') . "</strong><br /><br />";
            $messageMail .= "Nouveau club : <strong>" . htmlentities($_POST['newClubName'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1') . "</strong><br /><br />";
            $messageMail .= "<a href='https://tchoukball.ch/admin.php?menuselection=55&smenuselection=30'>Gestion des transferts</a>";
            $from = "From:no-reply@tchoukball.ch\n";
            $from .= "MIME-version: 1.0\n";
            $from .= "Content-type: text/html; charset= iso-8859-1\n";
            if (mail($destinataireMail, $objectMail, $messageMail, $from)) {
                echo '<p class="notification notification--success">Demande de transfert envoy�e. Elle sera trait�e prochainement et vous serez tenu inform� de son ex�cution.</p>';
                //echo '<p class="notification">mail('.$destinataireMail.', '.$objectMail.', '.$messageMail.', '.$from.')</p>';
            } else {
                echo '<p class="notification notification--error">La demande de transfert a �t� enregistr�e, mais d� � une erreur, le webmaster n\'a pas automatiquement �t� averti, veuillez <a href="mailto:webmaster@tchoukball.ch">le contacter</a> s\'il vous pla�t.</p>';
            }
        } else {
            echo '<p class="error">Erreur lors de l\'enregistrement de la demande de transfert.<br />' . mysql_error() . '</p>';
        }
    } else {
        echo '<p class="notification notification--error">ID invalide<br />' . htmlentities($_POST['currentClubID'] . ' ' . $_POST['clubs']) . '</p>';
    }
}
