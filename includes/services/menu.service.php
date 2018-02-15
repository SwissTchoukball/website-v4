<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/includes/DB.class.php';

class MenuService
{
    public static function getMenu($tableName) {
        $query = "SELECT sm.id, m.nom{$_SESSION['__langue__']} AS menu,
            sm.nom{$_SESSION['__langue__']} AS sousmenu, m.id AS sousMenuDeId,
            sm.ordre, m.userLevel AS parentUserLevel, sm.userLevel,
            sm.urlRewriting AS link, m.urlRewriting AS parentLink, sm.lienExterneSite AS isExternalLink
            FROM {$tableName} m
            LEFT OUTER JOIN {$tableName} sm ON sm.sousMenuDeId = m.id
            WHERE m.sousMenuDeId = -1
            ORDER BY m.ordre, sm.ordre";

        $db = new DB();

        return $db->query($query);
    }

    public static function getMenuItemById($tableName, $menuItemId) {
        $db = new DB();

        $db->bind('menuItemId', $menuItemId);

        $attributes = MenuService::getMenuAttributesForQuery();

        $query = "SELECT {$attributes}
        FROM {$tableName}
        WHERE id = :menuItemId
        LIMIT 1";

        return $db->query($query)[0];
    }

    public static function getMenuItemByParentIdAndOrder($tableName, $menuItemParentId, $menuItemOrder) {
        $db = new DB();

        $db->bind('menuItemParentId', $menuItemParentId);
        $db->bind('menuItemOrder', $menuItemOrder);

        $attributes = MenuService::getMenuAttributesForQuery();

        $query = "SELECT {$attributes}
        FROM {$tableName}
        WHERE sousMenuDeId = :menuItemParentId AND ordre = :menuItemOrder
        LIMIT 1";

        return $db->query($query)[0];
    }

    private static function getMenuAttributesForQuery() {
        return "id, nom{$_SESSION['__langue__']} AS title, sousMenuDeId AS parentId, ordre AS menuOrder, userLevel, lien AS filePath,
        urlRewriting AS urlPath, lienExterneSite AS isExternal, showTitle";
    }
}