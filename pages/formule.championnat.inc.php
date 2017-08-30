<?php
$seasonStartYear = date('Y');
if (date('m') < 7) {
    $seasonStartYear--;
}
$thisSeason = $seasonStartYear . '-' . ($seasonStartYear + 1);
$lastSeason = ($seasonStartYear - 1) . '-' . $seasonStartYear;

$shownSeason = $thisSeason;

$championshipFormulaImagePath = '/pictures/schema_championnat_' . $thisSeason . '.svg';
?>
<?php
if (!is_file($_SERVER['DOCUMENT_ROOT'] . $championshipFormulaImagePath)) {
    $championshipFormulaImagePath = '/pictures/schema_championnat_' . $lastSeason . '.svg';
    $shownSeason = $lastSeason;
}

if (is_file($_SERVER['DOCUMENT_ROOT'] . $championshipFormulaImagePath)) {
    ?>
    <h2>Championnat <?php echo $shownSeason; ?></h2>
    <img src="<?php echo $championshipFormulaImagePath; ?>"
         alt="Formule du championnat <?php echo $shownSeason; ?>"
         class="championship-format-image"/>
    <?php
}
?>


