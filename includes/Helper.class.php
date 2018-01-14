<?php
/**
 * Helper class
 *
 */

class Helper
{
    public static function isValidId($id) {
        return preg_match("#^\d+$#", $id) && $id != 0;
    }
}
