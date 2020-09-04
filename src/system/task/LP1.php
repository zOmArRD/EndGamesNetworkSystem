<?php

namespace system\task;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use system\Main;
use system\BungeeCord\BugeeCord;

class LP1 extends Task
{

    private $time = 1;

    public function __construct(Main $plugin, Player $player)
    {
        $this->plugin = $plugin;
        $this->pl = $player;
    }

    public function onRun(int $currentTick)
    {
        $player = $this->pl;
        if ($this->time == 1){
            $player->removeAllEffects();
            $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 999999999, 1, false));
            $player->addEffect(new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 999999999, 1, false));
        }
        if ($this->time == 0){
            $server = "p1";
            BugeeCord::transferir($player, $server);
            if ($player->isOnline()) {
                $player->removeAllEffects();
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 999999999, 1, false));
            }
        }
        $this->time--;
    }
}