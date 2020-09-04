<?php
namespace system\task;

use system\Main;
use pocketmine\scheduler\Task;
use pocketmine\updater\UpdateCheckTask;

class MakeAsyncTasks extends Task {
    public $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @inheritDoc
     */
    public function onRun(int $currentTick)
    {
        $this->plugin->getServer()->getAsyncPool()->submitTask(new NpcUpdatedTask(), 100);
    }
}