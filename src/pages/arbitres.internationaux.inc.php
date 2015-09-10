<?

if($_SESSION["debug_tracage"])echo __FILE__."<BR>";
statInsererPageSurf(__FILE__);


// Arbitres

?>
<table class="arbitres-es">	
	<?
		echo "<tr><th colspan='2'>".VAR_LANG_ARBITRE_INTER."</th></tr>";
		$requeteSQL = "SELECT * FROM `DBDPersonne` WHERE `idArbitre`='3' ORDER BY `nom`, `prenom`"; // arbitres internationaux
		$recordset = mysql_query($requeteSQL);
		while($record = mysql_fetch_array($recordset)){
			afficherArbitre($record, true);
		}
	?>
</table>
