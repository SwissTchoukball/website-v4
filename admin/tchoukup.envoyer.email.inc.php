<?php

$path = 'templates/emails/e-tchoukup.html';

$recipients = array();
$recipients[0] = 'webmaster@tchoukball.ch';
$recipients[1] = 'abonnes.tchoukup@tchoukball.ch';

echo '<p><a href="' . $path . '" target="_blank">Voir le template</a></p>';

if (
    isset($_POST['recipient']) &&
    is_numeric($_POST['recipient']) &&
    isset($_POST['issueNb']) &&
    is_numeric($_POST['issueNb'])
) {
    //TODO Réfléchir pour plutôt accéder directement à la BDD et envoyer des e-mails individuels.
    //	   Cela permettrait de choisir entre l'e-mail tchoukball.ch et l'e-mail.

    $to = $recipients[$_POST['recipient']];

    $templateVars = array();
    $templateVars['issueNb'] = $_POST['issueNb'];

    // Garder le charactère accentué ci-dessous tel quel. Le site est en ISO Latin et le mail est en UTF-8
    $subject = "Le nouveau numÃ©ro du Tchoukup vous attend !";
    $subject = utf8_decode($subject);
    $subject = mb_encode_mimeheader($subject, "UTF-8");
    if (file_exists($path)) {
        $message = file_get_contents($path);
        foreach ($templateVars as $key => $value) {
            $message = str_replace('{{ ' . $key . ' }}', $value, $message);
        }

        $headers = 'MIME-version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: ' . VAR_LANG_ASSOCIATION_NAME . ' <info@tchoukball.ch>' . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
            printSuccessMessage("E-mail envoyé à " . $to);
        } else {
            printErrorMessage("L'envoi de l'e-mail a échoué");
        }
    } else {
        printErrorMessage("Template introuvable");
    }
}

printMessage("Attention ! En cliquant sur <em>Envoyer</em>, l'e-mail sera envoyé sans autre avertissement.");
printMessage("Ne pas oublier de mettre à jour la liste de discussion avant d'envoyer");
$formAction = '?menuselection=' . $menuselection . '&smenuselection=' . $smenuselection;
echo '<form action="' . $formAction . '" method="post" class="st-form">';
echo '<label for="recipient">Destinataire</label>';
echo '<select name="recipient" id="recipient">';
foreach ($recipients as $key => $recipient) {
    echo '<option value="' . $key . '">' . $recipient . '</option>';
}
echo '</select>';
echo '<label for="issueNb">Numéro</label>';
echo '<input type="text" name="issueNb" id="issueNb" />';
echo '<input type="submit" class="button button--primary" value="Envoyer" />';
echo '</form>';
