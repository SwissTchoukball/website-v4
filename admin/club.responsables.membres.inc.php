<h3>Gestionnaires des membres de club</h3>
<table>
    <thead>
    <tr>
        <th>Club</th>
        <th>Nom et prénom</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $membersManagersRequest = "SELECT p.nom, p.prenom, c.club, p.email FROM Personne p, clubs c WHERE p.idClub=c.id AND p.gestionMembresClub=1 ORDER BY c.club";
    //echo $membersRequest;
    $allEmails = "";
    $membersManagersResult = mysql_query($membersManagersRequest);
    while ($membersManagers = mysql_fetch_assoc($membersManagersResult)) {
        $membersManagerEmail = $membersManagers['email'];
        if ($membersManagerEmail != "") {
            $allEmails .= $membersManagers['email'] . ",";
        }
        ?>
        <tr>
            <td><?php echo $membersManagers['club']; ?></td>
            <td>
                <?php
                if ($membersManagerEmail != "") {
                    echo '<a href="mailto:' . $membersManagerEmail . '">';
                }

                echo '<strong>' . $membersManagers['nom'] . '</strong> ' . $membersManagers['prenom'];

                if ($membersManagerEmail != "") {
                    echo '</a>';
                }
                ?>
            </td>
        </tr>
        <?php

    }
    ?>
    </tbody>
</table>
<p><a href="mailto:<?php echo $allEmails; ?>">Envoyer un e-mail à tous</a></p>
