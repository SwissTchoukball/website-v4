<?php
$referees = getReferees(true);
$currentLevel = -1;
echo '<div class="referee-list">';
foreach ($referees as $referee) {
    if ($currentLevel != $referee['levelId']) {
        echo "<h2 class='alt'>" . VAR_LANG_ARBITRES . " " . chif_rome($referee['levelId'] - 1) . "</h2>";
        $currentLevel = $referee['levelId'];
    }

    if ($referee['suspendu'] != 1 && $referee['hidden'] != 1) {
        afficherArbitre($referee);
    }
}
echo '</div>';
