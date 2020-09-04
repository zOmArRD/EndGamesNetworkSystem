<?php
declare(strict_types=1);

namespace system\task;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use system\Main;

use libpmquery\PMQuery;
use libpmquery\PmQueryException;

class NpcUpdatedTask extends AsyncTask{
    private $practice, $skywars, $bedwars;

    public function __construct()
    {
    }

    public function onRun()
    {
        $this->npcPractice();
        $this->npcBedWars();
        $this->npcSkyWars();
    }

    public function onCompletion(Server $server)
    {
        Main::applyNames("practice", $this->practice);
        Main::applyNames("skywars", $this->skywars);
        Main::applyNames("bedwars", $this->bedwars);
    }

    private function npcPractice()
    {
        try {
            $lobby1 = PMQuery::query("localhost", 19122);
            $lobby2 = PMQuery::query("localhost", 19123);
            $pl1 = (int)$lobby1['Players'];
            $pl2 = (int)$lobby2['Players'];
            $players = $pl1 + $pl2." §aPLAYERS";
        } catch (PmQueryException $e){
            $players = "§aUPDATING";
        }

        $title = $players;
        $this->practice = $title;
    }

    private function npcSkyWars()
    {
        try{
            $practice1 = PMQuery::query("localhost", 19135);
            $players = "§a".(int) $practice1['Players']." PLAYERS";
        }catch(PmQueryException $e){
            $players = "§cOFFLINE";
        }

        $title = $players;
        $this->skywars = $title;
    }

    private function npcBedWars()
    {
        try{
            $practice1 = PMQuery::query("localhost", 19000);
            $players = "§a".(int) $practice1['Players']." PLAYERS";
        }catch(PmQueryException $e){
            $players = "§cDEVELOPING";
        }

        $title = $players;
        $this->bedwars = $title;
    }

}
