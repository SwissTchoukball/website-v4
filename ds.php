<? // À vérifier, mais ce fichier n'a plus l'air d'être utilisé (supprimer.auto.connexion.php s'occupe de tout)

session_start();
include "includes/var.href.inc.php";







session_destroy();

$host = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: http://$host$uri/".VAR_HREF_PAGE_PRINCIPALE,true);
?>