<?php
include('../config.php');

date_default_timezone_set('Europe/Zurich');

$currentStatsQuery = "SELECT clubs.club,
				 clubs.nbIdClub AS id,
				 COUNT(if(idStatus=3,1,NULL)) AS nbMembresActifs,
				 COUNT(if(idStatus=6,1,NULL)) AS nbMembresJuniors,
				 COUNT(if(idStatus=5,1,NULL)) AS nbMembresSoutiens,
				 COUNT(if(idStatus=4,1,NULL)) AS nbMembresPassifs,
				 COUNT(if(idStatus=23,1,NULL)) AS nbMembresVIP,
				 COUNT(if(idStatus!=3 AND idStatus!=4 AND idStatus!=5 AND idStatus!=6 AND idStatus!=23,1,NULL)) AS nbMembresAutres,
				 COUNT(idDbdPersonne) AS nbMembresTotal
		  FROM DBDPersonne
		  JOIN clubs ON clubs.nbIdClub=DBDPersonne.idClub
		  WHERE statusId = 1 OR statusId = 2
		  GROUP BY DBDPersonne.idClub";

$clubsStats = mysql_query($currentStatsQuery);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Clubs statistics saving</title>
    <meta charset="iso-8859-1">
</head>
<body>
<?php

while ($clubStats = mysql_fetch_assoc($clubsStats)) {
    $saveStatisticsQuery = "INSERT INTO DBDStatsClubs (idClub,
                                                               date,
                                                               nbMembresActifs,
                                                               nbMembresJuniors,
                                                               nbMembresSoutiens,
                                                               nbMembresPassifs,
                                                               nbMembresVIP,
                                                               nbMembresAutres)
                                    VALUES (" . $clubStats['id'] . ",
                                            '" . date('Y-m-d') . "',
                                            " . $clubStats['nbMembresActifs'] . ",
                                            " . $clubStats['nbMembresJuniors'] . ",
                                            " . $clubStats['nbMembresSoutiens'] . ",
                                            " . $clubStats['nbMembresPassifs'] . ",
                                            " . $clubStats['nbMembresVIP'] . ",
                                            " . $clubStats['nbMembresAutres'] . ")";
    //echo $saveStatisticsQuery."<br />";
    if (mysql_query($saveStatisticsQuery)) {
        echo "<p><em>" . $clubStats['club'] . "</em> stats saved.</p>";
    } else {
        echo "<p>Error while saving <em>" . $clubStats['club'] . "</em> stats.
                      Maybe already saved today.</p>";
    }
}

mysql_close();
?>
</body>
</html>
