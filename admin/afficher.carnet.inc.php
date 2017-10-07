<?php
if (isset($_GET['modificationId']) && is_numeric($_GET['modificationId'])) {
    $modificationId = $_GET['modificationId'];
}
if (isset($_GET['suppressionId']) && is_numeric($_GET['suppressionId'])) {
    $suppressionId = $_GET['suppressionId'];
}
// page intermediaire pour les modifs
if ($_SESSION["__userLevel__"] <= 5 && $modificationId != "") {
    //|| $action=="modifierContact"
    include "modifier.contact.inc.php";
} // passe normale
else {
    // suppression
    if ($_SESSION["__userLevel__"] <= 5 && $suppressionId != "") {
        $requeteSQL = "DELETE FROM Personne WHERE id='" . $suppressionId . "'";
        mysql_query($requeteSQL) or die ("h4>Erreur de suppression</h4>");
    }
    // modification
    if ($_POST["action"] == "modifierContact" AND isset($_POST['idPersonne']) AND is_numeric($_POST['idPersonne'])) {
        $idPersonne = $_POST['idPersonne'];

        $requeteSQL = "SELECT * FROM Personne WHERE Personne.id='" . $idPersonne . "'";
        $recordset = mysql_query($requeteSQL);
        $record = mysql_fetch_array($recordset);

        $userLevel = 10;
        $email = validiteInsertionTextBd($_POST["email"]);
        $idClub = validiteInsertionTextBd($_POST["clubs"]);

        // selection pour le prochain affichage par lettre
        $lettre = substr($record["nom"], 0, 1);

        $queryUpdatePerson = "UPDATE `Personne` SET
                            `email`='$email',
                            `idClub`='$idClub'
                            WHERE Personne.id='" . $idPersonne . "'";
        if (mysql_query($queryUpdatePerson) === false) {
            printErrorMessage("Erreur de modification de l'e-mail et du club.");
        } else {
            printSuccessMessage("Mise à jour de l'e-mail et du club réussi.");
        }


        if ($_POST["nouveauPass"] != "") {
            try {
                updatePassword($record["id"], $_POST['nouveauPass'], $_POST['nouveauPassBis']);
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

    // affichage du carnet
    include "carnet.adresse.inc.php";
}
