<?php

$statRequest = "SELECT
				(SELECT COUNT(idDbdPersonne) FROM DBDPersonne WHERE idClub=" . $_SESSION['__nbIdClub__'] . " AND idStatus=3) AS nbActiveAdultsMembers,
				(SELECT COUNT(idDbdPersonne) FROM DBDPersonne WHERE idClub=" . $_SESSION['__nbIdClub__'] . " AND idStatus=6) AS nbJuniorMembers,
				(SELECT SUM(IF(idSexe=2,1,0)) FROM DBDPersonne WHERE idClub=" . $_SESSION['__nbIdClub__'] . " AND (idStatus=3 OR idStatus=6)) AS nbActiveMale,
				(SELECT SUM(IF(idSexe=3,1,0)) FROM DBDPersonne WHERE idClub=" . $_SESSION['__nbIdClub__'] . " AND (idStatus=3 OR idStatus=6)) AS nbActiveFemale,
				(SELECT COUNT(idDbdPersonne) FROM DBDPersonne WHERE idClub=" . $_SESSION['__nbIdClub__'] . " AND idStatus=4) AS nbPassiveMembres,
				(SELECT COUNT(idDbdPersonne) FROM DBDPersonne WHERE idClub=" . $_SESSION['__nbIdClub__'] . " AND idStatus=5) AS nbSupportMembres,
				(SELECT COUNT(idDbdPersonne) FROM DBDPersonne WHERE idClub=" . $_SESSION['__nbIdClub__'] . " AND idStatus=23) AS nbVIPMembres";
//echo $statRequest;
$statResult = mysql_query($statRequest);
$statData = mysql_fetch_assoc($statResult);
$nbActiveMembers = $statData['nbActiveAdultsMembers'] + $statData['nbJuniorMembers'];
$totalNbMembers = $nbActiveMembers + $statData['nbPassiveMembres'] + $statData['nbSupportMembres'] + $statData['nbVIPMembres'];

echo "<br />";

//Affichage des statistiques uniquement s'il y a des membres actifs.
if ($nbActiveMembers != 0) {
    echo '<p>';
    echo $totalNbMembers . ' ' . ajouterSSiPluriel("membre", $totalNbMembers) . ' au total<br />';

    echo $nbActiveMembers . ' ' . ajouterSSiPluriel("membre", $nbActiveMembers) . ' ' . ajouterSSiPluriel("actif",
            $nbActiveMembers) .
        ' (' . $statData['nbActiveAdultsMembers'] . ' ' . ajouterSSiPluriel("adulte",
            $statData['nbActiveAdultsMembers']) . ' et ' .
        $statData['nbJuniorMembers'] . ' ' . ajouterSSiPluriel("junior", $statData['nbJuniorMembers']) . '), ' .
        $statData['nbActiveMale'] . ' ' . ajouterSSiPluriel("homme",
            $statData['nbActiveMale']) . ', (' . round(($statData['nbActiveMale'] / $nbActiveMembers) * 100,
            2) . '%), ' .
        $statData['nbActiveFemale'] . ' ' . ajouterSSiPluriel("femme",
            $statData['nbActiveFemale']) . ', (' . round(($statData['nbActiveFemale'] / $nbActiveMembers) * 100,
            2) . '%)<br />';

    echo $statData['nbPassiveMembres'] . ' ' . ajouterSSiPluriel("membre",
            $statData['nbPassiveMembres']) . ' ' . ajouterSSiPluriel("passif",
            $statData['nbPassiveMembres']) . '<br />';
    echo $statData['nbSupportMembres'] . ' ' . ajouterSSiPluriel("membre",
            $statData['nbSupportMembres']) . ' ' . ajouterSSiPluriel("soutien",
            $statData['nbSupportMembres']) . '<br />';
    echo $statData['nbVIPMembres'] . ' ' . ajouterSSiPluriel("membre", $statData['nbVIPMembres']) . ' VIP';
    echo '</p>';
}

echo "<br />";

?>