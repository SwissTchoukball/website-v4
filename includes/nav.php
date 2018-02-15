<nav id="main-nav">
    <?php
    try {
        $menu = $navigation->getMenu();
    } catch (Exception $exception) {
        printErrorMessage('Erreur lors du chargement du menu.<br>' + $exception->getMessage());
        die();
    }

    $currentPage = $navigation->getCurrentMenuItem();

        $previousNavParentItemID = 0;
        foreach($menu as $menuItem) {
            $navParentItemID = $menuItem['sousMenuDeId'];
            $navItemOrder = $menuItem['ordre'];
            $navParentItemName = $menuItem['menu'];
            $navItemName = $menuItem['sousmenu'];
            $navParentUserLevel = $menuItem['parentUserLevel'];
            $navUserLevel = $menuItem['userLevel'];
            if ($menuItem['link'] != '') {
                $navLink = $menuItem['link'];
            } else {
                $srcFile = $admin ? 'admin.php' : 'index.php';
                $navLink = $srcFile . '?lien=' . $menuItem['id'];
            }
            if (!$menuItem['isExternalLink']) {
                $navLink = '/' . $navLink;
            }
            $navParentLink = $menuItem['parentLink'];


            if ($navParentUserLevel >= $_SESSION['__userLevel__']) {
                if ($navParentItemID != $previousNavParentItemID) {
                    if ($previousNavParentItemID != 0) {
                        echo '</ul>';
                    }

                    echo $currentPage['parentId'] == $navParentItemID ? '<h1 class="open">' : '<h1>';

                    if (is_null($navItemOrder) && $navParentLink != '') {
                        echo '<a href="/' . $navParentLink . '">' . $navParentItemName . '</a>';
                    } else {
                        echo $navParentItemName;
                    }

                    echo '</h1>';
                    echo $currentPage['parentId'] == $navParentItemID ? '<ul style="display: block;">' : '<ul>';

                }

                if ($navUserLevel >= $_SESSION['__userLevel__']) {
                    $navItemClass = ($currentPage['parentId'] == $navParentItemID && $currentPage['menuOrder'] == $navItemOrder) ? 'open' : '';
                    echo '<li><a href="' . $navLink . '" class="' . $navItemClass . '">' . $navItemName . '</a></li>';
                }
            }

            $previousNavParentItemID = $navParentItemID;
        }
    ?>
</nav>
