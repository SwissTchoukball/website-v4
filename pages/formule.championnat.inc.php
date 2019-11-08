<?php
$seasonStartYear = date('Y');
if (date('m') < 7) {
    $seasonStartYear--;
}
$thisSeason = $seasonStartYear . '-' . ($seasonStartYear + 1);
$lastSeason = ($seasonStartYear - 1) . '-' . $seasonStartYear;

$shownSeason = $thisSeason;

$leagueAFormulaImagePath = '/pictures/schema_championnat_%s.svg';
if (!is_file($_SERVER['DOCUMENT_ROOT'] . sprintf($leagueAFormulaImagePath, $thisSeason))) {
    $leagueAFormulaImagePath = sprintf($leagueAFormulaImagePath, $lastSeason);
    $shownSeason = $lastSeason;
}

$leagueBFormulaImagePath = '/pictures/schema_championnat_%s.svg';
if (!is_file($_SERVER['DOCUMENT_ROOT'] . sprintf($leagueBFormulaImagePath, $thisSeason))) {
    $leagueBFormulaImagePath = sprintf($leagueBFormulaImagePath, $lastSeason);
    $shownSeason = $lastSeason;
}

$promotionRelegationFormulaImagePath = '/pictures/schema_championnat_%s.svg';
if (!is_file($_SERVER['DOCUMENT_ROOT'] . sprintf($promotionRelegationFormulaImagePath, $thisSeason))) {
    $promotionRelegationFormulaImagePath = sprintf($promotionRelegationFormulaImagePath, $lastSeason);
    $shownSeason = $lastSeason;
}

if (is_file($_SERVER['DOCUMENT_ROOT'] . $leagueAFormulaImagePath)) {
    ?>
    <h2>Championnat <?php echo $shownSeason; ?></h2>
    <h3>Ligue A</h3>
    <img src="<?php printf($leagueAFormulaImagePath, $shownSeason); ?>"
         alt="Formule de la ligue A du championnat <?php echo $shownSeason; ?>"/>

    <h3>Ligue B</h3>
    <img src="<?php printf($leagueBFormulaImagePath, $shownSeason); ?>"
         alt="Formule de la ligue B du championnat <?php echo $shownSeason; ?>"/>

    <h3>Promotion/relégation</h3>
    <img src="<?php printf($promotionRelegationFormulaImagePath, $shownSeason); ?>"
         alt="Formule de la promotion/relégation du championnat <?php echo $shownSeason; ?>"/>
    <?php
}
?>


