<?php
?>
<h3>
    <?php echo VAR_LANG_ETAPE_1; ?>
</h3>
<form method="post" id="selecNbMatchs" action="" class="st-form">
    <label for="nbMatchesToAdd">Nombre de matchs à insérer</label>
    <select name="nbMatchs" id="nbMatchesToAdd">
        <?php
        for ($i = 1; $i <= 100; $i++) {
            echo "<option value='" . $i . "'>" . $i . "</option>";
        }
        ?>
    </select>
    <input type="hidden" name="action" value="goToStep2"/>
    <input type="submit" class="button button--primary" value="Suivant"/>
</form>