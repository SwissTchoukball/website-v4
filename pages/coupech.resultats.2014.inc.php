<?php
$firstDayVenue = "Centre sportif de la Blancherie, Delémont";
$secondDayVenue = "Centre sportif du Rocher, Nyon";
$firstDayDate = "18.01.2014";
$secondDayDate = "08.06.2014";

$teams = array();
array_push($teams, "Chambésy Panthers"); // team 0
array_push($teams, "Genève 2"); // team 1
array_push($teams, "TchoukballA.D.E 1"); // team 2
array_push($teams, "TchoukballA.D.E 2"); // team 3
array_push($teams, "Piranyon Origin"); // team 4
array_push($teams, "Vernier"); // team 5
array_push($teams, "Lausanne"); // team 6
array_push($teams, "La Chaux-de-Fonds"); // team 7
array_push($teams, "Genève 1"); // team 8
array_push($teams, "Chavannes"); // team 9
array_push($teams, "Val-de-Ruz 2"); // team 10
array_push($teams, "Sion"); // team 11
array_push($teams, "Genève 3"); // team 12
array_push($teams, "Lancy Sharks"); // team 13
array_push($teams, "Piranyon Fusion"); // team 14
array_push($teams, "Val-de-Ruz Flyers"); // team 15

$eightsWinners = array();
array_push($eightsWinners, null);
array_push($eightsWinners, null);
array_push($eightsWinners, null);
array_push($eightsWinners, null);
array_push($eightsWinners, null);
array_push($eightsWinners, null);
array_push($eightsWinners, null);
array_push($eightsWinners, null);

$quarterWinners = array();
array_push($quarterWinners, null);
array_push($quarterWinners, null);
array_push($quarterWinners, null);
array_push($quarterWinners, null);

$semiWinners = array();
array_push($semiWinners, null);
array_push($semiWinners, null);

$winner = null;

?>
<!-- MODELE
<div class="informationsMatch" style="display:none;" id="infomatch1">
	<div class="informationsBoxJournee">Journée 1</div>
	<div class="informationsBoxEquipes">Lausanne 2 - Genève 1</div>
	<div class="informationsBoxScore">Score final : 3 - 2</div>
	<div class="informationsBoxSet">Set 1 : 12 - 15</div>
	<div class="informationsBoxSet">Set 2 : 15 - 09</div>
	<div class="informationsBoxSet">Set 3 : 15 - 13</div>
	<div class="informationsBoxSet">Set 4 : 07 - 15</div>
	<div class="informationsBoxSet">Set 5 : 18 - 16</div>
	<div class="informationsBoxDate">17.01.2009 à 9h00</div>
	<div class="informationsBoxLieu">Riveraine, Neuchâtel</div>
</div>
-->
<div class="informationsMatch" style="display:none;" id="infomatch1">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 1</div>
    <div class="informationsBoxEquipes"><?php echo $teams[0] . " - " . $teams[1]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 06</div>
    <div class="informationsBoxSet">Set 2 : 18 - 16</div>
    <div class="informationsBoxSet">Set 3 : 15 - 07</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 9h45</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch2">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 2</div>
    <div class="informationsBoxEquipes"><?php echo $teams[2] . " - " . $teams[3]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 06</div>
    <div class="informationsBoxSet">Set 2 : 15 - 08</div>
    <div class="informationsBoxSet">Set 3 : 15 - 11</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 9h45</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch3">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 3</div>
    <div class="informationsBoxEquipes"><?php echo $teams[4] . " - " . $teams[5]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 10</div>
    <div class="informationsBoxSet">Set 2 : 15 - 12</div>
    <div class="informationsBoxSet">Set 3 : 15 - 11</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 9h45</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch4">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 4</div>
    <div class="informationsBoxEquipes"><?php echo $teams[6] . " - " . $teams[7]; ?></div>
    <div class="informationsBoxScore">Score final : 0 - 3</div>
    <div class="informationsBoxSet">Set 1 : 18 - 20</div>
    <div class="informationsBoxSet">Set 2 : 08 - 15</div>
    <div class="informationsBoxSet">Set 3 : 13 - 15</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 11h15</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch5">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 5</div>
    <div class="informationsBoxEquipes"><?php echo $teams[8] . " - " . $teams[9]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 06</div>
    <div class="informationsBoxSet">Set 2 : 15 - 08</div>
    <div class="informationsBoxSet">Set 3 : 15 - 07</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 11h15</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch6">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 6</div>
    <div class="informationsBoxEquipes"><?php echo $teams[10] . " - " . $teams[11]; ?></div>
    <div class="informationsBoxScore">Score final : 1 - 3</div>
    <div class="informationsBoxSet">Set 1 : 09 - 15</div>
    <div class="informationsBoxSet">Set 2 : 15 - 13</div>
    <div class="informationsBoxSet">Set 3 : 09 - 15</div>
    <div class="informationsBoxSet">Set 4 : 08 - 15</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 11h15</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch7">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 7</div>
    <div class="informationsBoxEquipes"><?php echo $teams[12] . " - " . $teams[13]; ?></div>
    <div class="informationsBoxScore">Score final : 0 - 3</div>
    <div class="informationsBoxSet">Set 1 : 03 - 15</div>
    <div class="informationsBoxSet">Set 2 : 13 - 15</div>
    <div class="informationsBoxSet">Set 3 : 03 - 15</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 12h45</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch8">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Huitième de finale 8</div>
    <div class="informationsBoxEquipes"><?php echo $teams[14] . " - " . $teams[15]; ?></div>
    <div class="informationsBoxScore">Score final : 0 - 3</div>
    <div class="informationsBoxSet">Set 1 : 08 - 15</div>
    <div class="informationsBoxSet">Set 2 : 11 - 15</div>
    <div class="informationsBoxSet">Set 3 : 06 - 15</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 12h45</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch9">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 1</div>
    <div class="informationsBoxEquipes"><?php echo $teams[0] . " - " . $teams[2]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 07</div>
    <div class="informationsBoxSet">Set 2 : 15 - 10</div>
    <div class="informationsBoxSet">Set 3 : 15 - 05</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 14h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch10">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 2</div>
    <div class="informationsBoxEquipes"><?php echo $teams[4] . " - " . $teams[7]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 07</div>
    <div class="informationsBoxSet">Set 2 : 15 - 11</div>
    <div class="informationsBoxSet">Set 3 : 15 - 08</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 16h</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch11">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 3</div>
    <div class="informationsBoxEquipes"><?php echo $teams[8] . " - " . $teams[11]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 10</div>
    <div class="informationsBoxSet">Set 2 : 15 - 05</div>
    <div class="informationsBoxSet">Set 3 : 15 - 04</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 16h</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch12">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 4</div>
    <div class="informationsBoxEquipes"><?php echo $teams[13] . " - " . $teams[15]; ?></div>
    <div class="informationsBoxScore">Score final : 1 - 3</div>
    <div class="informationsBoxSet">Set 1 : 16 - 14</div>
    <div class="informationsBoxSet">Set 2 : 15 - 17</div>
    <div class="informationsBoxSet">Set 3 : 11 - 15</div>
    <div class="informationsBoxSet">Set 4 : 10 - 15</div>
    <div class="informationsBoxEquipes"></div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 16h</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch13">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Demi-finale 1</div>
    <div class="informationsBoxEquipes"><?php echo $teams[0] . " - " . $teams[4]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 1</div>
    <div class="informationsBoxSet">Set 1 : 15 - 08</div>
    <div class="informationsBoxSet">Set 2 : 15 - 07</div>
    <div class="informationsBoxSet">Set 3 : 12 - 15</div>
    <div class="informationsBoxSet">Set 4 : 15 - 09</div>
    <div class="informationsBoxDate"><?php echo $secondDayDate; ?> à 14h</div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch14">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Demi-finale 2</div>
    <div class="informationsBoxEquipes"><?php echo $teams[8] . " - " . $teams[15]; ?></div>
    <div class="informationsBoxSet">Forfait de <?php echo $teams[15]; ?></div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch15">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Finale</div>
    <div class="informationsBoxEquipes"><?php echo $teams[0] . " - " . $teams[8]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 1</div>
    <div class="informationsBoxSet">Set 1 : 13 - 15</div>
    <div class="informationsBoxSet">Set 2 : 20 - 18</div>
    <div class="informationsBoxSet">Set 3 : 15 - 11</div>
    <div class="informationsBoxSet">Set 4 : 15 - 12</div>
    <div class="informationsBoxDate"><?php echo $secondDayDate; ?> à 19h</div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch16">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Petite finale</div>
    <div class="informationsBoxEquipes"><?php echo $teams[4] . " - " . $teams[15]; ?></div>
    <div class="informationsBoxSet">Forfait de <?php echo $teams[15]; ?></div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch17">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Demi-finale 1 pour la 5ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[2] . " - " . $teams[7]; ?></div>
    <div class="informationsBoxScore">Score final : 0 - 3</div>
    <div class="informationsBoxSet">Set 1 : 08 - 15</div>
    <div class="informationsBoxSet">Set 2 : 09 - 15</div>
    <div class="informationsBoxSet">Set 3 : 13 - 15</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 19h</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch18">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Demi-finale 2 pour la 5ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[11] . " - " . $teams[13]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 2</div>
    <div class="informationsBoxSet">Set 1 : 15 - 17</div>
    <div class="informationsBoxSet">Set 2 : 18 - 16</div>
    <div class="informationsBoxSet">Set 3 : 16 - 14</div>
    <div class="informationsBoxSet">Set 4 : 06 - 15</div>
    <div class="informationsBoxSet">Set 5 : 15 - 11</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 19h</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch19">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Finale pour la 5ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[7] . " - " . $teams[11]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 1</div>
    <div class="informationsBoxSet">Set 1 : 15 - 12</div>
    <div class="informationsBoxSet">Set 2 : 09 - 15</div>
    <div class="informationsBoxSet">Set 3 : 15 - 12</div>
    <div class="informationsBoxSet">Set 4 : 15 - 05</div>
    <div class="informationsBoxDate"><?php echo $secondDayDate; ?> à 15h45</div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch20">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Finale pour la 7ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[2] . " - " . $teams[13]; ?></div>
    <div class="informationsBoxSet">Forfait de <?php echo $teams[2]; ?></div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch21">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 1 pour la 9ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[1] . " - " . $teams[3]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 08</div>
    <div class="informationsBoxSet">Set 2 : 15 - 10</div>
    <div class="informationsBoxSet">Set 3 : 15 - 04</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 12h45</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch22">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 2 pour la 9ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[5] . " - " . $teams[6]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 08</div>
    <div class="informationsBoxSet">Set 2 : 15 - 11</div>
    <div class="informationsBoxSet">Set 3 : 15 - 11</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 14h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch23">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 3 pour la 9ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[9] . " - " . $teams[10]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 1</div>
    <div class="informationsBoxSet">Set 1 : 16 - 14</div>
    <div class="informationsBoxSet">Set 2 : 15 - 13</div>
    <div class="informationsBoxSet">Set 3 : 09 - 15</div>
    <div class="informationsBoxSet">Set 4 : 15 - 09</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 14h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch24">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Quart de finale 4 pour la 9ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[12] . " - " . $teams[14]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 1</div>
    <div class="informationsBoxSet">Set 1 : 16 - 18</div>
    <div class="informationsBoxSet">Set 2 : 15 - 12</div>
    <div class="informationsBoxSet">Set 3 : 08 - 15</div>
    <div class="informationsBoxSet">Set 4 : 11 - 15</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 17h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch25">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Demi-finale 1 pour la 9ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[1] . " - " . $teams[5]; ?></div>
    <div class="informationsBoxScore">Score final : 0 - 3</div>
    <div class="informationsBoxSet">Set 1 : 11 - 15</div>
    <div class="informationsBoxSet">Set 2 : 09 - 15</div>
    <div class="informationsBoxSet">Set 3 : 17 - 19</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 17h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch26">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Demi-finale 2 pour la 9ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[9] . " - " . $teams[14]; ?></div>
    <div class="informationsBoxScore">Score final : 2 - 3</div>
    <div class="informationsBoxSet">Set 1 : 13 - 15</div>
    <div class="informationsBoxSet">Set 2 : 12 - 15</div>
    <div class="informationsBoxSet">Set 3 : 15 - 12</div>
    <div class="informationsBoxSet">Set 4 : 19 - 17</div>
    <div class="informationsBoxSet">Set 5 : 14 - 16</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 20h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch27">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Finale pour la 9ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[5] . " - " . $teams[14]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 0</div>
    <div class="informationsBoxSet">Set 1 : 15 - 06</div>
    <div class="informationsBoxSet">Set 2 : 15 - 10</div>
    <div class="informationsBoxSet">Set 3 : 15 - 13</div>
    <div class="informationsBoxDate"><?php echo $secondDayDate; ?> à 12h</div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch28">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Finale pour la 11ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[1] . " - " . $teams[9]; ?></div>
    <div class="informationsBoxSet">Forfait de <?php echo $teams[1]; ?></div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch29">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Demi-finale 1 pour la 13ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[3] . " - " . $teams[6]; ?></div>
    <div class="informationsBoxScore">Score final : 0 - 3</div>
    <div class="informationsBoxSet">Set 1 : 04 - 15</div>
    <div class="informationsBoxSet">Set 2 : 07 - 15</div>
    <div class="informationsBoxSet">Set 3 : 08 - 15</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 17h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch30">
    <div class="informationsBoxJournee">Journée 1</div>
    <div class="informationsBoxTypeMatch">Demi-finale 2 pour la 13ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[10] . " - " . $teams[12]; ?></div>
    <div class="informationsBoxScore">Score final : 3 - 2</div>
    <div class="informationsBoxSet">Set 1 : 15 - 13</div>
    <div class="informationsBoxSet">Set 2 : 16 - 14</div>
    <div class="informationsBoxSet">Set 3 : 17 - 19</div>
    <div class="informationsBoxSet">Set 4 : 12 - 15</div>
    <div class="informationsBoxSet">Set 5 : 16 - 14</div>
    <div class="informationsBoxDate"><?php echo $firstDayDate; ?> à 20h30</div>
    <div class="informationsBoxLieu"><?php echo $firstDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch31">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Finale pour la 13ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[6] . " - " . $teams[10]; ?></div>
    <div class="informationsBoxSet">Forfait de <?php echo $teams[6]; ?></div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>
<div class="informationsMatch" style="display:none;" id="infomatch32">
    <div class="informationsBoxJournee">Journée 2</div>
    <div class="informationsBoxTypeMatch">Finale pour la 15ème place</div>
    <div class="informationsBoxEquipes"><?php echo $teams[3] . " - " . $teams[12]; ?></div>
    <div class="informationsBoxSet">Forfait de <?php echo $teams[3]; ?></div>
    <div class="informationsBoxLieu"><?php echo $secondDayVenue; ?></div>
</div>

<!-- !Arbres -->
<div class="arbreCoupeHuitiemes">
    <div class="colonneHuitiemes">
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('1');" onMouseOut="masquerInfoMatch('1');">
            <span class="gagnant"><?php echo $teams[0]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('1');" onMouseOut="masquerInfoMatch('1');">
            <span class="perdant"><?php echo $teams[1]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('2');" onMouseOut="masquerInfoMatch('2');">
            <span class="gagnant"><?php echo $teams[2]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('2');" onMouseOut="masquerInfoMatch('2');">
            <span class="perdant"><?php echo $teams[3]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('3');" onMouseOut="masquerInfoMatch('3');">
            <span class="gagnant"><?php echo $teams[4]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('3');" onMouseOut="masquerInfoMatch('3');">
            <span class="perdant"><?php echo $teams[5]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('4');" onMouseOut="masquerInfoMatch('4');">
            <span class="perdant"><?php echo $teams[6]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('4');" onMouseOut="masquerInfoMatch('4');">
            <span class="gagnant"><?php echo $teams[7]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('5');" onMouseOut="masquerInfoMatch('5');">
            <span class="gagnant"><?php echo $teams[8]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('5');" onMouseOut="masquerInfoMatch('5');">
            <span class="perdant"><?php echo $teams[9]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('6');" onMouseOut="masquerInfoMatch('6');">
            <span class="perdant"><?php echo $teams[10]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('6');" onMouseOut="masquerInfoMatch('6');">
            <span class="gagnant"><?php echo $teams[11]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('7');" onMouseOut="masquerInfoMatch('7');">
            <span class="perdant"><?php echo $teams[12]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('7');" onMouseOut="masquerInfoMatch('7');">
            <span class="gagnant"><?php echo $teams[13]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('8');" onMouseOut="masquerInfoMatch('8');">
            <span class="perdant"><?php echo $teams[14]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('8');" onMouseOut="masquerInfoMatch('8');">
            <span class="gagnant"><?php echo $teams[15]; ?></span>
        </div>
    </div>
    <div class="colonneQuarts">
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('9');" onMouseOut="masquerInfoMatch('9');">
            <span class="gagnant"><?php echo $teams[0]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('9');" onMouseOut="masquerInfoMatch('9');">
            <span class="perdant"><?php echo $teams[2]; ?></span>
        </div>
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('10');" onMouseOut="masquerInfoMatch('10');">
            <span class="gagnant"><?php echo $teams[4]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('10');" onMouseOut="masquerInfoMatch('10');">
            <span class="perdant"><?php echo $teams[7]; ?></span>
        </div>
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('11');" onMouseOut="masquerInfoMatch('11');">
            <span class="gagnant"><?php echo $teams[8]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('11');" onMouseOut="masquerInfoMatch('11');">
            <span class="perdant"><?php echo $teams[11]; ?></span>
        </div>
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('12');" onMouseOut="masquerInfoMatch('12');">
            <span class="perdant"><?php echo $teams[13]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('12');" onMouseOut="masquerInfoMatch('12');">
            <span class="gagnant"><?php echo $teams[15]; ?></span>
        </div>
    </div>
    <div class="colonneDemis">
        <div class="boxEquipe3A" onMouseOver="afficherInfoMatch('13');" onMouseOut="masquerInfoMatch('13');">
            <span class="gagnant"><?php echo $teams[0]; ?></span>
        </div>
        <div class="boxEquipe3B" onMouseOver="afficherInfoMatch('13');" onMouseOut="masquerInfoMatch('13');">
            <span class="perdant"><?php echo $teams[4]; ?></span>
        </div>
        <div class="boxEquipe3A" onMouseOver="afficherInfoMatch('14');" onMouseOut="masquerInfoMatch('14');">
            <span class="gagnant"><?php echo $teams[8]; ?></span>
        </div>
        <div class="boxEquipe3B" onMouseOver="afficherInfoMatch('14');" onMouseOut="masquerInfoMatch('14');">
            <span class="perdant"><?php echo $teams[15]; ?></span>
        </div>
    </div>
    <div class="colonneFinales">
        <div class="boxEquipe4A" onMouseOver="afficherInfoMatch('15');" onMouseOut="masquerInfoMatch('15');">
            <span class="gagnant"><?php echo $teams[0]; ?></span>
        </div>
        <div class="boxEquipe4B" onMouseOver="afficherInfoMatch('15');" onMouseOut="masquerInfoMatch('15');">
            <span class="perdant"><?php echo $teams[8]; ?></span>
        </div>
        <div class="boxEquipe4A" onMouseOver="afficherInfoMatch('16');" onMouseOut="masquerInfoMatch('16');">
            <span class="gagnant"><?php echo $teams[4]; ?></span>
        </div>
        <div class="boxEquipe4B" onMouseOver="afficherInfoMatch('16');" onMouseOut="masquerInfoMatch('16');">
            <span class="perdant"><?php echo $teams[15]; ?></span>
        </div>
    </div>
</div>
<div class="arbreCoupeDemis">
    <h3>Coupe pour la 5ème place</h3>
    <div class="colonneDemis">
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('17');" onMouseOut="masquerInfoMatch('17');">
            <span class="perdant"><?php echo $teams[2]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('17');" onMouseOut="masquerInfoMatch('17');">
            <span class="gagnant"><?php echo $teams[7]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('18');" onMouseOut="masquerInfoMatch('18');">
            <span class="gagnant"><?php echo $teams[11]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('18');" onMouseOut="masquerInfoMatch('18');">
            <span class="perdant"><?php echo $teams[13]; ?></span>
        </div>
    </div>
    <div class="colonneFinales">
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('19');" onMouseOut="masquerInfoMatch('19');">
            <span class="gagnant"><?php echo $teams[7]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('19');" onMouseOut="masquerInfoMatch('19');">
            <span class="perdant"><?php echo $teams[11]; ?></span>
        </div>
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('20');" onMouseOut="masquerInfoMatch('20');">
            <span class="perdant"><?php echo $teams[2]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('20');" onMouseOut="masquerInfoMatch('20');">
            <span class="gagnant"><?php echo $teams[13]; ?></span>
        </div>
    </div>
</div>
<div class="arbreCoupeQuarts">
    <h3>Coupe pour la 9ème place</h3>
    <div class="colonneQuarts">
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('21');" onMouseOut="masquerInfoMatch('21');">
            <span class="gagnant"><?php echo $teams[1]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('21');" onMouseOut="masquerInfoMatch('21');">
            <span class="perdant"><?php echo $teams[3]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('22');" onMouseOut="masquerInfoMatch('22');">
            <span class="gagnant"><?php echo $teams[5]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('22');" onMouseOut="masquerInfoMatch('22');">
            <span class="perdant"><?php echo $teams[6]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('23');" onMouseOut="masquerInfoMatch('23');">
            <span class="gagnant"><?php echo $teams[9]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('23');" onMouseOut="masquerInfoMatch('23');">
            <span class="perdant"><?php echo $teams[10]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('24');" onMouseOut="masquerInfoMatch('24');">
            <span class="perdant"><?php echo $teams[12]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('24');" onMouseOut="masquerInfoMatch('24');">
            <span class="gagnant"><?php echo $teams[14]; ?></span>
        </div>
    </div>
    <div class="colonneDemis">
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('25');" onMouseOut="masquerInfoMatch('25');">
            <span class="perdant"><?php echo $teams[1]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('25');" onMouseOut="masquerInfoMatch('25');">
            <span class="gagnant"><?php echo $teams[5]; ?></span>
        </div>
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('26');" onMouseOut="masquerInfoMatch('26');">
            <span class="perdant"><?php echo $teams[9]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('26');" onMouseOut="masquerInfoMatch('26');">
            <span class="gagnant"><?php echo $teams[14]; ?></span>
        </div>
    </div>
    <div class="colonneFinales">
        <div class="boxEquipe3A" onMouseOver="afficherInfoMatch('27');" onMouseOut="masquerInfoMatch('27');">
            <span class="gagnant"><?php echo $teams[5]; ?></span>
        </div>
        <div class="boxEquipe3B" onMouseOver="afficherInfoMatch('27');" onMouseOut="masquerInfoMatch('27');">
            <span class="perdant"><?php echo $teams[14]; ?></span>
        </div>
        <div class="boxEquipe3A" onMouseOver="afficherInfoMatch('28');" onMouseOut="masquerInfoMatch('28');">
            <span class="perdant"><?php echo $teams[1]; ?></span>
        </div>
        <div class="boxEquipe3B" onMouseOver="afficherInfoMatch('28');" onMouseOut="masquerInfoMatch('28');">
            <span class="gagnant"><?php echo $teams[9]; ?></span>
        </div>
    </div>
</div>
<div class="arbreCoupeDemis">
    <h3>Coupe pour la 13ème place</h3>
    <div class="colonneDemis">
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('29');" onMouseOut="masquerInfoMatch('29');">
            <span class="perdant"><?php echo $teams[3]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('29');" onMouseOut="masquerInfoMatch('29');">
            <span class="gagnant"><?php echo $teams[6]; ?></span>
        </div>
        <div class="boxEquipe1A" onMouseOver="afficherInfoMatch('30');" onMouseOut="masquerInfoMatch('30');">
            <span class="gagnant"><?php echo $teams[10]; ?></span>
        </div>
        <div class="boxEquipe1B" onMouseOver="afficherInfoMatch('30');" onMouseOut="masquerInfoMatch('30');">
            <span class="perdant"><?php echo $teams[12]; ?></span>
        </div>
    </div>
    <div class="colonneFinales">
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('31');" onMouseOut="masquerInfoMatch('31');">
            <span class="perdant"><?php echo $teams[6]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('31');" onMouseOut="masquerInfoMatch('31');">
            <span class="gagnant"><?php echo $teams[10]; ?></span>
        </div>
        <div class="boxEquipe2A" onMouseOver="afficherInfoMatch('32');" onMouseOut="masquerInfoMatch('32');">
            <span class="perdant"><?php echo $teams[3]; ?></span>
        </div>
        <div class="boxEquipe2B" onMouseOver="afficherInfoMatch('32');" onMouseOut="masquerInfoMatch('32');">
            <span class="gagnant"><?php echo $teams[12]; ?></span>
        </div>
    </div>
</div>
