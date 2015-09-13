<?php
if ($_SESSION["debug_tracage"]) {
    echo __FILE__."<BR>";
}

//"appel de /includes/var.de.incl.php <BR>";

function controler_acces()
{
    if (!isset($_SESSION["admin"])) {
        session_destroy();
        echo "ERREUR";
        exit();
    }
}
