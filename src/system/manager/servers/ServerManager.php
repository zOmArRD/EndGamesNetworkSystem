<?php
namespace system\manager\servers;
use EndGames\EndGamesDatabase\AsyncQueue;
use EndGames\EndGamesDatabase\Database;
use EndGames\EndGamesDatabase\SelectQuery;
use system\Main;
use system\task\ServerSyncTask;

/**
 * Class ServerManager
 * @package system\manager\servers
 */
class ServerManager {

    /** @var Server[] $servers */
    private static $servers = [];

    /** @var ServerGroup[] $serverGroups */
    private static $serverGroups = [];

    /** @var Server $currentServer */
    private static $currentServer = null;

    /**
     * @param Main $plugin
     */
    public static function init(Main $plugin, array $groups){
        $plugin->getScheduler()->scheduleRepeatingTask(new ServerSyncTask(), 60);
        foreach ($groups as $group){
            self::$serverGroups[] = new ServerGroup($group, []);
        }

        AsyncQueue::submitQuery(new SelectQuery("SELECT * FROM servers;"), function ($rows){
            foreach ($rows as $row) {
                $server = new Server($row["server"], $row["players"], $row["status"] === 1);
                if($row["server"] === Database::getInstance()->getCurrentServerName()){
                    self::$currentServer = $server;
                } else {
                    self::$servers[] = $server;
                }
                foreach (self::$serverGroups as $serverGroup){
                    $serverGroup->addServer($server);
                }
            }
        });
    }

    public static function getTotalPlayers() : int {
        $int = count(\pocketmine\Server::getInstance()->getOnlinePlayers());
        foreach(self::$servers as $server){
            $int = $int + $server->getOnlinePlayers();
        }
        return $int;
    }

    public static function getServers() : array{
        return self::$servers;
    }

    public static function getServergroups() : array {
        return self::$serverGroups;
    }
}