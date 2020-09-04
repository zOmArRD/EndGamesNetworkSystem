<?php
namespace system\task;

use pocketmine\scheduler\Task;
use system\manager\servers\ServerManager;

class ServerSyncTask extends Task {

    /**
     * @inheritDoc
     */
    public function onRun(int $currentTick)
    {
        foreach(ServerManager::getServers() as $server){
            $server->sync();
        }
    }
}