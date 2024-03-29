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

use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Binary;

class BugeeCord
{
    public static function transferPlayer(Player $player, String $server): bool
    {
        $pk = new ScriptCustomEventPacket();
        $pk->eventName = "bungeecord:main";
        $pk->eventData = "";
        ProtocolUtils::writeString("Connect", $pk->eventData);
        ProtocolUtils::writeString($player->getName(), $pk->eventData);
        ProtocolUtils::writeString($server, $pk->eventData);
        $player->sendDataPacket($pk);
        return true;
    }

    public static function getPlayers()
    {
        if (($sendthrough = static::getRandomPlayer()) != null) {
            $pk = new ScriptCustomEventPacket();
            $pk->eventName = "bungeecord:main";
            $pk->eventData = "";

            ProtocolUtils::writeString("PlayerList", $pk->eventData);
            ProtocolUtils::writeString("ALL", $pk->eventData);
            $sendthrough->sendDataPacket($pk);
        }
    }

    public static function transfer(String $player, String $target): bool
    {
        if (($sendthrough = static::getRandomPlayer()) != null) {
            $packet = new ScriptCustomEventPacket();
            $packet->eventName = "bungeecord:main";
            $packet->eventData = "";
            ProtocolUtils::writeString("ConnectOther", $packet->eventData);
            ProtocolUtils::writeString($player, $packet->eventData);
            ProtocolUtils::writeString($target, $packet->eventData);
            $sendthrough->sendDataPacket($packet);
            return true;
        } else {
            Server::getInstance()->getLogger()->warning("Cannot execute API::transfer(): No Player online for abusing");
            return false;
        }
    }

    public static function transferir(Player $player, String $server): bool
    {

        $pk = new ScriptCustomEventPacket();
        $pk->eventName = "bungeecord:main";
        $pk->eventData = Binary::writeShort(strlen("Connect")) . "Connect" . Binary::writeShort(strlen($server)) . $server;
        $player->sendDataPacket($pk);
        return true;
    }

    public static function kickPlayer(String $player, String $message): bool
    {
        if (($sendthrough = static::getRandomPlayer()) != null) {
            $packet = new ScriptCustomEventPacket();
            $packet->eventName = "bungeecord:main";
            $packet->eventData = "";
            ProtocolUtils::writeString("KickPlayer", $packet->eventData);
            ProtocolUtils::writeString($player, $packet->eventData);
            ProtocolUtils::writeString($message, $packet->eventData);
            $sendthrough->sendDataPacket($packet);
            return true;
        } else {
            return false;
        }
    }

    public static function sendMessage(String $message, String $player): bool
    {
        if (($sendthrough = static::getRandomPlayer()) != null) {
            $packet = new ScriptCustomEventPacket();
            $packet->eventName = "bungeecord:main";
            $packet->eventData = "";
            ProtocolUtils::writeString("Message", $packet->eventData);
            ProtocolUtils::writeString($player, $packet->eventData);
            ProtocolUtils::writeString($message, $packet->eventData);
            $sendthrough->sendDataPacket($packet);
            return true;
        } else {
            Server::getInstance()->getLogger()->warning("Cannot execute API::sendMessage(): No Player online for abusing");
            return false;
        }
    }

    public static function alignMessage(array $lines): ?string
    {
        $stickWith = max(array_map('strlen', $lines));
        $output = "";
        foreach ($lines as $line) {
            $diff = round(($stickWith - strlen($line)) / 2);

            if ($diff !== 0) $line = str_repeat(" ", $diff) . $line;

            $output .= "{$line}\n";
        }

        return $output;
    }

    public static function getRandomPlayer(): ?Player
    {
        if (count(Server::getInstance()->getOnlinePlayers()) > 0) {
            return Server::getInstance()->getOnlinePlayers()[array_rand(Server::getInstance()->getOnlinePlayers())];
        } else {
            return null;
        }
    }
}