<div class="statistiqueLoginAdmin">
<?
$requeteSQL = "SELECT * , count( id ) nbLogin
								FROM Personne, HistoriqueLoginAdmin
								WHERE Personne.id = HistoriqueLoginAdmin.idPersonne
								GROUP BY Personne.id
								ORDER BY nbLogin DESC";

include "statistique.personne.inc.php";
?>
</div>