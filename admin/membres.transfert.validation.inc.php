<?php
function sendNotification(
        $transferingPersonName,
        $sourceClubName,
        $targetClubName,
        $authorName,
        $recipientsEmails,
        $transferStatusString) {
    array_push($recipientsEmails, 'webmaster@tchoukball.ch');
    $recipientsEmailsString = implode(',', array_unique($recipientsEmails));
    $objectMail = "Auto: Demande de transfert " . $transferStatusString . "e";
    $messageMail = "La demande de transfert pour <strong>" .
        htmlentities($transferingPersonName, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1') .
        "</strong> a été " . $transferStatusString . "e.<br /><br />";
    $messageMail .= "Club d'origine : " . $sourceClubName . "<br />";
    $messageMail .= "Club de destination : " . $targetClubName . "<br /><br />";
    $messageMail .= "Cette demande a été faite par " .
        htmlentities($authorName, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1') . ".<br /><br />";
    $from = "From:no-reply@tchoukball.ch\n";
    $from .= "MIME-version: 1.0\n";
    $from .= "Content-type: text/html; charset= iso-8859-1\n";
    if (!mail($recipientsEmailsString, $objectMail, $messageMail, $from)) {
        printErrorMessage('Erreur lors d l\'envoi de l\'e-mail de notification.');
    }
}

// Handling transfer validation
$transferAccepted = null;
if (isset($_GET['accept-transfer']) && is_numeric($_GET['accept-transfer'])) {
    $updatedTransferId = $_GET['accept-transfer'];
    $transferAccepted = 1;
} else if (isset($_GET['refuse-transfer']) && is_numeric($_GET['refuse-transfer'])) {
    $updatedTransferId = $_GET['refuse-transfer'];
    $transferAccepted = 0;
}

if (($transferAccepted === 1 || $transferAccepted === 0) && isset($updatedTransferId)) {
    // Getting transfer information
    $transferDataQuery = "
        SELECT rcc.from_clubID, rcc.to_clubID, rcc.idDbdPersonne, p.nom AS lastName, p.prenom AS firstName,
        u.email AS authorEmail, CONCAT_WS(' ', u.prenom, u.nom) AS authorName,
        cs.club AS sourceClubName, ct.club AS targetClubName 
        FROM DBDRequetesChangementClub rcc, DBDPersonne p, Personne u, clubs cs, clubs ct
        WHERE rcc.id = $updatedTransferId
        AND rcc.userID = u.id
        AND rcc.from_clubID = cs.nbIdClub
        AND rcc.to_clubID = ct.nbIdClub
        AND rcc.idDbdPersonne = p.idDbdPersonne
        AND rcc.accepted IS NULL
        LIMIT 1";
    if (!$transferDataResource = mysql_query($transferDataQuery)) {
        printErrorMessage(
            'Erreur lors de la récupérationd des informations du transfert.<br>' .
            mysql_error()
        );
        die();
    } else if (mysql_num_rows($transferDataResource) < 1) {
        printErrorMessage("La requête de transfert est inexistante ou a déjà été traitée.");
        die();
    } else {
        $transferData = mysql_fetch_assoc($transferDataResource);
        $sourceClubId = $transferData['from_clubID'];
        $targetClubId = $transferData['to_clubID'];
        $sourceClubName = $transferData['sourceClubName'];
        $targetClubName = $transferData['targetClubName'];
        $transferingPersonId = $transferData['idDbdPersonne'];
        $transferingPersonName = $transferData['firstName'] . ' ' . $transferData['lastName'];
        $authorEmail = $transferData['authorEmail'];
        $authorName = $transferData['authorName'];
    }

    // Doing the actual transfer if accepted
    if ($transferAccepted === 1) {
        $updateMemberRequest = "
            UPDATE DBDPersonne
            SET idClub = $targetClubId
            WHERE idDbdPersonne = $transferingPersonId
            LIMIT 1";

        if (!mysql_query($updateMemberRequest)) {
            printErrorMessage(
                'Erreur lors de la modification du statut du transfert.<br>' .
                mysql_error()
            );
            die();
        }
    }

    // Updating the transfer status
    $updateTransferRequestQuery = "
        UPDATE DBDRequetesChangementClub
        SET accepted = $transferAccepted
        WHERE id = $updatedTransferId
        LIMIT 1";
    if (mysql_query($updateTransferRequestQuery)) {
        $transferStatusString = 'non-traité'; // Should not happen
        if ($transferAccepted === 1) {
            $transferStatusString = 'accepté';
        }
        else if ($transferAccepted === 0) {
            $transferStatusString = 'refusé';
        }
        printSuccessMessage('Transfert de ' . $transferingPersonName . ' ' . $transferStatusString);
        $recipientsEmails = [$authorEmail];
        if ($transferAccepted) {
            try {
                $clubManagers = UserService::getClubManagers([$targetClubId, $sourceClubId]);

                foreach ($clubManagers as $clubManager) {
                    array_push($recipientsEmails, $clubManager['email']);
                }
            }
            catch(Exception $exception) {
                printErrorMessage(
                    'Erreur lors de la récupération des adresses e-mails des gestionaires de membres des clubs concernés.',
                    $exception->getMessage()
                );
            }
        }
        sendNotification($transferingPersonName, $sourceClubName, $targetClubName, $authorName, $recipientsEmails, $transferStatusString);
    }
    else {
        printErrorMessage(
            'Erreur lors de la modification du statut du transfert.<br>' .
            mysql_error()
        );
        die();
    }
}

/* Retrieving transfers data */
$transferRequestsQuery = "
  SELECT rcc.id, rcc.accepted, UNIX_TIMESTAMP(rcc.datetime) AS timestamp, u.username,
  p.idDbdPersonne AS idPerson, p.nom AS lastName, p.prenom AS firstName, p.raisonSociale AS companyName,
  co.nbIdClub AS idClubOrigin, co.club AS nameClubOrigin, ct.nbIdClub AS idClubTarget, ct.club AS nameClubTarget
  FROM DBDRequetesChangementClub rcc, DBDPersonne p, clubs co, clubs ct, Personne u
  WHERE rcc.idDbdPersonne = p.idDbdPersonne
  AND rcc.from_clubID = co.nbIdClub
  AND rcc.to_clubID = ct.nbIdClub
  AND rcc.userID = u.id
  ORDER BY datetime DESC";

?>
<h3>Demandes de transfert</h3>
<table class="st-table st-table--spaced">
    <tr>
        <th>Date</th>
        <th>Auteur</th>
        <th>Membre</th>
        <th>Club d'origine</th>
        <th>Club de destination</th>
        <th></th>
    </tr>
    <?php
    if ($transferRequestsResource = mysql_query($transferRequestsQuery)) {
        while ($transfertRequest = mysql_fetch_assoc($transferRequestsResource)) {
            echo '<tr>';
            echo '<td>' . date('d.m.y H:i', $transfertRequest['timestamp']) . '</td>';
            echo '<td>' . $transfertRequest['username'] . '</td>';
            echo '<td>' . $transfertRequest['firstName'] . ' ' . $transfertRequest['lastName'] . '</td>';
            echo '<td>' . $transfertRequest['nameClubOrigin'] . '</td>';
            echo '<td>' . $transfertRequest['nameClubTarget'] . '</td>';
            echo '<td class="action">';
            if ($transfertRequest['accepted'] === '1') {
                echo '&#x2705;';
            } else if ($transfertRequest['accepted'] === '0') {
                echo '&#x274C;';
            } else {
                echo '<a href="?menuselection=' . $menuselection .
                    '&smenuselection=' . $smenuselection .
                    '&accept-transfer=' . $transfertRequest['id'] .
                    '">&#x2705;</a>';
                echo ' ';
                echo '<a href="?menuselection=' . $menuselection .
                    '&smenuselection=' . $smenuselection .
                    '&refuse-transfer=' . $transfertRequest['id'] .
                    '">&#x274C;</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo "</table>";
    } else {
        echo '<p class="error">Erreur lors de la récupération des données de requêtes de transfert.<br />' . mysql_error() . '</p>';
    }
    ?>
