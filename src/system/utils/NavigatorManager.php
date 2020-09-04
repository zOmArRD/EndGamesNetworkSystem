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

namespace system\utils;

use system\BungeeCord\protocol\BufferFactory;
use system\BungeeCord\protocol\Request;
use system\BungeeCord\protocol\RequestPool;
use system\BungeeCord\protocol\RequestType;
use system\BungeeCord\BugeeCord;
use system\task\LP1;
use system\task\LP2;
use system\utils\FormAPI\SimpleForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TE;
use system\Main;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\utils\MainLogger;

use libpmquery\PMQuery;
use libpmquery\PmQueryException;

class NavigatorManager implements Listener
{

    public const EXEPCION = "§l§a»  §fCould not connect to the server...";
    /**
     * mainevents constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }


    public function playerInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $pi = $player->getInventory();
        $id = $event->getItem()->getId();
        $custom = $event->getItem()->getCustomName();

        if ($id == 345 && $custom == "§7§l» §aNavigator §7«") {
            self::navigatorForm($player);
            //$player->sendMessage("Test");
        }
        if ($id == 450 && $custom == "§7§l» §aFriends §7«"){
            $player->sendMessage("§l§a» §fThis feature is under development...");
        }
        if ($id == 340 && $custom == "§7§l» §aSettings §7«"){
            $player->sendMessage("§l§a» §fThis feature is under development...");
        }
        if ($id == 399 && $custom == "§7§l» §aCosmetics §7«"){
            $player->sendMessage("§l§a» §fThis feature is under development...");
        }

    }

    public function navigatorForm(Player $player){
        $form = new SimpleForm(function (Player $player, ?int $data) {
            if( !is_null($data)) {
                switch ($data) {
                    case 0:
                        self::PracticeLobbySelector($player);
                        break;
                    case 1:
                        self::SkywarsLobbySelector($player);
                        break;
                    case 2:
                        $player->sendMessage("§l§a» §fThis server are in maintenance...");
                        break;
                    case 3:
                        $player->sendMessage("§l§a» §fThis server are in maintenance...");
                        break;
                    default:
                        return;
                }
            }
        });
        $form->setTitle("§l§7» §cEndGames §fNetwork §7«");
        $form->setContent("§eSelect which server you want to transfer to");
        $images = [
            "ServerPractice" => "textures/gui/newgui/mob_effects/strength_effect",
            "new" => "textures/ui/icon_new_item",
            "bw" => "textures/items/bed_red",
            "sw" => "textures/items/bow_pulling_0",
            "meetup" => "textures/ui/WorldDemoScreen",
            "exit" => "textures/ui/crossout",
        ];
        try {
            $practice1 = PMQuery::query("207.244.228.121", 19122);
            $practice2 = PMQuery::query("207.244.228.121", 19123);
            $pl1 = (int)$practice1['Players'];
            $pl2 = (int)$practice2['Players'];
            $practiceplayers = $pl1 + $pl2;
        } catch (PmQueryException $e){
            $practiceplayers = "§aUPDATING";
        }
        $form->addButton("§l§cPractice\n". "§r§7Players Connected: §a".$practiceplayers, 0, $images["ServerPractice"]);
        $form->addButton("§l§6SkyWars\n"."§r§cIN A FEW DAYS", 0, $images["sw"]);
        $form->addButton("§cBedWars\n"."§r§7Players Connected: §cSOON", 0, $images["bw"]);
        $form->addButton("§l§6UHC MEETUP\n". "§r§7Players Connected: §cSOON", 0, $images["meetup"]);
        $form->addButton("§cClose", 0, $images["exit"]);
        //$form->addButton("", 0, $images[""]);
        $player->sendForm($form);
    }

    /** Practice Form For Lobbys */
    public function PracticeLobbySelector(Player $player){
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
                    case 2:
                        self::navigatorForm($player);
                        break;
                    default:
                        return;
                }
            }
        });
        $form->setTitle("§l§cEndGames §7| §fPractice");
        $images = [
            "return" => "textures/ui/refresh_light",
            "lobby" => "textures/gui/newgui/mob_effects/strength_effect"
        ];
        try {
            $lobby1 = PMQuery::query("207.244.228.121", 19122);
            $lobby2 = PMQuery::query("207.244.228.121", 19123);
            $lp1 = (int)$lobby1['Players']."§7/§a60";
            $lp2 = (int)$lobby2['Players']."§7/§a60";
        } catch (PmQueryException $e){
            $lp1 = "§cOFFLINE";
            $lp2 = "§cOFFLINE";
        }
        $form->addButton("§l§6LOBBY 1\n§r§7Players Connected: §a".$lp1, 0, $images["lobby"]);
        $form->addButton("§l§6LOBBY 2\n§r§7Players Connected: §a".$lp2, 0, $images["lobby"]);
        $form->addButton("§cReturn", 0, $images["return"]);

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
                switch ($data){

                    case 0:
                        $player->sendMessage(self::EXEPCION);
                        //$this->plugin->getScheduler()->scheduleRepeatingTask(new LP1($this->plugin, $player), 20);
                        break;
                    case 1:
                        $player->sendMessage(self::EXEPCION);
                        break;
                    case 2:
                        $player->sendMessage(self::EXEPCION);
                        break;
                    case 3:
                        self::navigatorForm($player);
                        break;
                    default:
                        return;
                }
            }
        });
        $form->setTitle("§l§cEndGames §7| §6SkyWars");
        $images = [
            "return" => "textures/ui/refresh_light",
            "sw" => "textures/items/bow_pulling_0"
        ];
        $form->addButton("§l§6LOBBY 1\n§r§7Players Connected: §a".$plsw1, 0, $images["sw"]);
        $form->addButton("§l§6LOBBY 2\n§r§7Players Connected: §a".$plsw2, 0, $images["sw"]);
        $form->addButton("§l§6LOBBY 3\n§r§7Players Connected: §a".$plsw3, 0, $images["sw"]);
        $form->addButton("§cReturn", 0, $images["return"]);

        $player->sendForm($form);

    }

}