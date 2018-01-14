<?php
// Deleting user
if (isset($_GET['suppressionId']) && is_numeric($_GET['suppressionId'])) {
    UserService::deleteUser($_GET['suppressionId']);
    printSuccessMessage("Utilisateur supprimé");
}

// Editing user
if ($_POST["action"] == "modifierContact" AND isset($_POST['idPersonne'])) {
    try {
        UserService::updateUser($_POST['idPersonne'], $_POST["email"], $_POST["clubs"]);
        printSuccessMessage("Mise à jour de l'e-mail et du club réussi.");
    }
    catch (Exception $exception) {
        printErrorMessage($exception->getMessage());
    }

    if ($_POST["nouveauPass"] != "") {
        try {
            UserService::updatePassword($record["id"], $_POST['nouveauPass'], $_POST['nouveauPassBis']);
            printSuccessMessage('Modification du mot de passe réussi');
        }
        catch (Exception $e) {
            $errorMessage = $e->getMessage();
            if ($e->getCode() == 400) {
                $errorMessage .= ' Veuillez réessayer.';
            } else if ($e->getCode() == 500) {
                $errorMessage .= ' Contactez le <a href="mailto:webmaster@tchoukball.ch">webmaster</a>';
            }
            printErrorMessage($errorMessage);
        }
    }
}

if ($_SESSION["__userLevel__"] <= 5 && isset($_GET['modificationId']) && is_numeric($_GET['modificationId'])) {
    // Show form to edit a user
    $modificationId = $_GET['modificationId'];
    include "modifier.contact.inc.php";
}
else {
    // Show list of users
    include "carnet.adresse.inc.php";
}
