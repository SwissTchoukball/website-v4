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
    try {
        $clubManagers = UserService::getClubManagers();
    }
    catch(Exception $exception) {
        printErrorMessage($exception->getMessage());
        die($exception->getMessage());
    }

    $allEmails = "";

    foreach ($clubManagers as $clubManager) {
        $clubManagerEmail = $clubManager['email'];
        if ($clubManagerEmail != "") {
            $allEmails .= $clubManagerEmail . ",";
        }
        ?>
        <tr>
            <td><?php echo $clubManager['clubName']; ?></td>
            <td>
                <?php
                if ($clubManagerEmail != "") {
                    echo '<a href="mailto:' . $clubManagerEmail . '">';
                }

                echo '<strong>' . $clubManager['nom'] . '</strong> ' . $clubManager['prenom'];

                if ($clubManagerEmail != "") {
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
