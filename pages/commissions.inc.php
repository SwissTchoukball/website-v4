<?php
statInsererPageSurf(__FILE__);

$requeteSQLCommission = "SELECT cn.id, cn.nomCom" . $_SESSION["__langue__"] . " AS nomCommission, cn.lien, p.idDbdPersonne, p.nom, p.prenom, p.adresse, p.cp, p.npa, p.ville, p.emailFederation, p.email, p.telPrive, p.portable
						 FROM Commission_Nom cn
						 LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = cn.idResponsable
						 WHERE visible = 1
						 ORDER BY nomCommission";
$recordsetCommission = mysql_query($requeteSQLCommission);
while ($recordCommission = mysql_fetch_array($recordsetCommission)) {
    showCommission($recordCommission);
}
