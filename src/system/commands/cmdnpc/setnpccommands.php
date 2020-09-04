<?php
namespace system\commands\cmdnpc;

use pocketmine\Server;
use system\manager\bedwars\BedWarsEntityManager;
use system\manager\practice\PracticeEntity;
use system\manager\practice\PracticeEntityManager;
use system\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use system\manager\skywars\SkyWarsEntityManager;

class setnpccommands extends Command
{
    private $plugin;
    protected $description;
    protected $usageMessage;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("setentity");
        $this->description = "Set NPC entity.";
        $this->usageMessage = "/setentity help";
        $this->setPermission("setentity.cmd");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $helptext = [
            1 =>
                "§7[1] Commands (1-4):
        - §e/setentity help [1-3] - Shows this.§7
        - §e/setentity practice npc - Add NPC for Practice Server.§7
        - §e/setentity skywars npc - Add NPC for SkyWars Server.§7
        - §e/setentity meetup  npc - Add NPC for UHC MEETUP Server.§7",
            2 => "§7 [2] Commands (6-10):
        - §e/setentity NULL - NULL.§7
        - §e/setentity NULL - NULL.§7
        - §e/setentity NULL - NULL.§7
        - §e/setentity NULL - NULL.§7
       - §e/setentity NULL - NULL.§7"
        ];
        if (!isset($args[0])) {
            $player->sendMessage($helptext[1]);
            return true;
        }
        switch ($args[0]) {
            case "help":
                $num = 1;
                if (isset($args[1])) {
                    $num = intval($args[1]);
                }
                if (isset($helptext[$num])) {
                    $player->sendMessage($helptext[$num]);
                } else {
                    $player->sendMessage($helptext[1]);
                }
                return true;
                break;

            /** Entity Practice */
            case "practice":
                if( !isset($args[1]) ){
                    $player->sendMessage("§c/setentity practice <npc>");
                    return false;
                }
                if( strtolower($args[1]) == "npc" ){
                    if($player->hasPermission("setentity.cmd")){
                        $player->sendMessage("§l§a» §r§7You have put the npc correctly.");
                        $arenanpc = new PracticeEntityManager();
                        $arenanpc->setPracticeNPC($player);
                        return true;
                    } else {
                        $player->sendMessage('§l§a» §r§7To use this command you need to have permissions');
                        return true;
                    }
                }
                break;

            /** Entity Skywars */
            case "skywars":
                if( !isset($args[1]) ){
                    $player->sendMessage("§c/setentity skywars <npc>");
                    return false;
                }
                if( strtolower($args[1]) == "npc" ){
                    if($player->hasPermission("owner.endgames")){
                        $player->sendMessage('§l§a» §r§7You have put the npc correctly.');
                        $creative = new SkyWarsEntityManager();
                        $creative->setSkyWarsNPC($player);
                        return true;
                    } else {
                        $player->sendMessage('§l§a» §r§7To use this command you need to have permissions');
                        return true;
                    }
                }
                break;

            /** Entity BedWars */
            case "bedwars":
                if( !isset($args[1]) ){
                    $player->sendMessage("§c/setentity bedwars <npc>");
                    return false;
                }
                if( strtolower($args[1]) == "npc" ){
                    if($player->hasPermission("owner.endgames")){
                        $player->sendMessage('§l§a» §r§7You have put the npc correctly.');
                        $creative = new BedWarsEntityManager();
                        $creative->setBedWarsNPC($player);
                        return true;
                    } else {
                        $player->sendMessage('§l§a» §r§7To use this command you need to have permissions');
                        return true;
                    }
                }
                break;

            default:
                $player->sendMessage("§l§a» §r§7This subcommand does not exist or is under development. Use the command /setentity help to see a list of my commands.");
                break;
        }
        return true;
    }
}
