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

namespace system\events;

/** Pocketmine */

use pocketmine\block\Air;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\utils\TextFormat as TE;

/** System */
use system\Main;
use system\floatingtext\FloatingTextAPI;

/** EndGames Ranks */
use EndGamesRanks\instances\User;
use system\utils\DeviceData;

/**
 * Class mainevents
 * @package system\events\mainevents
 * @author: zOmAr
 */
class mainevents implements Listener
{
    /**
     * mainevents constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /*public function OnlyProxyJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getXuid() !== "") {
            $server = "207.244.228.121";
            $player->transfer($server, 19132);
            return;
        }
    }*/

    public function PlayerJoinEvent(PlayerJoinEvent $ev)
    {
        $p = $ev->getPlayer();
        $pn = $p->getName();
        $user = new User($p);

        $groupname = $user->getUserMainGroup()->getId() === null ? $pn : $user->getUserMainGroup()->getName();
        if ($p->isOp() && $groupname === "Owner") {
            $ev->setJoinMessage("§l§4⚡§7[§4OWNER§7]" . " §4" . $pn . " §ahas joined the lobby");

        } elseif ($groupname === "Hero") {
            $ev->setJoinMessage("§l§5✷§7[§5HERO§7]" . " §5" . $pn . " §ahas joined the lobby");

        } elseif ($groupname === "Titan") {
            $ev->setJoinMessage("§l§6✷§7[§cTITAN§7]" . " §3" . $pn . " §ahas joined the lobby");

        } elseif ($groupname === "Legend") {
            $ev->setJoinMessage("§l§3☼§7[§6LEGEND§7]" . " §c" . $pn . " §ahas joined the lobby");

        } elseif ($groupname === "YouTuber") {
            $ev->setJoinMessage("§l§c✹§7[§fYOU§cTUBER§7]" . " §c" . $pn . " §ahas joined the lobby");

        } elseif ($groupname === "Famous") {
            $ev->setJoinMessage("§l§5★§7[§5FAMOUS§7]" . " §6" . $pn . " §ahas joined the lobby");
        } else {
            $ev->setJoinMessage("");
        }

        $this->FloatingTextManager($p);
        $this->playerJoinManager($p);

        if ($p->hasPermission("endgames.fly")) {
            $p->setAllowFlight(true);
        } else {
            $p->setAllowFlight(false);
        }

    }

    public function PlayerQuitEvent(PlayerQuitEvent $ev)
    {
        $player = $ev->getPlayer();
        $ev->setQuitMessage("");
        $player->teleport(new Position(0, 84, -28, Server::getInstance()->getLevelByName("world")));
    }

    public function PlayerItemConsumeEvent(PlayerItemConsumeEvent $ev)
    {
        $player = $ev->getPlayer();
        $level = $player->getLevel()->getName();
        $world = Server::getInstance()->getDefaultLevel()->getName();
        if ($level == $world) {
            $ev->setCancelled(true);
        }
    }

    public function PlayerExhaustEvent(PlayerExhaustEvent $ev)
    {
        $player = $ev->getPlayer();
        $level = $player->getLevel()->getName();
        $world = Server::getInstance()->getDefaultLevel()->getName();
        if ($level == $world) {
            $ev->setCancelled(true);
        }
    }

    public function FloatingTextManager(Player $player)
    {
        $text1 = FloatingTextApi::createText(new Vector3(0.50, 81.00, -16.00));
        $text2 = FloatingTextApi::createText(new Vector3(0.50, 80.67, -16.00));
        $text3 = FloatingTextApi::createText(new Vector3(0.50, 80.30, -16.00));
        $text4 = FloatingTextApi::createText(new Vector3(0.50, 80.00, -16.00));
        $text5 = FloatingTextApi::createText(new Vector3(0.50, 79.57, -16.00));
        $text6 = FloatingTextApi::createText(new Vector3(0.50, 79.28, -16.00));
        $text7 = FloatingTextApi::createText(new Vector3(0.50, 78.82, -16.00));
        $text8 = FloatingTextApi::createText(new Vector3(0.50, 78.53, -16.00));
        $text9 = FloatingTextApi::createText(new Vector3(0.50, 78.20, -16.00));

        if ($player instanceof Player) {
            /** FloatingText Main */
            FloatingTextApi::sendText($text1, $player, "§l§cEndGames §fNetwork");
            FloatingTextApi::sendText($text2, $player, "§7───────────────────");
            FloatingTextApi::sendText($text3, $player, "§7» §c§lTwitter §r§7«");
            FloatingTextApi::sendText($text4, $player, "§o§f@EndGamesNetwork");
            FloatingTextApi::sendText($text5, $player, "§7» §c§lDiscord §r§7«");
            FloatingTextApi::sendText($text6, $player, "§o§fdiscord.gg/UFbmPGh");
            FloatingTextApi::sendText($text7, $player, "§7» §c§lStore §r§7«");
            FloatingTextApi::sendText($text8, $player, "§o§fshop.endgames.cf");
            FloatingTextApi::sendText($text9, $player, "§7───────────────────");

            /** FloatingText For NPC Practice */
            $title_practice = FloatingTextAPI::createText(new Vector3(4.50, 74.50, 31.40));
            $title_practice2 = FloatingTextAPI::createText(new Vector3(4.50, 74.90, 31.40));
            FloatingTextAPI::sendText($title_practice, $player, "§c§lPractice");
            FloatingTextAPI::sendText($title_practice2, $player, "§7§l» §aNew Updated §7«");

            /** FloatingText For NPC Practice */
            $title_practice = FloatingTextAPI::createText(new Vector3(-3.50, 74.50, 31.40));
            $title_practice2 = FloatingTextAPI::createText(new Vector3(-3.50, 74.90, 31.40));
            FloatingTextAPI::sendText($title_practice, $player, "§6§lSkyWars");
            FloatingTextAPI::sendText($title_practice2, $player, "§7§l» §3COMING SOON §7«");

            /** FloatingText For NPC BedWars */
            $title_bw = FloatingTextAPI::createText(new Vector3(-11.50, 74.50, 31.50));
            $title_bw2 = FloatingTextAPI::createText(new Vector3(-11.50, 74.90, 31.50));
            FloatingTextAPI::sendText($title_bw, $player, "§c§lBedWars");
            FloatingTextAPI::sendText($title_bw2, $player, "§7§l» §3COMING SOON §7«");
        }
    }

    public function BlockBreakEvent(BlockBreakEvent $ev)
    {
        $player = $ev->getPlayer();
        $level = $player->getLevel()->getName();
        $world = Server::getInstance()->getDefaultLevel()->getName();
        if ($level == $world) {
            $ev->setCancelled(true);
            if ($player->isOp()) {
                $ev->setCancelled(false);
            }
        }
    }

    public function BlockPlaceEvent(BlockPlaceEvent $ev)
    {
        $player = $ev->getPlayer();
        $level = $player->getLevel()->getName();
        $world = Server::getInstance()->getDefaultLevel()->getName();
        if ($level == $world) {
            $ev->setCancelled(true);
            if ($player->isOp()) {
                $ev->setCancelled(false);
            }
        }
    }

    public function playerJoinManager(Player $player)
    {
        $pn = $player->getName();
        $player->removeAllEffects();
        $player->setHealth(20);
        $player->setFood(20);
        $player->setGamemode(2);
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        $player->setAutoJump(true);
        $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 999999999, 1, false));
        $player->teleport(new Position(0.00, 84.00, -28.00, Server::getInstance()->getLevelByName("world")));
        $player->addTitle("§c§lEndGames §fNetwork", "§fWelcome $pn", 40, 80, 40);
        $this->LobbyItems($player);

    }

    public function LobbyItems(Player $player){
        $navigator = Item::get(345, 0, 1);
        $navigator->setCustomName("§7§l» §aNavigator §7«");
        $navigator->setLore([
            TE::RESET."§l§cEndGames §fNetwork",
            TE::RESET."\n§aWith this item you can see\n§athe list of available servers\n",
            TE::RESET."§c✸ §cHAVE FUN!"
        ]);

        $friends = Item::get(Item::TOTEM);
        $friends->setCustomName("§7§l» §aFriends §7«");
        $friends->setLore([
            TE::RESET."§l§cEndGames §fNetwork",
            TE::RESET."\n§aWith this items you can manage\n§ayour friends on the network\n\n§c✸ Maintenance"
        ]);

        $settings = Item::get(Item::BOOK);
        $settings->setCustomName("§7§l» §aSettings §7«");
        $settings->setLore([
            TE::RESET."§l§cEndGames §fNetwork",
            TE::RESET."\n§aWith this you can see what\n§ais configurable in the network\n\n§6● Beta"
        ]);

        $cosmetics = Item::get(Item::NETHERSTAR);
        $cosmetics->setCustomName("§7§l» §aCosmetics §7«");
        $cosmetics->setLore([
            TE::RESET."§l§cEndGames §fNetwork",
            TE::RESET."\n§6● §aCosmetics manager for those\n§awith rank on the network\n",
            TE::RESET."§l§7» §bGet your rank in the store"
        ]);

        $player->getInventory()->setItem(0, $navigator);
        $player->getInventory()->setItem(1, $friends);
        $player->getInventory()->setItem(7, $cosmetics);
        $player->getInventory()->setItem(8, $settings);
    }

    public function lobbyBorder(PlayerMoveEvent $ev)
    {
        $player = $ev->getPlayer();
        $level = $player->getLevel()->getName();
        $world = Server::getInstance()->getDefaultLevel()->getName();
        $x = round($player->getX());
        $y = round($player->getY());
        $z = round($player->getZ());
        if ($level == $world) {
            if (($x >= 40 || $x <= -39) || ($y <= 68 || $y >= 153) || ($z <= -35)) {
                $player->teleport(new Position(0.00, 84.00, -28.00, Server::getInstance()->getLevelByName("world")));
            }
        }

    }

    public function NoDropLobby(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        $level = $player->getLevel()->getName();
        $world = Server::getInstance()->getDefaultLevel()->getName();
        if ($level == $world) {
            $event->setCancelled(true);
            if ($player->isOp()) {
                $event->setCancelled(false);
            }
        }
    }

    /*public function onDamage(EntityDamageEvent $event) {
        $player = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            if ($damager instanceof Player) {
                $pk = new AnimatePacket();
                $pk->entityRuntimeId = $player->getId();
                $pk->action = AnimatePacket::ACTION_CRITICAL_HIT;

                $damager->dataPacket($pk);
            }
        }
    }*/

    public function EntityDamageEvent(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        $level = $player->getLevel()->getName();
        $world = Server::getInstance()->getDefaultLevel()->getName();
        if ($level == $world) {
            if ($player instanceof Player) {
                $event->setCancelled(true);
            }
        }
    }

    public function noChangeItemSlot(InventoryTransactionEvent $event){
        $entity = $event->getTransaction()->getSource();
        if ($entity->getLevel()->getName() === "world") {
            $event->setCancelled(true);
        }
    }

}