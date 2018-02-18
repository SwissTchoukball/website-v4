<?php
/**
 * Add a list of comma separated email addresses to a string of line separated emails for Infomaniak mailing-list
 * @param $mailingList
 * @param $emails
 * @param $clubName
 */
function addToMailingList(&$mailingList, $emails, $clubName) {
    $emailsArray = explode(',', $emails);
    foreach ($emailsArray as $email) {
        $mailingList .= trim($email). ',' . $clubName . "\n";
    }
}

$officialCommMailingList = "comite@tchoukball.ch, Comité Swiss Tchoukball\n";
$officialCommMailingList .= "resp.developpement@tchoukball.ch, Responsable commission développement\n";

$tournamentsCommMailingList = "comite@tchoukball.ch, Comité Swiss Tchoukball\n";

$clubs = ClubService::getClubsContactEmails();
foreach ($clubs as $club) {
    addToMailingList($officialCommMailingList, $club['emailsOfficialComm'], $club['shortName']);
    addToMailingList($tournamentsCommMailingList, $club['emailsTournamentComm'], $club['shortName']);
}

$externalEmailsTournamentQuery = "SELECT email, name FROM email_tournament_comm_external ORDER BY email";
$externalEmailsTournamentResource = mysql_query($externalEmailsTournamentQuery);
while($contact = mysql_fetch_assoc($externalEmailsTournamentResource)) {
    $tournamentsCommMailingList .= $contact['email']. ',' . $contact['name'] . "\n";
}

?>
<h2>Pour clubs@tchoukball.ch</h2>
<textarea title="Official communiation mailing-list" class="big-field" readonly><?php echo $club['officialCommMailingList'] ?></textarea>

<h2>Pour info.tournois@tchoukball.ch</h2>
<textarea title="Tournament communiation mailing-list" class="big-field" readonly><?php echo $club['tournamentsCommMailingList'] ?></textarea>
