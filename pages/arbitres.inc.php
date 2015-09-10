<?php

$afficherPhoto = false;

for($k=4;$k>=1;$k--){
	echo "<h2 class='alt'>".VAR_LANG_ARBITRES." ".chif_rome($k)."</h2>";
	echo '<table class="arbitres-es">';
	$idArbitreBDD=$k+1;
	$requeteSQL = "SELECT * FROM `DBDPersonne` WHERE `idArbitre`='".$idArbitreBDD."' ORDER BY `nom`, `prenom`";
	$recordset = mysql_query($requeteSQL);
	while($record = mysql_fetch_array($recordset)){
		if($record['suspendu'] != 1 && $record['arbitreMasque'] != 1){
			afficherArbitre($record, $afficherPhoto);
		}
	}
	echo '</table>';
}
?>
