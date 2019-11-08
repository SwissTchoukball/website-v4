<?php
$seasonStartYear = date('Y');
if (date('m') < 8) {
    $seasonStartYear--;
}
$thisSeason = $seasonStartYear . '-' . ($seasonStartYear + 1);

$leagueAFormulaImagePath = "/pictures/formule_championnat-ligueA-{$thisSeason}.svg";
$leagueBFormulaImagePath = "/pictures/formule_championnat-ligueB-{$thisSeason}.svg";
$promotionRelegationFormulaImagePath = "/pictures/formule_championnat-promotion-relegation-{$thisSeason}.svg";

?>
<h2>Championnat <?php echo $thisSeason; ?></h2>
<h3>Ligue A</h3>
<img src="<?php printf($leagueAFormulaImagePath, $thisSeason); ?>"
     alt="Formule de la ligue A du championnat <?php echo $thisSeason; ?>"/>

<h3>Ligue B</h3>
<img src="<?php printf($leagueBFormulaImagePath, $thisSeason); ?>"
     alt="Formule de la ligue B du championnat <?php echo $thisSeason; ?>"/>

<h3>Promotion/relégation</h3>
<img src="<?php printf($promotionRelegationFormulaImagePath, $thisSeason); ?>"
     alt="Formule de la promotion/relégation du championnat <?php echo $thisSeason; ?>"/>


