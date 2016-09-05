<?php

include('config.php');


function incrementDay($year, $month, $day)
{
    $nbDayinMonth = date('t', strtotime($year."-".$month."-".$day));
    $date = array();
    if ($day != $nbDayinMonth) {
        $day++;
        $date = array($year, $month, $day);
    } elseif ($month != 12) {
        $month++;
        $date = array($year, $month, 1);
    } elseif ($month == 12) {
        $year++;
        $date = array($year, 1, 1);
    } else {
        die("Erreur date suivante");
    }
    return $date;
}

function dateTime_sql2dateTime_ics($dateIn, $time, $allday, $isEnd)
{
    $date = array();
    $date[0] = substr($dateIn, 0, 4);
    $date[1] = substr($dateIn, 5, 2);
    $date[2] = substr($dateIn, 8, 2);

    $heure=substr($time, 0, 2);
    $minute=substr($time, 3, 2);

    if ($allday == 1) {
        if ($isEnd) {
            $date = incrementDay($date[0], $date[1], $date[2]);
        }
        $dateTimeICS = ";VALUE=DATE:".$date[0].sprintf('%02u', $date[1]).sprintf('%02u', $date[2])."";
    } else {
        $dateTimeICS = ";TZID=Europe/Zurich:".$date[0].$date[1].$date[2]."T".$heure.$minute."00";
    }
    return $dateTimeICS;
}

function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

function format4ICS($string)
{
    $string = str_replace("\n", "\\n", str_replace(";", "\;", str_replace(",", '\,', $string)));
    $string = strip_tags($string);
    //$string = addslashes($string);
    $string = str_replace(":", "", $string);
    $string = str_replace(";", "", $string);
    return $string;
}

$lastYear = date('Y')-1;
$oneYearAgo = $lastYear."-".date('m')."-".date('d');


if (isset($_GET['championnat'])) {
    $queryTeamSelect = "";
    if (isset($_GET['equipe']) && is_numeric($_GET['equipe'])) {
        $queryTeamSelect = "AND (equipeA=".$_GET['equipe']." OR equipeB=".$_GET['equipe'].")";
    }
    $eventsQuery="SELECT m.dateDebut, m.dateFin, m.heureDebut, m.heureFin, l.nom AS salle, l.ville, m.idMatch, m.equipeA, m.equipeB
                  FROM Championnat_Matchs m
                  LEFT OUTER JOIN Lieux l ON l.id = m.idLieu
                  WHERE dateFin>'".$oneYearAgo."' ".$queryTeamSelect."
                  ORDER BY dateDebut, heureDebut";
    $requeteEquipes="SELECT * FROM Championnat_Equipes WHERE idEquipe!=11 ORDER BY idEquipe";
    $retourEquipes=mysql_query($requeteEquipes);
    $tableauEquipes=array();
    while ($donneesEquipes=mysql_fetch_array($retourEquipes)) {
        $tableauEquipes[$donneesEquipes['idEquipe']]=$donneesEquipes['equipe'];
    }
} elseif (isset($_GET['entrainements'])) {
    $eventsQuery = "SELECT id, titre, description, lieu, jourEntier, heureDebut, heureFin, dateDebut, dateFin FROM Calendrier_Evenements WHERE idCategorie=2 AND dateFin>'".$oneYearAgo."' AND visible=1 ORDER BY dateDebut, heureDebut";
} else {
    //Request events for 1 year.
    $eventsQuery = "SELECT Calendrier_Evenements.id AS idEvent, titre, description, lieu, jourEntier, couleur, nom, heureDebut, heureFin, dateDebut, dateFin FROM Calendrier_Evenements, Calendrier_Categories WHERE Calendrier_Evenements.idCategorie=Calendrier_Categories.id AND dateFin>'".$oneYearAgo."' AND visible=1 ORDER BY dateDebut, heureDebut";
}
$eventsReturn = mysql_query($eventsQuery);


$ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VTIMEZONE
TZID:Europe/Zurich
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
RRULE:FREQ=YEARLY;UNTIL=19420504T000000Z;BYMONTH=5;BYDAY=1MO
DTSTART:19410505T010000
TZNAME:CEST
TZOFFSETTO:+0200
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200
RRULE:FREQ=YEARLY;UNTIL=19421005T000000Z;BYMONTH=10;BYDAY=1MO
DTSTART:19411006T020000
TZNAME:CET
TZOFFSETTO:+0100
END:STANDARD
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
DTSTART:19810329T020000
TZNAME:CEST
TZOFFSETTO:+0200
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200
RRULE:FREQ=YEARLY;UNTIL=19950924T010000Z;BYMONTH=9;BYDAY=-1SU
DTSTART:19810927T030000
TZNAME:CET
TZOFFSETTO:+0100
END:STANDARD
BEGIN:STANDARD
TZOFFSETFROM:+0200
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
DTSTART:19961027T030000
TZNAME:CET
TZOFFSETTO:+0100
END:STANDARD
END:VTIMEZONE
";

while ($event = mysql_fetch_assoc($eventsReturn)) {
    if (isset($_GET['championnat'])) {
        $titre = format4ICS($tableauEquipes[$event['equipeA']]." - ".$tableauEquipes[$event['equipeB']]);
        $lieu = format4ICS($event['salle'].", ".$event['ville']);
        $description =  "";
    } else {
        $titre = $event['titre'];
        $lieu = format4ICS($event['lieu']);
        $description = format4ICS($event['description']);
    }

    $ical .= "BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "@tchoukball.ch
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART".dateTime_sql2dateTime_ics($event['dateDebut'], $event['heureDebut'], $event['jourEntier'], false)."
DTEND".dateTime_sql2dateTime_ics($event['dateFin'], $event['heureFin'], $event['jourEntier'], true)."
SUMMARY:".$titre."
DESCRIPTION:".$description."
LOCATION:".$lieu."
END:VEVENT
";
}
$ical .= "END:VCALENDAR";

/*
$ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "@yourhost.test
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:19970714T170000Z
DTEND:19970715T035959Z
SUMMARY:Bastille Day Party
END:VEVENT
END:VCALENDAR";*/

//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=fstb-calendar.ics');
echo $ical;
exit;
