<?php

declare(strict_types=1);

namespace system\Emote;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\EmotePacket;
use pocketmine\Server;

class EmoteWork implements Listener
{
    public function onData(DataPacketReceiveEvent $event)
    {
        $packet = $event->getPacket();
        if (!$packet instanceof EmotePacket) return;
        $emoteId= $packet->getEmoteId();
        Server::getInstance()->broadcastPacket($event->getPlayer()->getViewers(), EmotePacket::create($event->getPlayer()->getId(), $emoteId, 1 << 0));
    }
}
