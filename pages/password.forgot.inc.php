<?php
if (isset($_POST['email'])) {

    $email = strtolower(strip_tags($_POST["email"]));

    // Get user ID
    unset($userId);
    try {
        $user = UserService::getUserByUsernameOrEmail($email);
    }
    catch(Exception $exception) {
        printErrorMessage(
            'Erreur lors de la r�cup�ration de vos informations.' .
            'Contactez le <a href="mailto:webmaster@tchoukball.ch">webmaster</a>'
        );
        exit;
    }

    $userId = $user['id'];
    $username = $user['username'];

    $body = "Une demande de r�initialisation de mot de passe a �t� faite pour " .
        "<strong>" . $email . ".</strong><br />";

    if (isset($userId) && isset($username)) {
        $body .= "<strong>" . $username . "</strong> est le nom d'utilisateur associ�.<br /><br />";

        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $passwordResetLink = 'https://tchoukball.ch/reset-password/' . $token;
        $body .= '<a href="' . $passwordResetLink . '">' . 'R�initialiser mon mot de passe</a><br /><br />';
        $body .= 'Ce lien est valide 24h.';

        $expirationDate = (new \DateTime())->modify('+1 day');

        try {
            UserService::savePasswordResetRequest($userId, $expirationDate, $token);
        }
        catch(Exception $exception) {
            printErrorMessage('Erreur lors de la sauvegarde la demande de r�initialisation.');
            exit;
        }
    } else {
        $body .= "Il n'existe toutefois aucun compte avec cette adresse e-mail.";
    }

    $subject = "R�initialisation de votre mot de passe Swiss Tchoukball";

    $from = "From:no-reply@tchoukball.ch\n";
    $from .= "MIME-version: 1.0\n";
    $from .= "Content-type: text/html; charset= iso-8859-1\n";
    if (mail($email, $subject, $body, $from)) {
        printSuccessMessage('Un e-mail de r�initialisation du mot de passe vous a �t� envoy�');
    } else {
        printErrorMessage('Erreur lors de l\'envoi de l\'e-mail de r�initialisation.');
    }
} else {
    ?>
    <form name="forgotPassword" method="post" action="/forgot-password" class="st-form">
        <label for="emailInput"><?php echo VAR_LANG_EMAIL; ?></label>
        <input name="email"
               id="emailInput"
               type="text"
               autocorrect="off"
               autocapitalize="off"
               spellcheck="false"
               size="35"/>

        <input type="submit" class="button button--primary" value="<?php echo VAR_LANG_SEND_RESET_EMAIL; ?>">
    </form>
    <?php
}
?>
