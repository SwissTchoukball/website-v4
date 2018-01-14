<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/includes/DB.class.php';

class UserService
{
    public static function getUserByUsernameOrEmail($usernameOrEmail)
    {
        $db = new DB();

        $db->bind('username', $usernameOrEmail);
        $db->bind('email', $usernameOrEmail);

        $query = "SELECT p.id, nom, prenom, username, userLevel, password, idClub, gestionMembresClub, c.nbIdClub
                   FROM `Personne` p, `clubs` c
                   WHERE (p.`username`= :username OR p.email = :email)
                   AND p.`idClub`=c.`id`";

        // TODO: remove try-catch block as simply forwarding the exception is automatically done.
        try {
            return $db->query($query)[0];
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    public static function login($user, $hashedPassword, $stayLoggedIn)
    {
        if ($hashedPassword == $user['password']) {
            $_SESSION["__nom__"] = $user['nom'];
            $_SESSION["__prenom__"] = $user['prenom'];
            $_SESSION["__idUser__"] = $user['id'];
            $_SESSION["__username__"] = $user['username'];
            $_SESSION["__userLevel__"] = $user['userLevel'];
            $_SESSION['__authdata__'] = base64_encode($user['username'] . ':' . $user['password']);
            $_SESSION["__idClub__"] = $user['idClub'];
            $_SESSION["__nbIdClub__"] = $user['nbIdClub'];
            $_SESSION["__gestionMembresClub__"] = $user['gestionMembresClub'];

            // Creating cookie
            if ($stayLoggedIn) {
                $threeMonths = time() + (3600 * 24 * 30) * 3;
                setcookie("login[username]", $_SESSION["__username__"], $threeMonths, "/");
                setcookie("login[password]", $user["password"], $threeMonths, "/");
            }

            self::logLogin($user['username']);

            return true;
        }
        else {
            return false;
        }
    }

    public static function updatePassword($userId, $newPassword, $newPasswordRepeat)
    {

        $validationErrors = '';
        if (strlen($newPassword) < 8) {
            $validationErrors .= 'Votre mot de passe doit être composé d\'au minimum 8 caractères.<br/>';
        }
        if (!preg_match("#[0-9]+#", $newPassword)) {
            $validationErrors .= 'Votre mot de passe doit contenir au minimum un chiffre<br/>';
        }
        if (!preg_match("#[A-Z]+#", $newPassword)) {
            $validationErrors .= 'Votre mot de passe doit contenir au minimum une lettre en majuscule<br/>';
        }
        if (!preg_match("#[a-z]+#", $newPassword)) {
            $validationErrors .= 'Votre mot de passe doit contenir au minimum une lettre en minuscule<br/>';
        }
        if (!preg_match("#[\W]+#", $newPassword)) {
            $validationErrors .= 'Votre mot de passe doit contenir au minimum un caractère spécial<br/>';
        }

        if (strlen($validationErrors) > 0) {
            throw new Exception($validationErrors, 400);
        }

        if ($newPassword != $newPasswordRepeat) {
            throw new Exception('Vous n\'avez pas entré deux fois le même mot de passe.', 400);
        }

        $hashedPassword = md5($newPassword);

        $db = new DB();
        $db->bind('userId', $userId);
        $db->bind('hashedPassword', $hashedPassword);

        $query = "UPDATE Personne SET password = :hashedPassword WHERE id = :userId LIMIT 1";

        try {
            return $db->query($query);
        } catch (PDOException $exception) {
            throw new Exception('Erreur lors de la modification du mot de passe.', 500);
        }
    }

    public static function savePasswordResetRequest($userId, $expirationDate, $token) {
        $db = new DB();
        $db->bind('userId', $userId);
        $db->bind('expirationDate', $expirationDate->format('c'));
        $db->bind('token', $token);

        $query = "INSERT INTO password_reset (userId, expirationDate, token)
             VALUES(:userId, :expirationDate, :token)";

        try {
            return $db->query($query);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    private static function logLogin($username)
    {
        $db = new DB();

        $now = getdate();

        $db->bind('username', $username);
        $db->bind('loginDate', $now['year'] . '-' . $now['mon'] . '-' . $now['mday']);
        $db->bind('loginTime', $now['hours'] . ':' . $now['minutes'] . ':' . $now['seconds']);

        $query = "INSERT INTO `HistoriqueLogin` ( `username` , `date` , `heure` )
                       VALUES (:username, :loginDate, :loginTime)";

        // TODO: remove try-catch block as simply forwarding the exception is automatically done.
        try {
            return $db->query($query);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }
}