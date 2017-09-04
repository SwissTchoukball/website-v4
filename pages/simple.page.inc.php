<?php
if (isset($_GET['lien']) && is_numeric($_GET['lien'])) {
    echo '<div>' . getSimplePageContent($_GET['lien']) . '</div>';
} else {
    // TODO: redirect to a real 404 page
    echo '<p>Page introuvable</p>';
}
