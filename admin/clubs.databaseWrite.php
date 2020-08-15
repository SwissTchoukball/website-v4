<?php

if ($_POST['postType'] == "newClub" || $_POST['postType'] == "editClub") {

    $clubID = $_POST['clubID']; //validity already checked in clubs.inc.php
    $shortName = validiteInsertionTextBd($_POST['shortName']);
    $fullName = validiteInsertionTextBd($_POST['fullName']);
    $nameForSorting = validiteInsertionTextBd($_POST['nameForSorting']);
    $address = validiteInsertionTextBd($_POST['address']);
    if (isValidNPA($_POST['npa'])) {
        $npa = $_POST['npa'];
    } else {
        $npa = 'NULL';
    }
    $city = validiteInsertionTextBd($_POST['city']);
    $cantonID = validiteInsertionTextBd($_POST['Canton']);
    $status = isValidID($_POST['clubs_status']) ? $_POST['clubs_status'] : 3;
    $phone = validiteInsertionTextBd($_POST['phone']);
    $email = validiteInsertionTextBd($_POST['email']);
    $emailsOfficialComm = validiteInsertionTextBd($_POST['emailsOfficialComm']);
    $emailsTournamentComm = validiteInsertionTextBd($_POST['emailsTournamentComm']);
    $url = validiteInsertionTextBd($_POST['url']);
    $committeeComposition = validiteInsertionTextBd($_POST['committeeComposition']);
    $coachJSID = isValidID($_POST['coachJSID']) ? $_POST['coachJSID'] : 'NULL';

    if ($nbError > 0) {
        // Erreur. Si c'était un ajout, on veut afficher le formulaire pour nouveau club, sinon on affiche le formulaire de modification du club.
        echo "<p class='notification notification--error'>Procédure annulée.</p>";
        if ($clubID == 0) {
            $showClubCreationForm = true;
        } else {
            $clubToEditID = $clubID;
        }
    } else if ($_SESSION['__userLevel__'] <= 0 || ($clubID != 0 && $_SESSION['__idClub__'] == $clubID && $_SESSION['__gestionMembresClub__'])) {
        // Pas d'erreur. On vérifie bien que c'est une personne autorisée qui procède à l'ajout ou la modification
        $showClubCreationForm = false;
        if ($clubID == 0 && $_POST['postType'] == "newClub" && $_SESSION['__userLevel__'] <= 0) {
            /**
             * CREATING A NEW CLUB
             * Only by admins
             */

            //Getting the auto-increment value for the shitty club double ID
            $autoIncrementQuery = "SELECT `AUTO_INCREMENT`
									FROM  INFORMATION_SCHEMA.TABLES
									WHERE TABLE_SCHEMA = 'kuix_tchoukball1'
									AND   TABLE_NAME   = 'clubs'";
            $autoIncrementData = mysql_query($autoIncrementQuery);
            $autoIncrementArray = mysql_fetch_assoc($autoIncrementData);
            $autoIncrement = $autoIncrementArray['AUTO_INCREMENT'];
            $newClubID = $autoIncrement - 4;


            $memberID = $_POST['memberID'];
            $clubInsertRequest =
                "INSERT INTO `clubs`(
                    `id`,
                    `club`,
                    `nomComplet`,
                    `nomPourTri`,
                    `adresse`,
                    `npa`,
                    `ville`,
                    `canton`,
                    `telephone`,
                    `email`,
                    `emailsOfficialComm`,
                    `emailsTournamentComm`,
                    `url`,
                    `committeeComposition`,
                    `coachJSID`,
                    `statusId`,
                    `lastEdit`,
                    `lastEditorID`
                )
                VALUES (
                    '" . $newClubID . "',
                    '" . $shortName . "',
                    '" . $fullName . "',
                    '" . $nameForSorting . "',
                    '" . $address . "',
                    " . $npa . ",
                    '" . $city . "',
                    " . $cantonID . ",
                    '" . $phone . "',
                    '" . $email . "',
                    '" . $emailsOfficialComm . "',
                    '" . $emailsTournamentComm . "',
                    '" . $url . "',
                    '" . $committeeComposition . "',
                    " . $coachJSID . ",
                    " . $status . ",
                    '" . date('Y-m-d') . "',
                    " . $_SESSION['__idUser__'] . "
                )";
            $clubInsertResult = mysql_query($clubInsertRequest);
            if ($clubInsertResult) { // Tout s'est bien passé.
                echo "<p class='notification notification--success'>Insertion réussie.</p>";
                $clubToEditID = $newClubID;
            } else {
                echo "<p class='notification notification--error'>Erreur lors de l'insertion dans la base de données.</p>";
                $nbError++;
                $showClubCreationForm = true;
            }
        } elseif ($_POST['postType'] == "editClub") {
            /**
             * EDITING AN EXISTING CLUB
             */

            if (!$clubCurrentDataResult = mysql_query("SELECT * FROM clubs WHERE id={$clubID}")) {
                echo '<p class="notification notification--error">Erreur lors de le récupération des données actuelles du club.<br>' . mysql_error() . '</p>';
            } else {
                $clubCurrentData = mysql_fetch_object($clubCurrentDataResult);
            }

            $clubUpdateRequest = "UPDATE clubs
									SET adresse='" . $address . "',
										npa=" . $npa . ",
										ville='" . $city . "',
										telephone='" . $phone . "',
										email='" . $email . "',
										emailsOfficialComm='" . $emailsOfficialComm . "',
										emailsTournamentComm='" . $emailsTournamentComm . "',
										url='" . $url . "',
										committeeComposition='" . $committeeComposition . "',
										coachJSID=" . $coachJSID . ",
										lastEdit='" . date('Y-m-d') . "',
										lastEditorID=" . $_SESSION['__idUser__'];
            if ($_SESSION['__userLevel__'] <= 0) {
                $clubUpdateRequest .= ", club='" . $shortName . "'
										 , nomComplet='" . $fullName . "'
										 , nomPourTri='" . $nameForSorting . "'
										 , canton=" . $cantonID . "
										 , statusId=" . $status;
            }
            $clubUpdateRequest .= " WHERE id=" . $clubID;
            //echo "<p class='notification'>".$clubUpdateRequest."</p>";
            $clubUpdateResult = mysql_query($clubUpdateRequest);
            if ($clubUpdateResult) { // Tout s'est bien passé.
                echo "<p class='notification notification--success'>Modification réussie.</p>";

                /**
                 * Sending an email to Swiss Tchoukball to inform about the change.
                 * For this we need to know what changed.
                 */

                $clubNameForEmail = $shortName;
                $clubChanges = "<h4>Modifications</h4><p>";
                
                if (isset($clubCurrentData)) {
                    $clubNameForEmail = $clubCurrentData->club;
                    if ($clubCurrentData->address != $address || ($clubCurrentData->npa != $npa && $npa != 'NULL') || $clubCurrentData->ville != $city) {
                        $clubChanges .= "Adresse : " .
                        "{$clubCurrentData->address}, {$clubCurrentData->npa} {$clubCurrentData->ville}" .
                        " -> {$address}, {$npa} {$city}<br/>";
                    }
                    if ($clubCurrentData->telephone != $phone) {
                        $clubChanges .= "Numéro de télépone : {$clubCurrentData->telephone} -> {$phone}<br/>";
                    }
                    if ($clubCurrentData->email != $email) {
                        $clubChanges .= "Adresse email publique : {$clubCurrentData->email} -> {$email}<br/>";
                    }
                    if ($clubCurrentData->emailsOfficialComm != $emailsOfficialComm) {
                        $clubChanges .= "Adresses emails pour la communication officielle : {$clubCurrentData->emailsOfficialComm} -> {$emailsOfficialComm}<br/>";
                    }
                    if ($clubCurrentData->emailsTournamentComm != $emailsTournamentComm) {
                        $clubChanges .= "Adresses emails pour les tournois : {$clubCurrentData->emailsTournamentComm} -> {$emailsTournamentComm}<br/>";
                    }
                    if ($clubCurrentData->url != $url) {
                        $clubChanges .= "Site web : {$clubCurrentData->url} -> {$url}<br/>";
                    }
                    if ($clubCurrentData->committeeComposition != $_POST['committeeComposition']) {
                        // Here we compared directly with the query param because `validiteInsertionTextBd` was adding different line breaks
                        $clubChanges .= "Composition du comité :<br/>" . 
                        "<em>Avant</em> :<br/>" . nl2br($clubCurrentData->committeeComposition) . "<br/>" .
                        "<em>Après</em> :<br/>" . nl2br($_POST['committeeComposition']) . "<br/><br/>";
                    }
                    if ($clubCurrentData->coachJSID != $coachJSID) {
                        $clubChanges .= "Le coach J+S a été modifié<br/>";
                    }
                } else {
                    $clubChanges .= "Aucune information n'a pu être récupérée sur l'état du club avant les modifications";
                }
                
                $clubChanges .= "</p>";
                $body = "<p>Le club <strong>{$clubNameForEmail}</strong> a été modifié.</p>{$clubChanges}";

                // echo $body;

                $from = "From:no-reply@tchoukball.ch\n";
                $from .= "MIME-version: 1.0\n";
                $from .= "Content-type: text/html; charset= iso-8859-1\n";
                $destinataireMail = "resp.communication@tchoukball.ch";
                mail($destinataireMail, "Modification du {$clubNameForEmail}", $body, $from);
            } else {
                echo "<p class='notification notification--error'>Erreur lors de la modification dans la base de données. Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.</p>";
                //echo "<p class='notification'>" . mysql_error() . "</p>";
                $nbError++;
            }
            $clubToEditID = $clubID;
        } else {
            echo '<p class="notification notification--error">Action indéfinie.</p>';
            //Ne devrait pas arriver
        }
    } else {
        echo "<p class='notification notification--error'>Vous n'avez pas le droit d'effectuer cet action.</p>";
    }
} else {
    echo "<p class='notification notification--error'>Action inconnue</p>";
}
