<?php
$showPasswordResetForm = false;
unset($userId);

function getUserIdFromPasswordResetToken($token)
{
    $queryPasswordResetCheck = "SELECT userId
    FROM password_reset
    WHERE token = '$token'
    AND expirationDate > CURRENT_DATE()
    AND used = 0";
    if (!$passwordResetCheckResource = mysql_query($queryPasswordResetCheck)) {
        throw new Exception('Erreur lors de la récupération de vos informations.', 500);
    } else {
        if (mysql_num_rows($passwordResetCheckResource) == 1) {
            $passwordResetData = mysql_fetch_assoc($passwordResetCheckResource);
            return $passwordResetData['userId'];
        } else {
            throw new Exception(
                'Votre requête n\'est pas valide. Il se peut que votre lien ait expiré ou qu\'il ait déjà été utlilisé.',
                410
            );
        }
    }
}

function markPasswordResetTokenAsUsed($token)
{
    $query = "UPDATE password_reset SET used = 1 WHERE token = '$token' LIMIT 1";
    if (!mysql_query($query)) {
        throw new Exception('Erreur SQL', 500);
    }
}

// User comes from link in reset e-mail
if (isset($_GET['token'])) {
    $token = strtolower(mysql_real_escape_string(strip_tags($_GET["token"])));

    try {
        $userId = getUserIdFromPasswordResetToken($token);
        $showPasswordResetForm = true;
    } catch (Exception $e) {
        printErrorMessage($e->getMessage());
    }
}

// User has submitted a new password
if (isset($_POST['token']) &&
    isset($_POST['password']) &&
    isset($_POST['passwordRepeat'])) {
    $token = strtolower(mysql_real_escape_string(strip_tags($_POST["token"])));

    try {
        $userId = getUserIdFromPasswordResetToken($token);
        updatePassword($userId, $_POST['password'], $_POST['passwordRepeat']);
        markPasswordResetTokenAsUsed($token);
        printSuccessMessage('Mot de passe modifié. <a href="/login">Se connecter</a>');
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        if ($e->getCode() == 400) {
            $errorMessage .= ' <a href="/reset-password/' . $token . '">Réessayer</a>';
        } else if ($e->getCode() == 500) {
            $errorMessage .= ' Contactez le <a href="mailto:webmaster@tchoukball.ch">webmaster</a>';
        }
        printErrorMessage($errorMessage);
    }
}

// TODO: JS check for passwords

if ($showPasswordResetForm && isset($userId) && isset($token)) {
    printMessage(
        'Votre mot de passe doit faire minimum 8 caractères et contenir au minimum un chiffre, une lettre minuscule, ' .
        'une lettre majuscule et un caractère spécial.'
    );
    ?>
    <form name="resetPassword" method="post" action="/reset-password" class="st-form">
        <label for="passwordInput"><?php echo VAR_LANG_PASSWORD; ?></label>
        <input name="password"
               id="passwordInput"
               type="password"
               autocorrect="off"
               autocapitalize="off"
               spellcheck="false"
               size="35"/>

        <label for="passwordRepeatInput"><?php echo VAR_LANG_REPEAT_PASSWORD; ?></label>
        <input name="passwordRepeat"
               id="passwordRepeatInput"
               type="password"
               autocorrect="off"
               autocapitalize="off"
               spellcheck="false"
               size="35"/>

        <input type="hidden" name="token" value="<?php echo $token; ?>"/>
        <input type="submit" class="button button--primary" value="<?php echo VAR_LANG_RESET_PASSWORD; ?>">
    </form>
    <?php
}
