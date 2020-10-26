<?php
function showTableYear($year) {

    echo "<p>";
    echo "<h3>";
    echo "Saison ";
    echo $year;
    echo " - ";
    echo $year + 1;
    echo "</h3>";
    ?>
    <table class="tableauAgeJunior">
        <tr>
            <th rowspan="2"><?php echo VAR_LANG_ANNEE_DE_NAISSANCE; ?></th>
            <th rowspan="2"><?php echo VAR_LANG_AGE_EN . "<br />" . $year; ?></th>
            <th colspan="3"><?php echo VAR_LANG_CATEGORIES; ?></th>
        </tr>
        <tr>
            <th><?php echo VAR_LANG_COMPETITIONS; ?></th>
            <th><?php echo VAR_LANG_JEUNESSESPORT; ?></th>
            <th><?php echo VAR_LANG_ASSOCIATION_NAME; ?></th>
        </tr>
        <tr>
            <td><?php echo $year - 21; ?> et moins</td>
            <td>21+</td>
            <td rowspan="4">Adultes</td>
            <td>-</td>
            <td rowspan="2">Membres actifs</td>
        </tr>
        <tr>
            <td><?php echo $year - 20; ?></td>
            <td>20</td>
            <td rowspan="10">Sport des jeunes</td>
        </tr>
        <tr>
            <td><?php echo $year - 19; ?></td>
            <td>19</td>
            <td rowspan="15">Membres juniors</td>
        </tr>
        <tr>
            <td><?php echo $year - 18; ?></td>
            <td>18</td>
        </tr>
        <tr>
            <td><?php echo $year - 17; ?></td>
            <td>17</td>
            <td rowspan="3">M18</td>
        </tr>
        <tr>
            <td><?php echo $year - 16; ?></td>
            <td>16</td>
        </tr>
        <tr>
            <td><?php echo $year - 15; ?></td>
            <td>15</td>
        </tr>
        <tr>
            <td><?php echo $year - 14; ?></td>
            <td>14</td>
            <td rowspan="3">M15</td>
        </tr>
        <tr>
            <td><?php echo $year - 13; ?></td>
            <td>13</td>
        </tr>
        <tr>
            <td><?php echo $year - 12; ?></td>
            <td>12</td>
        </tr>
        <tr>
            <td><?php echo $year - 11; ?></td>
            <td>11</td>
            <td rowspan="2">M12</td>
        </tr>
        <tr>
            <td><?php echo $year - 10; ?></td>
            <td>10</td>
            <td rowspan="8">Sport des enfants</td>
        </tr>
        <tr>
            <td><?php echo $year - 9; ?></td>
            <td>9</td>
            <td rowspan="2">M10</td>
        </tr>
        <tr>
            <td><?php echo $year - 8; ?></td>
            <td>8</td>
        </tr>
        <tr>
            <td><?php echo $year - 7; ?></td>
            <td>7</td>
            <td rowspan="3">M8</td>
        </tr>
        <tr>
            <td><?php echo $year - 6; ?></td>
            <td>6</td>
        </tr>
        <tr>
            <td><?php echo $year - 5; ?></td>
            <td>5</td>
        </tr>
    </table>
    <?php
    echo "</p>";
}

$currentSeasonStartYear = date('Y');
if (date('n') < 8) {
    $currentSeasonStartYear--;
}
showTableYear($currentSeasonStartYear);
?>
