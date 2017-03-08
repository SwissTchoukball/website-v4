<?php

if ($_POST['postType'] == "newMember" || $_POST['postType'] == "editMember") {
    if (hasAllMembersManagementAccess()) {
        $idOrigineAdresse = 2; // Swiss Tchoukball
    } else {
        $idOrigineAdresse = 11; // Club
    }

    // Vérification date de naissance
    if (checkdate($_POST['birthDateMonth'], $_POST['birthDateDay'], $_POST['birthDateYear'])) {
        $birthDate = $_POST['birthDateYear'] . "-" . $_POST['birthDateMonth'] . "-" . $_POST['birthDateDay'];
    } elseif ($_POST['birthDateMonth'] == 0 && $_POST['birthDateDay'] == 0 && $_POST['birthDateYear'] == 0 && $_POST['statutID'] != 2) {
        // Si la date de naissance n'est pas précisé et le statut du membre n'est pas actif/junior, alors c'est ok.
        $birthDate = 'NULL';
    } else { //avertissement Javascript
        $birthDate = 'NULL';
        echo "<p class='notification notification--error'>Erreur dans la date de naissance.</p>";
        $nbError++;
    }

    //Vérification et calcul statut
    if ($_POST['statutID'] == 1) { // Non spécifié (avertissement Javascript)
        $statutID = 3; //Membre actif
        echo "<p class='notification notification--error'>Statut non spécifié.</p>";
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


    //Vérification nom et prénom non vide
    $noName = false;
    $lastname = ucwordspecific(strtolower(validiteInsertionTextBd($_POST['lastname'])), '-');
    $firstname = ucwordspecific(strtolower(validiteInsertionTextBd($_POST['firstname'])), '-');
    $companyName = validiteInsertionTextBd($_POST['companyName']);
    if ($lastname == "" && $firstname == "" && $companyName != "") {
        //C'est ok de ne pas mettre de nom et prénom si raison sociale définie.
        $noName = true;
    } elseif ($lastname == "" && $firstname == "" && $companyName == "") {
        echo "<p class='notification notification--error'>Il faut préciser un nom et un prénom OU une raison sociale.</p>";
    } elseif ($lastname == "" || $firstname == "") {
        echo "<p class='notification notification--error'>Nom ou prénom manquant.</p>";
        $nbError++;
    }

    if (!$noName) {
        // Vérification existance nom et prénom (+indication club)
        // TODO: Gérer de multiples personnes avec le même nom.
        $duplicateNameRequest = "SELECT club, idDbdPersonne FROM `DBDPersonne`, `ClubsFstb` WHERE `nom` LIKE '" . $lastname . "' AND `prenom` LIKE '" . $firstname . "' AND `nbIdClub`=`idClub` AND `idDbdPersonne`!=" . $_POST['memberID'] . " LIMIT 1";
        $duplicateNameResult = mysql_query($duplicateNameRequest);
        if (mysql_num_rows($duplicateNameResult) > 0) {
            $duplicateName = mysql_fetch_assoc($duplicateNameResult);
            echo "<p class='notification notification--error'>Un &laquo; " . $firstname . " " . $lastname . " &raquo; existe déjà dans la base de données et est membre du club &laquo; " . $duplicateName['club'] . " &raquo;.<br />";
            echo "<a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&transfer-request=" . $duplicateName['idDbdPersonne'] . "'>Demandez à le transférer</a>.</p>";
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
    $clubID = validiteInsertionTextBd($_POST['ClubsFstb']);
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


    if ($nbError > 0) { // Erreur. Si c'était un ajout, on veut afficher le formulaire pour nouveau membre, sinon on affiche le formulaire de modification du membre.
        echo "<p class='notification notification--error'>Procédure annulée.</p>";
        if ($_POST['memberID'] == 0) {
            $newMember = true;
        } else {
            $idMemberToEdit = $_POST['memberID'];
        }
    } else {
        if (hasAllMembersManagementAccess() || ($_SESSION['__nbIdClub__'] == $clubID && $_SESSION['__gestionMembresClub__'])) { // Pas d'erreur. On vérifie bien que c'est une personne autorisée qui procède à l'ajout ou la modification
            $newMember = false;
            if ($_POST['memberID'] == 0 && $_POST['postType'] == "newMember") { // New member to add
                $memberID = $_POST['memberID'];
                //TODO: Autoriser la modification du nom, prénom raison sociale que si ce n'est pas un bénévole Swiss Tchoukball
                //TODO: Autoriser la modification des coordonnées que si ce n'est pas un membre du comité.

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
                        `idOrigineAdresse`,
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
                        '" . $idOrigineAdresse . "',
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
                        '" . $birthDate . "',
                        '" . $companyName . "',
                        '" . $countryID . "',
                        '" . $tchoukupID . "',
                        '" . $kidsSportCatId . "',
                        '" . date('Y-m-d') . "'
                        $sensitiveValuesForQuery
                     )";
                $memberInsertResult = mysql_query($memberInsertRequest);
                if ($memberInsertResult) { // Tout s'est bien passé.
                    printSuccessMessage("Insertion de la personne réussie.");
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
                    $errorMessage = "<p class='notification notification--error'>Erreur lors de l'insertion dans la base de données.";
                    $errorMessage .= "<br/>Requête: " . $memberInsertRequest;
                    $errorMessage .= "<br/>Message: " . mysql_error();
                    printErrorMessage($errorMessage);
                    $nbError++;
                    $newMember = true;
                }

            } elseif ($_POST['postType'] == "editMember") { // Modification of an already existing member
                $memberID = $_POST['memberID'];

                $memberUpdateRequest = "UPDATE DBDPersonne
                                    SET idStatus='" . $statutID . "',
                                        idOrigineAdresse=11,
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
                if ($memberUpdateResult) { // Tout s'est bien passé.
                    echo "<p class='notification notification--success'>Modification réussie.</p>";

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
                    echo "<p class='notification notification--error'>Erreur lors de la modification dans la base de données. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.</p>";
                    if ($_SESSION['__userLevel__'] == 0) {
                        printMessage(mysql_error());
                        printMessage($memberUpdateRequest);
                    }
                    $nbError++;
                }
                $idMemberToEdit = $_POST['memberID'];
            } else {
                echo '<p class="notification notification--error">Action indéfinie.</p>';
                //Ne devrait pas arriver
            }
        } else {
            echo "<p class='notification notification--error'>Vous n'avez pas le droit d'effectuer cet action.</p>";
        }
    }
} elseif ($_POST['postType'] == "transfer-request") {
    //On ne vérifie pas que la personne qui fait la demande soit du club entrant ou sortant. Ce n'est qu'une demande.
    if (isValidClubID($_POST['currentClubID']) && isValidClubID($_POST['ClubsFstb'])) {
        $transferRequestQuery = "INSERT INTO `DBDRequetesChangementClub` (`userID`, `idDbdPersonne`, `from_clubID`, `to_clubID`, `datetime`)
                                 VALUES (" . $_SESSION['__idUser__'] . ", " . $_POST['memberID'] . ", " . $_POST['currentClubID'] . ", " . $_POST['ClubsFstb'] . ", '" . date('Y-m-d H:i:s') . "')";
        if (mysql_query($transferRequestQuery)) {
            $requestID = mysql_insert_id();
            //Envoi d'un e-mail pour avertir le webmaster
            $destinataireMail = "webmaster@tchoukball.ch";
            $objectMail = "Auto: Demande de transfert No " . $requestID;
            $messageMail = $_SESSION['__prenom__'] . " " . $_SESSION['__nom__'] . " demande un transfert pour <strong>" . htmlentities($_POST['memberName']) . "</strong>.<br /><br />";
            $messageMail .= "Club d'origine : <strong>" . htmlentities($_POST['currentClubName']) . "</strong><br /><br />";
            $messageMail .= "Nouveau club : <strong>" . htmlentities($_POST['newClubName']) . "</strong><br /><br />";
            $from = "From:no-reply@tchoukball.ch\n";
            $from .= "MIME-version: 1.0\n";
            $from .= "Content-type: text/html; charset= iso-8859-1\n";
            if (mail($destinataireMail, $objectMail, $messageMail, $from)) {
                echo '<p class="notification notification--success">Demande de transfert envoyée. Elle sera traitée prochainement et vous serez tenu informé de son exécution.</p>';
                //echo '<p class="notification">mail('.$destinataireMail.', '.$objectMail.', '.$messageMail.', '.$from.')</p>';
            } else {
                echo '<p class="notification notification--error">La demande de transfert a été enregistrée, mais dû à une erreur, le webmaster n\'a pas automatiquement été averti, veuillez <a href="mailto:webmaster@tchoukball.ch">le contacter</a> s\'il vous plaît.</p>';
            }
        } else {
            echo '<p class="error">Erreur lors de l\'enregistrement de la demande de transfert.<br />' . mysql_error() . '</p>';
        }
    } else {
        echo '<p class="notification notification--error">ID invalide<br />' . htmlentities($_POST['currentClubID'] . ' ' . $_POST['ClubsFstb']) . '</p>';
    }
}
