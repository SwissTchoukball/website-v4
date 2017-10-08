<?php
$mailingListQuery =
    "SELECT c.club as shortName, c.emailsOfficialComm, c.emailsTournamentComm
    FROM clubs c
    WHERE c.statusId != 3";
$mailingListResource = mysql_query($mailingListQuery);

$officialCommMailingList = "comite@tchoukball.ch, Comit� Swiss Tchoukball\n";
$officialCommMailingList .= "resp.developpement@tchoukball.ch, Responsable commission d�veloppement\n";

$tournamentsCommMailingList = "comite@tchoukball.ch, Comit� Swiss Tchoukball\n";

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

while($club = mysql_fetch_assoc($mailingListResource)) {
    addToMailingList($officialCommMailingList, $club['emailsOfficialComm'], $club['shortName']);
    addToMailingList($tournamentsCommMailingList, $club['emailsTournamentComm'], $club['shortName']);
}
?>
<h2>Pour clubs@tchoukball.ch</h2>
<textarea title="Official communiation mailing-list" class="big-field" readonly><?php echo $officialCommMailingList ?></textarea>

<h2>Pour info.tournois@tchoukball.ch</h2>
<textarea title="Tournament communiation mailing-list" class="big-field" readonly><?php echo $tournamentsCommMailingList ?></textarea>
