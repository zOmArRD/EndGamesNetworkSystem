<?php
declare(strict_types=1);

namespace system\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use system\Main;

class antispam implements Listener{

    public $ips = [".es", ".net", ".ddns", ".eu", ".us", ".club", ".sytes", ".cf", ".tk", ".ml", ".pro", ".com", ".1", ".2", ".3", ".4", ".5", ".6", ".7", ".8", ".9", ".10", ",net", ",pro", ",com", ",ml", ",tk", ",cf", "cubecraft", "versai"];
    public $allowedips = ["play.endgames.cf", "shop.endgames.cf", "endgames.cf"];

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function antiSpam(PlayerChatEvent $ev)
    {
        $p = $ev->getPlayer();
        $pn = $p->getName();
        $msg = $ev->getMessage();
        $pref = $this->plugin->getPrefix();
        foreach ($this->ips as $ips) {
            if (strpos($msg, $ips)) {
                if ($p->hasPermission("endgames.staff")) {
                } else {
                    $p->sendMessage($pref.  "§cWe have seen that you tried to pass ip from another server, the staff will take serious measures if this happens again");
                    $ev->setCancelled(true);
                }
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    if ($player->hasPermission("endgames.staff")) {
                        $player->sendMessage($pref. "§cAttention, the player §6$pn has tried to pass an IP\n§bMessage: §c$msg");
                    }
                }
                foreach ($this->allowedips as $allow) {
                    if (strpos($msg, $allow)) {
                        $ev->setCancelled(false);
                    }

                }
            }
        }

    }
}