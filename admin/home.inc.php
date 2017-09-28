<?php
statInsererPageSurf(__FILE__);

$userQuery = "
    SELECT p.nom, p.prenom, p.username, c.club, c.id AS clubId
    FROM `Personne` p,`ClubsFstb` c
    WHERE p.`username`='" . addslashes($_SESSION["__username__"]) . "'
    AND p.`idClub`=c.`id`";

$userResource = mysql_query($userQuery) or die(printErrorMessage('Erreur lors de l\'accès à vos données'));
$user = mysql_fetch_array($userResource);

?>
<div class="homepage-admin">
    <img class="logoClub" src="<?php echo VAR_IMAGE_LOGO_CLUBS . $user['clubId'] . '.png'; ?>" />
    <h1><?php echo VAR_LANG_HELLO . ' ' . $user["prenom"]; ?></h1>
    <p><?php echo VAR_LANG_ADMIN_WELCOME; ?></p>
    <p>
        Pour en savoir plus sur le fonctionnement de la fédération, vous pouvez consulter
        le <a href="https://wiki.tchoukball.ch" target="_blank">wiki de Swiss Tchoukball</a>.
    </p>
    <p>
        Vous y trouverez notamment des
        <a href="https://wiki.tchoukball.ch/Marches_%C3%A0_suivre" target="_blank">marches à suivre</a>
        pour vous aider dans l'utilisation de certaines fonctionnalités de l'administation.
    </p>
    <p>
        Si vous rencontrez des problèmes liés à l'utilisation de l'administration, vous pouvez contacter le
        <a href="mailto:admin@tchoukball.ch">responsable du site web</a>.
    </p>
</div>
