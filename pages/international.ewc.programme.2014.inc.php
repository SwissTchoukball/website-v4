<?php
if($_SESSION["debug_tracage"])echo __FILE__."<BR>";
statInsererPageSurf(__FILE__);
?>
<div class="presentation">
	<?php
	$editionID = 7;
	$editionQuery = "SELECT e.year, e.venue, c.idA2 FROM EWC_Editions e, DBDPays c WHERE e.id=".$editionID." AND e.countryID=c.idPays LIMIT 1";
	$editionData = mysql_query($editionQuery);
	$edition = mysql_fetch_assoc($editionData);
	$editionYear = $edition['year'];
	$editionVenue = $edition['venue'];
	$editionCountry = $edition['idA2'];

	echo '<h3>European Winners\' Cup '.$editionYear.'<br />'.$editionVenue.' ('.$editionCountry.')</h3>';
	?>
	<p class="EWCScoresLink"><a href="http://live.tchoukballworld.net/ewc2014"><?php echo VAR_LANG_RESULTATS; ?></a></p>
	<?php


	$matchDate = '0000-00-00'; //In order for the first table header to be printed.
	$matchVenue = ''; //In order for the first table header to be printed.

	$matchesQuery = "SELECT DATE(datetime) AS date,
							TIME(datetime) AS time,
							DAYOFWEEK(datetime) AS dayOfWeek,
							m.pool,
							ta.name AS teamAName,
							tb.name AS teamBName,
							tm.name".$_SESSION['__langue__']." AS type,
							tm.initial AS typeShort,
							m.scoreA,
							m.scoreB,
							l.name".$_SESSION['__langue__']." AS venue
					 FROM EWC_Matches m
					 INNER JOIN EWC_MatchesTypes tm ON tm.id=m.typeID
					 INNER JOIN Lieux l ON l.id=m.venueID
					 LEFT OUTER JOIN EWC_Teams ta ON ta.id=m.teamA
					 LEFT OUTER JOIN EWC_Teams tb ON tb.id=m.teamB
					 WHERE editionID=".$editionID."
					 ORDER BY date, venue, m.datetime";
	$matchesData = mysql_query($matchesQuery);
	while($match = mysql_fetch_assoc($matchesData)) {
		$matchID = $match['id'];
		$matchPool = $match['pool'];
		if ($match['teamAName'] == NULL) {
			$matchTeamA = VAR_LANG_INCONNU;
		} else {
			$matchTeamA = $match['teamAName'];
		}
		if ($match['teamBName'] == NULL) {
			$matchTeamB = VAR_LANG_INCONNU;
		} else {
			$matchTeamB = $match['teamBName'];
		}
		$matchType = $match['type'];
		$matchTypeShort = $match['typeShort'];
		$matchScoreA = $match['scoreA'];
		$matchScoreB = $match['scoreB'];
		$previousMatchVenue = $matchVenue;
		$matchVenue = $match['venue'];
		$previousMatchDate = $matchDate;
		$matchDate = $match['date'];
		$matchTime = substr($match['time'],0,5);
		$matchDayOfWeek = $match['dayOfWeek'];

		if ($matchDate != $previousMatchDate OR $matchVenue != $previousMatchVenue) {
			if ($previousMatchDate != '0000-00-00') { // Not the first table
				echo "</table>";
			}
			?>
			<h4><?php echo ucfirst(date_sql2date_joli($matchDate, "", $_SESSION['__langue__']))." - ".$matchVenue; ?></h4><br />
			<table class="tableauProgrammeEWC">
				<tr>
					<th></th>
					<th><?php echo VAR_LANG_GROUPE; ?></th>
					<th><?php echo VAR_LANG_HEURE; ?></th>
					<th colspan="3"><?php echo VAR_LANG_MATCH; ?></th>
					<!--<th><?php echo VAR_LANG_SCORE; ?></th>-->
				</tr>
			<?php
		}
		echo '<tr>';
		echo '<td align="center"><span class="competitionIcon" title="'.$matchType.'">'.$matchTypeShort.'</span></td>';
		echo '<td align="center">'.$matchPool.'</td>';
		echo '<td align="center">'.$matchTime.'</td>';
		echo '<td align="right">'.$matchTeamA.'</td>';
		echo '<td align="center" width="10px">-</td>';
		echo '<td align="left">'.$matchTeamB.'</td>';
		/*if ($matchScoreA != NULL and $matchScoreB != NULL) {
			echo '<td align="center">'.$matchScoreA.'-'.$matchScoreB.'</td>';
		} else {
			echo '<td align="center"></td>';
		}*/
		echo '</tr>';
	}
	?>
			</table>
	<?php
	printEWCMatchesTypesKey();
	?>
</div>
