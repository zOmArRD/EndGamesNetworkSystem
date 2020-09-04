<?php
/**
 * Created by PhpStorm.
 * @author zOmArRD
 *       ___               _         ____  ____
 *  ____/ _ \ _ __ ___    / \   _ __|  _ \|  _ \
 * |_  / | | | '_ ` _ \  / _ \ | '__| |_) | | | |
 *  / /| |_| | | | | | |/ ___ \| |  |  _ <| |_| |
 * /___|\___/|_| |_| |_/_/   \_\_|  |_| \_\____/
 *
 */
namespace system\BungeeCord;

use pocketmine\utils\Binary;

class ProtocolUtils
{
    public static function writeString(String $str, String &$datastream)
    {
        $datastream .= Binary::writeShort(strlen($str)) . $str;
    }
}