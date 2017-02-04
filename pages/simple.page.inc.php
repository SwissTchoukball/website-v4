<?php
if (isset($_GET['lien']) && is_numeric($_GET['lien'])) {
    $menuId = $_GET['lien'];
    $query = "SELECT p.body" . $_SESSION['__langue__'] . " AS body
        FROM IdTextCorpPage p
        WHERE p.menuId = $menuId";
    $resource = mysql_query($query);

    $pageData = mysql_fetch_assoc($resource);

    $body = Markdown($pageData['body']);

    echo '<div>' . $body . '</div>';

} else {
    // TODO: redirect to a real 404 page
    echo '<p>Page introuvable</p>';
}
