<?php
if (hasRefereeManagementAccess()) {
    if (isset($_POST['add']) || isset($_POST['edit'])) {
        $pointsID = isValidID($_POST['pointsID']) ? $_POST['pointsID'] : false;
        $refereeID = isValidID($_POST['refereeID']) ? $_POST['refereeID'] : false;
        $seasonID = isValidSeasonID($_POST['seasonID']) ? $_POST['seasonID'] : false;
        $pointsTypeID = isValidID($_POST['pointsTypeID']) ? $_POST['pointsTypeID'] : false;
        $points = is_numeric($_POST['points']) ? $_POST['points'] : false;
        $year = substr($_POST['date'], 6, 4);
        $month = substr($_POST['date'], 3, 2);
        $day = substr($_POST['date'], 0, 2);
        if (checkdate($month, $day, $year)) {
            $date = $year . '-' . $month . '-' . $day;
        } else {
            $date = false;
        }
        $description = mysql_real_escape_string(htmlentities($_POST['description']));

        //printMessage($refereeID  . ' - ' . $seasonID  . ' - ' . $pointsTypeID  . ' - ' . $points  . ' - ' . $date);
        if ($refereeID && $seasonID && $pointsTypeID && $points && $date) {
            if (isset($_POST['add'])) {
                $queryAddPoints = "INSERT INTO Arbitres_Points (idArbitre, idSaison, idTypePoints, points, date, description, creator, lastEditor)
								   VALUES (" . $refereeID . ", " . $seasonID . ", " . $pointsTypeID . ", " . $points . ", '" . $date . "', '" . $description . "', " . $_SESSION['__idUser__'] . ", " . $_SESSION['__idUser__'] . ")";
                if (mysql_query($queryAddPoints)) {
                    printSuccessMessage("Insertion des points réussie.");
                } else {
                    printErrorMessage("L'insertion des points n'a pas aboutie.<br />" . mysql_error() . "<br />" . $queryAddPoints);
                }
            } elseif (isset($_POST['edit']) && $pointsID) {
                $queryEditPoints = "UPDATE Arbitres_Points
									SET idArbitre = " . $refereeID . ",
										idSaison = " . $seasonID . ",
										idTypePoints = " . $pointsTypeID . ",
										points = " . $points . ",
										date = '" . $date . "',
										description = '" . $description . "',
										lastEditor = " . $_SESSION['__idUser__'] . "
									WHERE id = " . $pointsID;
                if (mysql_query($queryEditPoints)) {
                    printSuccessMessage("Modification des points réussie.");
                } else {
                    printErrorMessage("La modification des points n'a pas aboutie.<br />" . mysql_error() . "<br />" . $queryEditPoints);
                }
            } else {
                // Comme il y a de toute façon add ou edit, arriver ici signifique que $pointsID est false.
                printErrorMessage("Points à modifier indéfinis");
            }
        } else {
            printErrorMessage("Formulaire invalide");
        }
    } elseif (isset($_GET['delete'])) {
        if (isValidID($_GET['delete'])) {
            $queryDeletePoints = "DELETE FROM Arbitres_Points WHERE id=" . $_GET['delete'];
            if (mysql_query($queryDeletePoints)) {
                printSuccessMessage("Suppression des points effectuée");
            } else {
                printErrorMessage("Échec de la suppression des points<br />" . mysql_error() . "<br />" . $queryDeletePoints);
            }
        } else {
            printErrorMessage("ID incorrect");
        }
    }
}
