<?php
$committeeQuery = "SELECT p.idDbdPersonne, p.nom, p.prenom, p.idSexe,
						  cf.titreH" . $_SESSION['__langue__'] . " AS titreH, cf.titreF" . $_SESSION['__langue__'] . " AS titreF,
						  p.adresse, p.cp, p.npa, p.ville, p.email, p.emailFSTB, p.telPrive, p.portable
			       FROM Comite_Membres cm
			       LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = cm.idPersonne
			       LEFT OUTER JOIN Comite_Fonctions cf ON cf.id = cm.idFonction
			       WHERE cm.dateFin >= CURDATE() AND cm.dateDebut < CURDATE()
			       ORDER BY cf.ordre, p.nom, p.prenom";
$committeeData = mysql_query($committeeQuery) or die ("<H1>mauvaise requete</H1>");
while ($committeeMember = mysql_fetch_array($committeeData)) {
    showCommitteeMember($committeeMember);
}
?>