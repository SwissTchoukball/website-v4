<?php

// liste des liens
if ($_SESSION["debug_tracage"]) {
    echo __FILE__ . "<BR>";
}

define("VAR_HREF_PAGE_PRINCIPALE", "/");
define("VAR_HREF_PAGE_ADMIN", "/admin.php");
define("VAR_HREF_LIEN_MENU", "lien");

define("VAR_IMAGE_PORTRAITS_PATH", "pictures/portraits/"); //server name added later as not compatible with is_file()
define("VAR_IMAGE_PHOTOS_EQUIPES_PATH", "/pictures/equipesSuisses/photosEquipes/");
define("VAR_IMAGE_LOGO_CLUBS", "/pictures/logos_clubs/");
define("VAR_IMAGE_LANGUE", "/pictures/langues/");

