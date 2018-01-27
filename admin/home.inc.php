<?php
statInsererPageSurf(__FILE__);

?>
<div class="homepage-admin">
    <img class="logoClub" src="<?php echo VAR_IMAGE_LOGO_CLUBS . $_SESSION['__idClub__'] . '.png'; ?>" />
    <h1><?php echo VAR_LANG_HELLO . ' ' . $_SESSION["__prenom__"]; ?></h1>
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
