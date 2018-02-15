<table class="st-table">
    <thead>
    <tr>
        <th>Adresse</th>
        <th>Coordonnées</th>
        <th>Édition</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $queryClubs =
        "SELECT c.id, c.nomComplet, c.adresse, c.npa, c.ville, c.email, c.telephone, c.statusId, cs.name" . $_SESSION['__langue__'] . " AS status, c.url
			 FROM clubs c, clubs_status cs
			 WHERE c.id!=0
			 	AND c.statusId = cs.id
			 ORDER BY c.statusId, c.nomPourTri";
    $dataClubs = mysql_query($queryClubs);
    while ($club = mysql_fetch_assoc($dataClubs)) {
        $clubID = $club['id'];
        $clubName = $club['nomComplet'];
        $clubAdress = $club['adresse'];
        $clubNPA = $club['npa'];
        $clubCity = $club['ville'];
        $clubEmail = $club['email'];
        $clubPhone = $club['telephone'];
        $clubURL = $club['url'];
        $clubStatus = $club['statusId'];

        $clubIsAffiliated = $clubStatus == 1 || $clubStatus == 2;

        if ($clubIsAffiliated) {
            echo '<tr>';
        } else {
            echo '<tr class="st-table__dimmed">';
        }
        ?>
        <td>
            <?php
            echo $clubName . "<br />";
            echo $clubAdress != "" ? $clubAdress . "<br />" : "";
            if ($clubNPA != "" || $clubCity != "") {
                echo $clubNPA . " " . $clubCity . "<br />";
            }
            ?>
        </td>
        <td>
            <?php
            echo $clubPhone != "" ? "<img src='/admin/images/telPrive.png' alt='Numéro de téléphone'/> " . $clubPhone . "<br />" : "";
            echo $clubEmail != "" ? "<img src='/admin/images/email.png' alt='Adresse e-mail'/> <a href='mailto:" . $clubEmail . "'>" . $clubEmail . "</a><br />" : "";
            echo $clubURL != "" ? "<img src='/admin/images/globe.png' alt='Adresse e-mail'/> <a href='" . addhttp($clubURL) . "'>" . $clubURL . "</a><br />" : "";
            ?>
        </td>
        <td class="action">
            <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&edit=<?php echo $clubID; ?>"><img
                    src="/admin/images/modifier.png" alt="Modifier un club"/></a>
        </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>