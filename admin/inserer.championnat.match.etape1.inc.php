<?php
?>
<h3>
    <?php echo VAR_LANG_ETAPE_1; ?>
</h3>

<p>
    Nombre de matchs � ins�rer:
<form method="post" id="selecNbMatchs" action="">
    <select name="nbMatchs">
        <?php
        for ($i = 1; $i <= 100; $i++) {
            echo "<option value='" . $i . "'>" . $i . "</option>";
        }
        ?>
    </select>
    <input type="hidden" name="action" value="goToStep2"/>
    <input type="submit" class="button button--primary" value="Suivant"/>
</form>
</p><br/>
