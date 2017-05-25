<div class="login">
    <?php
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 1:
                $messageErreurLogin = "Vous n'êtes pas dans la base de données.";
                break;
            case 2:
                $messageErreurLogin = "mot de passe incorrect";
                break;
            case 3:
                $messageErreurLogin = "Erreur de sécurité.";
                break;
            case 4:
                $messageErreurLogin = "Erreur SQL.";
                break;
            default:
                $messageErreurLogin = "Erreur inconnue.";
        }

        echo '<p class="notification notification--error">' . $messageErreurLogin . '</p>';
    }

    ?>
    <form name="log" method="post" action="/gestionLogin.php" class="st-form">
        <label for="usernameInput"><?php echo VAR_LANG_USERNAME; ?></label>
        <input name="username" id="usernameInput" type="text" autocorrect="off" autocapitalize="off" spellcheck="false"
               size="35" maxlength="35">

        <label for="passwordInput"><?php echo VAR_LANG_PASSWORD; ?></label>
        <input name="password" id="passwordInput" type="password" size="35" maxlength="35">

        <label for="autoConnectCheckbox" align="center"><?php echo VAR_LANG_AUTO_CONNECTION; ?></label>
        <input class="couleurCheckBox" id="autoConnectCheckbox" type="checkbox" name="autoConnect">

        <input type="submit" class="button button--primary" name="login" value="<?php echo VAR_LANG_SE_LOGUER; ?>">
    </form>
</div>
