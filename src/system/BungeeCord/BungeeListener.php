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

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;
use system\BungeeCord\protocol\Request;
use system\BungeeCord\protocol\RequestPool;
use system\BungeeCord\protocol\StringStream;

class BungeeListener implements Listener
{
    public function onPacketReceive(DataPacketReceiveEvent $event)
    {
        $packet = $event->getPacket();
        if ($packet instanceof ScriptCustomEventPacket) {
            if ($packet->eventName == "bungeecord:main") {
                $request = RequestPool::getRequestForPlayer($event->getPlayer()->getName());
                if ($request instanceof Request) {
                    $request->notify($packet->eventData);
                }
            }
        }
    }
}