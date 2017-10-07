<?php
include('../config.php');
include('../includes/langue/var.Fr.inc.php');

date_default_timezone_set('Europe/Zurich');

mysql_set_charset('utf8');

$subjectTemplate = "Joyeux anniversaire %s !";

$birthdayMessageTemplate = "
<!DOCTYPE html>
<html>
<head>
    <title>Joyeux anniversaire !</title>
</head>
<body>
    <p>Joyeux anniversaire %s !</p>
    <p>Profitez bien de votre journée, vos amis et votre famille. Nous vous souhaitons une année
    pleine de réussite.</p>
    <p>Nous saisissons cette occasion pour vous remercier pour tout ce que vous faites ou avez fait
    pour de notre sport en Suisse. Vous pouvez être %s d'avoir apporté votre pierre au grand édifice
    qu'est le développement du tchoukball en notre pays.</p>
    <p>Félicitations et prenez votre souffle pour vos %d bougies :-)</p>
    <p>Meilleures salutations sportives</p>
    <p>Le Comité exécutif de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . "</p>
</body>
</html>
";

$webmasterTo = 'webmaster@tchoukball.ch';
$webmasterSubject = "Message d'anniversaire " . VAR_LANG_ASSOCIATION_NAME;

$query = "SELECT nom, prenom, dateNaissance, idSexe,
                 TIMESTAMPDIFF(YEAR, dateNaissance, CURDATE()) AS age, email, emailFederation
          FROM Benevoles
          WHERE DATE_ADD(dateNaissance, INTERVAL YEAR(CURDATE())-YEAR(dateNaissance) YEAR) = CURDATE()
          AND suspendu = 0";
$result = mysql_query($query);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $webmasterMessage = "Des messages d'anniversaire ont été envoyés à:" . "\r\n";
        while ($person = mysql_fetch_assoc($result)) {
            if ($person['emailFederation'] != '') {
                $to = $person['emailFederation'];
                $validEmail = true;
            } elseif ($person['email'] != '') {
                $to = $person['email'];
                $validEmail = true;
            } else {
                $to = '';
                $validEmail = false;
            }
            if ($person['dateNaissance'] != null) {
                $validBirthdate = true;
            } else { // Ne devrait pas arriver comme on sélectionne par rapport à la date de naissance
                $validBirthdate = false;
            }

            $prenom = $person['prenom'];
            $nom = $person['nom'];
            $age = $person['age'];

            if ($validEmail && $validBirthdate) {
                //$to = $webmasterTo;

                $webmasterMessage .= $prenom . " " . $nom . " (" . $age . ")" . "\r\n";

                if ($person['idSexe'] == 3) {
                    $fier = 'fière';
                } else {
                    $fier = 'fier';
                }

                $subject = sprintf($subjectTemplate, $prenom);
                $birthdayMessage = sprintf($birthdayMessageTemplate, $prenom, $fier, $age);

                $headers = 'MIME-version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                $headers .= 'From: ' . VAR_LANG_ASSOCIATION_NAME . ' <comite@tchoukball.ch>' . "\r\n";

                mail($to, $subject, $birthdayMessage, $headers);
                echo $to . '<br />' . $subject . '<br />' . $birthdayMessage . '<br />' . $headers . '<br /><br /><br />';
            } else {
                $noValidEmailMessage = $prenom . ' ' . $nom . ' n\'a pas d\'adresse e-mail valide.'; // Ou date de naissance, mais normalement pas posible.
                echo $noValidEmailMessage;
                $webmasterMessage .= $noValidEmailMessage . "\r\n";
            }

            // echo '<pre>';
            // print_r($person);
            // echo '</pre>';
        }
        mail($webmasterTo, $webmasterSubject, $webmasterMessage);
    } else {
        echo "Pas d'anniversaire aujourd'hui :-(";
    }
} else {
    $errorMessage = "Erreur dans la requête pour le message automatique d'anniversaire";
    mail($webmasterTo, $webmasterSubject, $errorMessage);
}

mysql_close();
