<?php
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
