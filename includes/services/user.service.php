<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/includes/DB.class.php';

class UserService
{
    public static function getByUsernameOrEmail($usernameOrEmail)
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

    public static function logLogin($username)
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