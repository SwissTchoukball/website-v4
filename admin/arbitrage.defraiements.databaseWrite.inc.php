<?php

function sendPaymentMailToReferee($refereeId, $paymentDate, $amountPaid) {
    $refereeQuery = "SELECT p.prenom, p.email, p.emailFederation FROM DBDPersonne p WHERE p.idDbdPersonne = $refereeId LIMIT 1";
    $refereeResouce = mysql_query($refereeQuery);
    if (!$refereeResouce) {
        printErrorMessage("Erreur lors de la récupération des informations de l'arbitre. Il n'a pas pu être informé.");
        return;
    }

    $referee = mysql_fetch_assoc($refereeResouce);

    $refereeEmail = $referee['emailFederation'];
    if ($referee['emailFederation'] == '' && $referee['email'] != '') {
        $refereeEmail = $referee['email'];
    }

    $recipients = $refereeEmail . ", admin@tchoukball.ch";
    $subject = "Paiement défraiement arbitre";
    $body = "Bonjour " . $referee['prenom'] . ",<br><br>";
    $body .= "Nous avons envoyé l'ordre de paiement de <strong>CHF " . $amountPaid . "</strong> sur votre compte afin de vous défrayer pour vos arbitrages. ";
    $body .= "Le versement sera effectué <strong>" . date_sql2date_joli($paymentDate, "le", "Fr", false) . "</strong>.<br><br>";
    $body .= "Merci pour votre engagement en tant qu'arbitre de la fédération !<br><br>";
    $body .= "Salutations sportives<br><br>Swiss Tchoukball - Secteur finances";
    $from = "From:finances@tchoukball.ch\n";
    $from .= "MIME-version: 1.0\n";
    $from .= "Content-type: text/html; charset= iso-8859-1\n";
    if (mail($recipients, $subject, $body, $from)) {
        echo '<p class="notification notification--success">L\'arbitre a été informé par e-mail.</p>';
    } else {
        echo '<p class="notification notification--error">L\'arbitre n\'a pas pu être informé à cause d\'une erreur lors de l\'envoi de l\'e-mail. Le webmaster n\'a pas automatiquement été averti, veuillez <a href="mailto:webmaster@tchoukball.ch">le contacter</a> s\'il vous plaît.</p>';
    }
}

if (isset($_POST['add']) || isset($_POST['edit'])) {
    $paymentID = isValidID($_POST['paymentID']) ? $_POST['paymentID'] : false;
    $refereeID = isValidID($_POST['refereeID']) ? $_POST['refereeID'] : false;
    $seasonID = isValidSeasonID($_POST['seasonID']) ? $_POST['seasonID'] : false;
    $amountPaid = is_numeric($_POST['amountPaid']) ? $_POST['amountPaid'] : false;
    $year = substr($_POST['paymentDate'], 6, 4);
    $month = substr($_POST['paymentDate'], 3, 2);
    $day = substr($_POST['paymentDate'], 0, 2);
    if (checkdate($month, $day, $year)) {
        $paymentDate = $year . '-' . $month . '-' . $day;
    } else {
        $paymentDate = false;
    }

    //printMessage($refereeID  . ' - ' . $seasonID  . ' - ' . $amountPaid  . ' - ' . $paymentDate);
    if ($refereeID && $seasonID && $amountPaid && $paymentDate) {
        if (isset($_POST['add'])) {
            $queryAddPayment = "INSERT INTO Arbitres_Versements (idArbitre, saison, montantPaye, datePaiement, userID)
                               VALUES (" . $refereeID . ", " . $seasonID . ", " . $amountPaid . ", '" . $paymentDate . "', " . $_SESSION['__idUser__'] . ")";
            if (mysql_query($queryAddPayment)) {
                printSuccessMessage("Insertion du versement réussie.");
                sendPaymentMailToReferee($refereeID, $paymentDate, $amountPaid);
            } else {
                printErrorMessage("L'insertion du versement n'a pas aboutie.<br />" . mysql_error() . "<br />" . $queryAddPayment);
            }
        } elseif (isset($_POST['edit']) && $paymentID) {
            $queryEditPayment = "UPDATE Arbitres_Versements
                                SET idArbitre = " . $refereeID . ",
                                    saison = " . $seasonID . ",
                                    montantPaye = " . $amountPaid . ",
                                    datePaiement = '" . $paymentDate . "',
                                    userID = " . $_SESSION['__idUser__'] . "
                                WHERE id = " . $paymentID;
            if (mysql_query($queryEditPayment)) {
                printSuccessMessage("Modification du versement réussie.");
            } else {
                printErrorMessage("La modification du versement n'a pas aboutie.<br />" . mysql_error() . "<br />" . $queryEditPayment);
            }
        } else {
            // Comme il y a de toute façon add ou edit, arriver ici signifique que $paymentID est false.
            printErrorMessage("Versement à modifier indéfini");
        }
    } else {
        printErrorMessage("Formulaire invalide");
    }
} elseif (isset($_GET['delete'])) {
    if (isValidID($_GET['delete'])) {
        $queryDeletePayment = "DELETE FROM Arbitres_Versements WHERE id=" . $_GET['delete'];
        if (mysql_query($queryDeletePayment)) {
            printSuccessMessage("Suppression du versement effectuée");
        } else {
            printErrorMessage("Échec de la suppression du versement<br />" . mysql_error() . "<br />" . $queryDeletePayment);
        }
    } else {
        printErrorMessage("ID incorrect");
    }
}
