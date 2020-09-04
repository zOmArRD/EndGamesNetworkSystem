<?php
declare(strict_types=1);

namespace system\events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use system\Main;
use system\manager\bedwars\BedWarsEntity;
use system\manager\practice\PracticeEntity;
use system\manager\skywars\SkyWarsEntity;
use system\task\LP1;
use system\task\LP2;
use system\utils\FormAPI\SimpleForm;
use libpmquery\PMQuery;
use libpmquery\PmQueryException;

class entitymanager implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onHitEntityForTransfer(EntityDamageByEntityEvent $ev){
        if ($ev->getEntity() instanceof PracticeEntity){
            $player = $ev->getDamager();
            if($player instanceof Player){
                $ev->setCancelled(true);
                $this->PracticeLobbySelector($player);
            }
        }
        if ($ev->getEntity() instanceof SkyWarsEntity){
            $player = $ev->getDamager();
            if ($player instanceof Player){
                $ev->setCancelled(true);
                $this->SkywarsLobbySelector($player);
            }
        }
        if ($ev->getEntity() instanceof BedWarsEntity){
            $player = $ev->getDamager();
            if ($player instanceof Player){
                $ev->setCancelled(true);
                $player->sendMessage("§l§a» §fThis server are in maintenance...");
            }
        }
    }

    public function onInteractEntity(PlayerInteractEvent $ev){
        if ($ev->getPlayer() instanceof PracticeEntity){
            $player = $ev->getPlayer();
            $this->PracticeLobbySelector($player);
        }
    }

    /** Practice Form For Lobbys */
    public function PracticeLobbySelector(Player $player){
        try {
            $lobby1 = PMQuery::query("207.244.228.121", 19122);
            $lobby2 = PMQuery::query("207.244.228.121", 19123);
            $lp1 = (int)$lobby1['Players']."§7/§a60";
            $lp2 = (int)$lobby2['Players']."§7/§a60";
        } catch (PmQueryException $e){
            $lp1 = "§cOFFLINE";
            $lp2 = "§cOFFLINE";
        }
        $form = new SimpleForm(function (Player $player, ?int $data){
            if (!is_null($data)){
                $prefix = "§l§a»  §fConnecting to the server...";
                switch ($data){

                    case 0:
                        $player->sendMessage($prefix);
                        $this->plugin->getScheduler()->scheduleRepeatingTask(new LP1($this->plugin, $player), 20);
                        break;
                    case 1:
                        $player->sendMessage($prefix);
                        $this->plugin->getScheduler()->scheduleRepeatingTask(new LP2($this->plugin, $player), 20);
                        break;
                    default:
                        return;
                }
            }
        });
        $form->setTitle("§l§cEndGames §7| §fPractice");
        $images = [
            "return" => "textures/ui/refresh_light",
            "exit" => "textures/ui/crossout",
            "lobby" => "textures/gui/newgui/mob_effects/strength_effect"
        ];
        $form->addButton("§l§6LOBBY 1\n§r§7Players Connected: §a".$lp1, 0, $images["lobby"]);
        $form->addButton("§l§6LOBBY 2\n§r§7Players Connected: §a".$lp2, 0, $images["lobby"]);
        $form->addButton("§cClose", 0, $images["exit"]);

        $player->sendForm($form);

    }

    /** Skywars Form For Lobbys */
    public function SkywarsLobbySelector(Player $player){
        try {
            $sw1 = PMQuery::query("sw1", 19135);
            $sw2 = PMQuery::query("sw2", 19135);
            $sw3 = PMQuery::query("sw2", 19135);
            $plsw1 = (int)$sw1['Players']."§7/§a50";
            $plsw2 = (int)$sw2['Players']."§7/§a50";
            $plsw3 = (int)$sw3['Players']."§7/§a50";
        } catch (PmQueryException $e){
            $plsw1 = "§cOFFLINE";
            $plsw2 = "§cOFFLINE";
            $plsw3 = "§cOFFLINE";
        }
        $form = new SimpleForm(function (Player $player, ?int $data){
            if (!is_null($data)){
                $prefix = "§l§a»  §fCould not connect to the server...";
                switch ($data){

                    case 0:
                        $player->sendMessage($prefix);
                        //$this->plugin->getScheduler()->scheduleRepeatingTask(new LP1($this->plugin, $player), 20);
                        break;
                    case 1:
                        $player->sendMessage($prefix);
                        break;
                    case 2:
                        $player->sendMessage($prefix);
                        break;
                    default:
                        return;
                }
            }
        });
        $form->setTitle("§l§cEndGames §7| §6SkyWars");
        $images = [
            "return" => "textures/ui/refresh_light",
            "sw" => "textures/items/bow_pulling_0",
            "exit" => "textures/ui/crossout"
        ];
        $form->addButton("§l§6LOBBY 1\n§r§7Players Connected: §a".$plsw1, 0, $images["sw"]);
        $form->addButton("§l§6LOBBY 2\n§r§7Players Connected: §a".$plsw2, 0, $images["sw"]);
        $form->addButton("§l§6LOBBY 3\n§r§7Players Connected: §a".$plsw3, 0, $images["sw"]);
        $form->addButton("§cClose", 0, $images["exit"]);

        $player->sendForm($form);

    }
}