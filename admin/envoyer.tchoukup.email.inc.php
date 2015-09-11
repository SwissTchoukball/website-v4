<?php

$path = 'templates/emails/e-tchoukup.html';

$recipients = array();
$recipients[0] = 'webmaster@tchoukball.ch';
$recipients[1] = 'abonnes.tchoukup@tchoukball.ch';

// Le lien direct vers le template n'affiche pas bien les accents car le fichier est en ISO Latin,
// mais l'encodage indiqué par le HTML indique de l'UTF-8.
// echo '<p><a href="' . $path . '" target="_blank">Voir le template</a></p>';

if (isset($_POST['recipient']) && is_numeric($_POST['recipient'])) {
	//TODO Réfléchir pour plutôt accéder directement à la BDD et envoyer des e-mails individuels.
	//	   Cela permettrait de choisir entre l'e-mail tchoukball.ch et l'e-mail.

	$to = $recipients[$_POST['recipient']];

	$subject = "Le nouveau numÃ©ro du Tchoukup vous attend !"; // Garder l'accent tel quel. Le site est en ISO Latin et le mail est en UTF-8
	$subject = utf8_decode($subject);
	$subject = mb_encode_mimeheader($subject,"UTF-8");
	if(file_exists($path)) {
		ob_start();
	    require($path);
	    $message = ob_get_contents();
	    ob_end_clean();
	    $message = utf8_encode($message);

		$headers  = 'MIME-version: 1.0' . "\r\n";
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
echo '<form action="?menuselection=' . $menuselection . '&smenuselection=' . $smenuselection . '" method="post" class="adminForm">';
echo '<select name="recipient">';
foreach($recipients as $key => $recipient) {
	echo '<option value="' . $key . '">' . $recipient . '</option>';
}
echo '</select>';
echo '<input type="submit" value="Envoyer" />';
echo '</form>';
?>