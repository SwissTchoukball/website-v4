<?php

$memberQuery = "SELECT p.idDbdPersonne AS id, p.nom, p.prenom, p.raisonSociale, c.nbIdClub AS idClubActuel, c.club AS nomClubActuel
      FROM DBDPersonne p, clubs c
      LEFT OUTER JOIN clubs c ON p.idClub=c.nbIdClub 
      WHERE p.idDbdPersonne=" . $_GET['transfer-request'] . "
      LIMIT 1";
if ($memberResource = mysql_query($memberQuery)) {
    $member = mysql_fetch_assoc($memberResource);
    if ($member['prenom'] == "" && $member['nom'] == "") {
        $name = $member['raisonSociale'];
    } else {
        $name = $member['prenom'] . ' ' . $member['nom'];
    }

    if ($member['idClubActuel'] == null) {
        $currentClubName = "Aucun club";
    } else {
        $currentClubName = $member['nomClubActuel'];
    }
    ?>
    <h3>Demande de transfert pour <?php echo $name; ?></h3>
    <form class="st-form" method="post" onchange="updateNewClubName();" name="transfer-request"
          action="?<?php echo $navigation->getCurrentPageLinkQueryString(); ?>">
        <label>Club d'origine</label>
        <p class="givenData"><?php echo $currentClubName; ?></p>

        <label>Nouveau club</label>
        <?php afficherListeClubs($member['idClubActuel']); ?>
        <input type="hidden" name="memberID" value="<?php echo $member['id']; ?>"/>
        <input type="hidden" name="memberName" value="<?php echo $name; ?>"/>
        <input type="hidden" name="currentClubID" value="<?php echo $member['idClubActuel']; ?>"/>
        <input type="hidden" name="currentClubName" value="<?php echo $currentClubName; ?>"/>
        <input type="hidden" name="newClubName"
               value="<?php echo $currentClubName; /* It will change with JavaScript*/ ?>"/>
        <input type="hidden" name="postType" value="transfer-request"/>
        <input type="submit" class="button button--primary" value="Envoyer la demande de transfert"/>
    </form>
    <?php
} else {
    echo '<p class="error">Erreur lors de la r�cup�ration des donn�es du membre.<br />' . mysql_error() . '</p>';
}

?>
<script type="text/javascript">
    function updateNewClubName() {
        var newClubName = $("select[name=clubs] option:selected").text();
        $("input[name=newClubName]").attr("value", newClubName);
    }
</script>
