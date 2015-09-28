<table class="tableauStatistiquePersonne">
<?php

function afficherStatistique($ratio){
	$sizeImage = getimagesize(VAR_LOOK_path."fond_stat_colore.jpg");
	$longueurRecouvrement = $sizeImage[0]*(1-$ratio);
	echo "<table align='center' border='0' cellpadding='0' cellspacing='0'><tr>";
		echo "<td class='noborder' width='1px'><img src='".VAR_LOOK_path."partie_stat_gauche.gif'></td>";
		echo "<td class='noborder' width='".$sizeImage[0]."px' height='".$sizeImage[1]."px' background='".VAR_LOOK_path."fond_stat_colore.jpg' align='right'>";
		if($longueurRecouvrement!=0) echo "<img src='".VAR_LOOK_path."partie_stat_centrale_recouvrement.gif' width='".$longueurRecouvrement."px' height='".$sizeImage[1]."px'></td>";
		else echo "</td>";
		if($longueurRecouvrement==0) echo "<td class='noborder' width='1px'><img src='".VAR_LOOK_path."partie_stat_droite_full.gif'></td>";
		else echo "<td class='noborder' width='1px'><img src='".VAR_LOOK_path."partie_stat_droite.gif'></td>";
	echo "</tr></table>";
}
$recordset = mysql_query($requeteSQL);

?>
<tr>
    <th>Nom, prénom</th>
    <th>Nb login</th>
    <th>User level</th>
    <th>Pourcentage de login</th>
</tr>
<?php

$premiereFois=true;
$nbLoginMax=0;

while($record = mysql_fetch_array($recordset)){
	// prendre la valeur max
	if($premiereFois){
		$nbLoginMax=$record["nbLogin"];
		$premiereFois=false;
		//attention pour eviter une division par 0
		if($nbLoginMax==0)$nbLoginMax=1;
	}
	echo "<tr>";
        echo "<td>".$record["nom"]." ".$record["prenom"]."</td>";
        echo "<td class='center'>".$record["nbLogin"]."</td>";
        echo "<td class='center'>".$record["userLevel"]."</td>";
        echo "<td>";
            afficherStatistique($record["nbLogin"]/$nbLoginMax);
        echo "</td>";
	echo "</tr>";
}

?>
</table>
