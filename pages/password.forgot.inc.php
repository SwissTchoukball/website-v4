<?php
if (isset($_POST['email'])) {

    $email = strtolower(mysql_real_escape_string(strip_tags($_POST["email"])));

    // Get user ID
    unset($userId);
    $queryUser = "SELECT id, username FROM Personne WHERE email='$email'";
    if (!$userResource = mysql_query($queryUser)) {
        printErrorMessage(
            'Erreur lors de la récupération de vos informations.' .
            'Contactez le <a href="mailto:webmaster@tchoukball.ch">webmaster</a>'
        );
        exit;
    } else {
        if (mysql_num_rows($userResource) == 1) {
            $user = mysql_fetch_assoc($userResource);
            $userId = $user['id'];
            $username = $user['username'];
        }
    }

    $body = "Une demande de réinitialisation de mot de passe a été faite pour " .
        "<strong>" . $email . ".</strong><br />";

    if (isset($userId) && isset($username)) {
        $body .= "<strong>" . $username . "</strong> est le nom d\'utilisateur associé.<br /><br />";

        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $passwordResetLink = 'https://tchoukball.ch/reset-password/' . $token;
        $body .= '<a href="' . $passwordResetLink . '">' . 'Réinitialiser mon mot de passe</a><br /><br />';
        $body .= 'Ce lien est valide 24h.';

        $expirationDate = (new \DateTime())->modify('+1 day');

        // Save password reset request in DB
        $queryPasswordResetRequest =
            "INSERT INTO password_reset (userId, expirationDate, token)
             VALUES(" . $userId. ", '" . $expirationDate->format('c') . "', '" . $token . "')";
        if (!mysql_query($queryPasswordResetRequest)) {
            printErrorMessage('Erreur lors de la sauvegarde la demande de réinitialisation.');
            exit;
        }
    } else {
        $body .= "Il n'existe toutefois aucun compte avec cette adresse e-mail.";
    }

    echo $body;

    $subject = "Réinitialisation de votre mot de passe Swiss Tchoukball";

    $from = "From:no-reply@tchoukball.ch\n";
    $from .= "MIME-version: 1.0\n";
    $from .= "Content-type: text/html; charset= iso-8859-1\n";
    if (mail($email, $subject, $body, $from)) {
        printSuccessMessage('Un e-mail de réinitialisation du mot de passe vous a été envoyé');
    } else {
        printErrorMessage('Erreur lors de l\'envoi de l\'e-mail de réinitialisation.');
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
