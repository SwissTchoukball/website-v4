<?php
session_start();
include "includes/var.href.inc.php";


$host = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if($_SESSION["__userLevel__">100]){
	header("Location: http://$host$uri".VAR_HREF_PAGE_ADMIN,true);
}

@mysql_pconnect("localhost","kuix_tchoukball","jsdQs22YG1X92jW") or die ("<H1>Connexion impossible au serveur</H1>");
@mysql_select_db("kuix_tchoukball1") or die ("<H1>Connexion impossible à la base de donnée</H1>");
mysql_set_charset('latin1');

// se faire passer pour la partie admin
$PHP_SELF = "/admin.php";

// les champs existent ?
if(isset($_POST["login"]) && isset($_POST["username"]) && isset($_POST["password"])){
	$usernameLogin = strtolower(mysql_escape_string(strip_tags($_POST["username"])));

	$requeteSQL = "SELECT p.id, nom, prenom, username, userLevel, password, idClub, gestionMembresClub, c.nbIdClub FROM `Personne` p, `ClubsFstb` c WHERE p.`username`='".$usernameLogin."' AND p.`idClub`=c.`id`";
	$resultatSQL = mysql_query($requeteSQL);
	if (!$resultatSQL) {
		header("Location: http://$host$uri/login-fail-4",true);
	} else {

		$record = mysql_fetch_array($resultatSQL);
		if($record===false){
			header("Location: http://$host$uri/login-fail-1",true);
			exit();
		}

		echo $requeteSQL."<br />";
		echo md5($_POST["password"])."==".$record["password"];


		if(md5($_POST["password"])==$record["password"]){
			$_SESSION["__nom__"]=$record['nom'];
			$_SESSION["__prenom__"]=$record['prenom'];
			$_SESSION["__idUser__"]=$record['id'];
			$_SESSION["__username__"]=$record['username'];
			$_SESSION["__userLevel__"]=$record['userLevel'];
			$_SESSION["__idClub__"]=$record['idClub'];
			$_SESSION["__nbIdClub__"]=$record['nbIdClub'];
			$_SESSION["__gestionMembresClub__"]=$record['gestionMembresClub'];

			// gestion de l'autoconnexion par cookie
			if($_POST["autoConnect"]!=""){
				// creation du cookie
				$troisMois = time()+(3600*24*30)*3;

				setcookie("login[username]",$_SESSION["__username__"],$troisMois,"/");
				setcookie("login[password]",$record["password"],$troisMois,"/");
			}

			// Insertion du login dans l'historique des logins
			$maintenant = getdate();
			$requeteSQL = "INSERT INTO `HistoriqueLogin` ( `username` , `date` , `heure` ) VALUES ('".$record["username"]."', '".$maintenant["year"]."-".$maintenant["mon"]."-".$maintenant["mday"]."', '".$maintenant["hours"].":".$maintenant["minutes"].":".$maintenant["seconds"]."')";
			mysql_query($requeteSQL);

			header("Location: http://$host$uri".VAR_HREF_PAGE_ADMIN,true);
		} else{
			header("Location: http://$host$uri/login-fail-2",true);
		}
	}

}
else{
	header("Location: http://$host$uri/login-fail-3",true);
}
?>
