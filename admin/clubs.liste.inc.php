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
    $excludeNonMembers = false;
    $clubs = ClubService::getClubList($excludeNonMembers);
    foreach ($clubs as $club) {
        $rowClass = '';
        if (!$club->isAffiliated()) {
            $rowClass = 'st-table__dimmed';
        }
        ?>
        <tr class="<?php echo $rowClass; ?>">
        <td>
            <?php
            echo $club->fullName . "<br />";
            echo $club->address != "" ? $club->address . "<br />" : "";
            if ($club->npa != "" || $club->city != "") {
                echo $club->npa . " " . $club->city . "<br />";
            }
            ?>
        </td>
        <td>
            <?php
            echo $club->phoneNumber != "" ? "<img src='/admin/images/telPrive.png' alt='Numéro de téléphone'/> " . $club->phoneNumber . "<br />" : "";
            echo $club->email != "" ? "<img src='/admin/images/email.png' alt='Adresse e-mail'/> <a href='mailto:" . $club->email . "'>" . $club->email . "</a><br />" : "";
            echo $club->url != "" ? "<img src='/admin/images/globe.png' alt='Adresse e-mail'/> <a href='" . addhttp($club->url) . "'>" . $club->url . "</a><br />" : "";
            ?>
        </td>
        <td class="action">
            <a href="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>&edit=<?php echo $club->id; ?>"><img
                    src="/admin/images/modifier.png" alt="Modifier un club"/></a>
        </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>