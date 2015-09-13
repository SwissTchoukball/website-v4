<?php

// liste des liens
if ($_SESSION["debug_tracage"]) {
    echo __FILE__."<BR>";
}

define("VAR_HREF_PATH_PAGE_PRINCIPALE", "/pages/");
define("VAR_HREF_PAGE_PRINCIPALE", "/");
define("VAR_HREF_PAGE_PRINCIPALE_RACINE", "/");
define("VAR_HREF_PATH_ADMIN", "/admin/");
define("VAR_HREF_PAGE_ADMIN", "/admin.php");
define("VAR_HREF_ACTION_SPECIALE", "/actionSpeciale");
define("VAR_HREF_LIEN_MENU", "lien");

define("VAR_IMAGE_PORTRAITS_PATH", "pictures/portraits/"); //server name added later as not compatible with is_file()
define("FICHIER_GRAPHE_STAT_LANGUE", "/temp/graphe_langue.gif");
define("VAR_IMAGE_PHOTOS_EQUIPES_PATH", "/pictures/equipesSuisses/photosEquipes/");
define("VAR_IMAGE_LOGO_CLUBS", "/pictures/logos_clubs/");
define("VAR_IMAGE_VIDEOS_PATH", "/pictures/videos/");
define("VAR_IMAGE_LANGUE", "/pictures/langues/");

define("VAR_HREF_VIDEOS_PATH", "/Videos/videos/");


if (stristr($_SERVER["REDIRECT_SCRIPT_URI"], 'admin')!==false) {
    define("VAR_HREF_PAGE_MERE", VAR_HREF_PAGE_ADMIN);
} else {
    define("VAR_HREF_PAGE_MERE", VAR_HREF_PAGE_PRINCIPALE);
}
