<?php
$seasonStartYear = date('Y');
if (date('m') < 9) {
    $seasonStartYear--;
}
$seasonString = $seasonStartYear . '-' . ($seasonStartYear + 1);
$championshipFormulaImagePath = '/pictures/schema_championnat_' . $seasonString . '.png';
?>
<h2>Championnat <?php echo $seasonString; ?></h2>
<?php
if (is_file($_SERVER['DOCUMENT_ROOT'] . $championshipFormulaImagePath)) {
    ?>
    <img src='<?php echo $championshipFormulaImagePath; ?>' alt='Formule du championnat <?php echo $seasonString; ?>'/>
    <?php
}
?>


