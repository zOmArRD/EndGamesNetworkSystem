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

namespace system\task;
use system\Main;

use pocketmine\Server;
use pocketmine\scheduler\Task;

class MainHud extends Task
{
    private $plugin;
    private $time = 0;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        $this->time++;
        $world = Server::getInstance()->getLevelByName("world");
        foreach ($world->getPlayers() as $player)
        {
            $this->plugin->createScoreboard($player, 0);
        }

    }


}