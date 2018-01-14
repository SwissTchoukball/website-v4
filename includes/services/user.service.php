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