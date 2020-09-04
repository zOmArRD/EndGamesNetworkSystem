<?php
/**
 * Created by PhpStorm.
 * User: zOmArRD
 * Date: 2020-08-21
 *       ___               _         ____  ____
 *  ____/ _ \ _ __ ___    / \   _ __|  _ \|  _ \
 * |_  / | | | '_ ` _ \  / _ \ | '__| |_) | | | |
 *  / /| |_| | | | | | |/ ___ \| |  |  _ <| |_| |
 * /___|\___/|_| |_| |_/_/   \_\_|  |_| \_\____/
 *
 */
declare(strict_types=1);

namespace system\bossbar;

/** System */
use system\Main;

/** Pocketmine */
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;
use pocketmine\Server;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\Task;

/** Bossbar */
use xenialdan\apibossbar\BossBar;

class EnderBoss implements Listener{

    public static $bar;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function sendBossbar(PlayerJoinEvent $ev)
    {
        self::$bar = (new BossBar())->setPercentage(1);
        $this->plugin->getScheduler()->scheduleRepeatingTask(new class extends Task
        {
            public function onRun(int $currentTick)
            {
                foreach (Server::getInstance()->getDefaultLevel()->getPlayers() as $player) {
                    EnderBoss::$bar->setTitle("§c§lEndGames §fNetwork");
                }
            }
        }, 20);

        self::$bar->addPlayer($ev->getPlayer());
    }
}
