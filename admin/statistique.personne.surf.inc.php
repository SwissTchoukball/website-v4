<div class="statistiqueLoginSurf">
<?
$requeteSQL = "SELECT * , count( id ) nbLogin
								FROM Personne, HistoriqueLogin
								WHERE Personne.id = HistoriqueLogin.idPersonne
								GROUP BY Personne.id
								ORDER BY nbLogin DESC";

include "statistique.personne.inc.php";
?>
</div