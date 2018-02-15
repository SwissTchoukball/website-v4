<?php

require_once 'services/menu.service.php';

class Navigation
{
    private $menuTypes = [
        "main" => [
            "table" => "Menu",
            "homeId" => 56
        ],
        "admin" => [
            "table" => "MenuAdmin",
            "homeId" => 3
        ]
    ];

    /**
     * @var string
     */
    private $menuTableName;

    /**
     * @var int
     */
    private $homeMenuItemId;

    private $currentMenuItem;

    public function __construct($menuType)
    {
        $this->menuTableName = $this->menuTypes[$menuType]["table"];
        $this->homeMenuItemId = $this->menuTypes[$menuType]["homeId"];
        $this->setCurrentMenuItemById($this->homeMenuItemId);
    }

    /**
     * @param int $menuItemId
     */
    public function setCurrentMenuItemById($menuItemId)
    {
        if (is_numeric($menuItemId)) {
            $this->currentMenuItem = MenuService::getMenuItemById($this->menuTableName, $menuItemId);

            // If the user has the correct permission we're done.
            if ($_SESSION["__userLevel__"] <= $this->currentMenuItem['userLevel']) {
                return;
            }
        }

        // Fallback home if invalid ID or not the right permission
        $this->currentMenuItem = MenuService::getMenuItemById($this->menuTableName, $this->homeMenuItemId);
    }

    /**
     * @param int $menuItemParentId
     * @param int $menuItemOrder
     */
    public function setCurrentMenuItemByParentIdAndOrder($menuItemParentId, $menuItemOrder)
    {
        if (is_numeric($menuItemParentId) && is_numeric($menuItemOrder)) {
            $this->currentMenuItem = MenuService::getMenuItemByParentIdAndOrder(
                $this->menuTableName,
                $menuItemParentId,
                $menuItemOrder
            );

            // If the user has the correct permission we're done.
            if ($_SESSION["__userLevel__"] <= $this->currentMenuItem['userLevel']) {
                return;
            }
        }

        // Fallback home if invalid ID or not the right permission
        $this->currentMenuItem = MenuService::getMenuItemById($this->menuTableName, $this->homeMenuItemId);
    }

    /**
     * @return mixed
     */
    public function getCurrentMenuItem()
    {
        return $this->currentMenuItem;
    }

    public function getMenu() {
        return MenuService::getMenu($this->menuTableName);
    }

    public function getCurrentPageLinkQueryString() {
        return VAR_HREF_LIEN_MENU . '=' . $this->currentMenuItem['id'];
    }
}
