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
namespace system\BungeeCord\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use system\Main;
use system\BungeeCord\protocol\BufferFactory;
use system\BungeeCord\protocol\Request;
use system\BungeeCord\protocol\RequestPool;
use system\BungeeCord\protocol\RequestType;

class PingCommand extends Command
{
    public function __construct(String $description, String $usage, String $noperm, String $perm)
    {
        parent::__construct("ping", $description, $usage);
        $this->setPermissionMessage($noperm);
        $this->setPermission($perm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $pk = BufferFactory::constructPacket(["player" => $sender->getName()], RequestType::TYPE_GET_PING);
            RequestPool::addRequest($sender, new Request($pk->buffer, $sender->getName(), RequestType::TYPE_GET_PING, function (array $data, array $extra) {
                if (($player = Server::getInstance()->getPlayerExact($extra["player"])) != null) {
                    $player->sendMessage("§l§a» §r§7Your Ping to the proxy is currently at §c" . $data["ping"] . "ms§7!");
                }
            }, ["player" => $sender->getName()]));
            $sender->dataPacket($pk);
        }


    }
}