<?php
namespace system\manager\servers;
use pocketmine\Player;

class ServerGroup {
    /** @var Server[] $servers */
    private $servers = [];

    /** @var string $name */
    private $name = "";

    /**
     * ServerGroup constructor.
     * @param $name
     * @param $servers
     */
    public function __construct($name, $servers)
    {
        $this->name = $name;
    }

    public function getServers() : array {
        return $this->servers;
    }

    public function addServer(Server $server){
        if(strpos($server->getName(), $this->getName()) !== false){
            $this->servers[] = $server;
        }
    }

    public function findOptimalServer(/*Player $player*/){
        $servers = $this->getServers();
        $sort = array_map(function (Server $server) {return $server->getOnlinePlayers();}, $servers);
        asort($sort);
        $finalServer = null;
        foreach ($sort as $key) {
            $server = $servers[$key];
            // TODO: Actually make it join the most optimal server.
        }
        return $finalServer;
    }

    public function getName() :string{
        return $this->name;
    }
}