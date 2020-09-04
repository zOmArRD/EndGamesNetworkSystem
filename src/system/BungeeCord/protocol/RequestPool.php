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
namespace system\BungeeCord\protocol;

use pocketmine\Player;
use pocketmine\Server;

abstract class RequestPool
{
    /** @var Request[] */
    public static $requests = [];

    public static function addRequest(Player $throughpass, Request $request): void
    {
        self::$requests[$throughpass->getName()] = $request;
    }

    public static function removeRequest(String $name): void
    {
        unset(self::$requests[$name]);
    }

    public static function hasRequestOpen(String $name): bool
    {
        return isset(self::$requests[$name]);
    }

    public static function getFreePlayerForRequest(): ?Player
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $p) {
            if (!(self::hasRequestOpen($p->getName()))) return $p;
        }
        return null;
    }
    public static function getRequestForPlayer(String $name): ?Request{
        if(isset(self::$requests[$name])) return self::$requests[$name]; else return null;
    }
}