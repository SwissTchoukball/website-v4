<?php

if ($_POST['postType'] == "newClub" || $_POST['postType'] == "editClub") {
    $rawClub = [
        'id' => isValidClubID($_POST['clubID']) ? $_POST['clubID'] : null,
        'shortName' => validiteInsertionTextBd($_POST['shortName']),
        'fullName' => validiteInsertionTextBd($_POST['fullName']),
        'sortName' => validiteInsertionTextBd($_POST['sortName']),
        'address' => validiteInsertionTextBd($_POST['address']),
        'npa' => isValidNPA($_POST['npa']) ? $_POST['npa'] : 'NULL',
        'city' => validiteInsertionTextBd($_POST['city']),
        'cantonId' => validiteInsertionTextBd($_POST['Canton']),
        'phoneNumbber' => validiteInsertionTextBd($_POST['phone']),
        'email' => validiteInsertionTextBd($_POST['email']),
        'officialCommMailingList' => validiteInsertionTextBd($_POST['emailsOfficialComm']),
        'tournamentsCommMailingList' => validiteInsertionTextBd($_POST['emailsTournamentComm']),
        'url' => validiteInsertionTextBd($_POST['url']),
        'committeeComposition' => validiteInsertionTextBd($_POST['committeeComposition']),
        'coachJSID' => isValidID($_POST['coachJSID']) ? $_POST['coachJSID'] : 'NULL',
        'statusId' => isValidID($_POST['clubs_status']) ? $_POST['clubs_status'] : 3,
    ];
    $club = new Club($rawClub);

    // Erreur. Si c'était un ajout, on veut afficher le formulaire pour nouveau club,
    // sinon on affiche le formulaire de modification du club.
    if ($nbError > 0) {
        echo "<p class='notification notification--error'>Procédure annulée.</p>";
    }
    // Pas d'erreur. On vérifie bien que c'est une personne autorisée qui procède à l'ajout ou la modification
    else if ($_SESSION['__userLevel__'] <= 0 ||
        (!$club->isNewClub() && $_SESSION['__idClub__'] == $club->id && $_SESSION['__gestionMembresClub__'])) {

        // New club to add, only by admins
        if ($_POST['postType'] == "newClub" && $_SESSION['__userLevel__'] <= 0) {
            try {
                $club->id = ClubService::addClub($club);
                printSuccessMessage("Insértion réussie.");
            } catch (Exception $exception) {
                printErrorMessage("Erreur lors de l'insertion dans la base de données.<br>" . $exception->getMessage());
                $nbError++;
            }
        } elseif ($_POST['postType'] == "editClub") { // Modification of an already existing club
            try {
                ClubService::editClub($club);

                printSuccessMessage("Modification réussie.");

                if (!$devWebsite) {
                    // Sending notification email
                    $from = "From:no-reply@tchoukball.ch\n";
                    $from .= "MIME-version: 1.0\n";
                    $from .= "Content-type: text/html; charset= iso-8859-1\n";
                    $destinataireMail = "resp.communication@tchoukball.ch";
                    mail($destinataireMail, "Modification club", "Les club " . $shortName . " a été modifié.", $from);
                }
            } catch (Exception $exception) {
                printErrorMessage(
                    "Erreur lors de la modification dans la base de données." .
                    " Contactez le <a href='mailto:webmaster@tchoukball.ch'>webmaster</a>.<br>" .
                    $exception->getMessage()
                );
                $nbError++;
            }
        } else {
            printErrorMessage("Action indéfinie.");
            //Ne devrait pas arriver
        }
    } else {
        printErrorMessage("Vous n'avez pas le droit d'effectuer cet action.");
    }
} else {
    printErrorMessage("Action inconnue");
}
