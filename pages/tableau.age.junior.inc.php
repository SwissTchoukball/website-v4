<?php
$annee = annee(date_actuelle());


$i = 1;
while ($i < 3) {
    if ($i == 2) {
        $annee++;
    }
    echo "<p>";
    echo "<h3>";
    echo "Saison ";
    echo $annee - 1;
    echo " - ";
    echo $annee;
    echo "</h3>";
    ?>
    <table class="tableauAgeJunior">
        <tr>
            <th rowspan="2"><?php echo VAR_LANG_ANNEE_DE_NAISSANCE; ?></th>
            <th rowspan="2"><?php echo VAR_LANG_AGE_EN . "<br />" . $annee; ?></th>
            <th colspan="3"><?php echo VAR_LANG_CATEGORIES; ?></th>
        </tr>
        <tr>
            <th><?php echo VAR_LANG_COMPETITIONS; ?></th>
            <th><?php echo VAR_LANG_JEUNESSESPORT; ?></th>
            <th><?php echo VAR_LANG_ASSOCIATION_NAME; ?></th>
        </tr>
        <tr>
            <td><?php echo $annee - 21; ?> et moins</td>
            <td>21+</td>
            <td rowspan="3">Adultes</td>
            <td>-</td>
            <td>Membres actifs</td>
        </tr>
        <tr>
            <td><?php echo $annee - 20; ?></td>
            <td>20</td>
            <td rowspan="12">Sport des jeunes</td>
            <td rowspan="20">Membres juniors</td>
        </tr>
        <tr>
            <td><?php echo $annee - 19; ?></td>
            <td>19</td>
        </tr>
        <tr>
            <td><?php echo $annee - 18; ?></td>
            <td>18</td>
            <td rowspan="3">M18</td>
        </tr>
        <tr>
            <td><?php echo $annee - 17; ?></td>
            <td>17</td>
        </tr>
        <tr>
            <td><?php echo $annee - 16; ?></td>
            <td>16</td>
        </tr>
        <tr>
            <td><?php echo $annee - 15; ?></td>
            <td>15</td>
            <td rowspan="3">M15</td>
        </tr>
        <tr>
            <td><?php echo $annee - 14; ?></td>
            <td>14</td>
        </tr>
        <tr>
            <td rowspan="2"><?php echo $annee - 13; ?></td>
            <td rowspan="2">13</td>
        </tr>
        <tr>
            <td rowspan="3">Noir</td>
        </tr>
        <tr>
            <td><?php echo $annee - 12; ?></td>
            <td>12</td>
        </tr>
        <tr>
            <td rowspan="2"><?php echo $annee - 11; ?></td>
            <td rowspan="2">11</td>
        </tr>
        <tr>
            <td rowspan="3">Rouge</td>
        </tr>
        <tr>
            <td><?php echo $annee - 10; ?></td>
            <td>10</td>
            <td rowspan="9">Sport des enfants</td>
        </tr>
        <tr>
            <td rowspan="2"><?php echo $annee - 9; ?></td>
            <td rowspan="2">9</td>
        </tr>
        <tr>
            <td rowspan="3">Bleu</td>
        </tr>
        <tr>
            <td><?php echo $annee - 8; ?></td>
            <td>8</td>
        </tr>
        <tr>
            <td rowspan="2"><?php echo $annee - 7; ?></td>
            <td rowspan="2">7</td>
        </tr>
        <tr>
            <td rowspan="3">Vert</td>
        </tr>
        <tr>
            <td><?php echo $annee - 6; ?></td>
            <td>6</td>
        </tr>
        <tr>
            <td><?php echo $annee - 5; ?></td>
            <td>5</td>
        </tr>
    </table>
    <?php
    echo "</p>";
    $i++;
}
?>
