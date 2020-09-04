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

namespace system;

/** Pocketmine */
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\types\{DeviceOS, SkinAdapterSingleton};
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TE;
use pocketmine\network\mcpe\RakLibInterface;
use pocketmine\utils\Config;

/** System */
use system\BungeeCord\BungeeListener;
use system\commands\cmdnpc\setnpccommands;
use system\Emote\EmoteWork;
use system\events\{antispam, entitymanager, mainevents};
use system\manager\bedwars\BedWarsEntity;
use system\manager\practice\PracticeEntity;
use system\manager\servers\ServerManager;
use system\manager\skywars\SkyWarsEntity;
use system\skins\ParsonaSkinAdapter;
use system\utils\NavigatorManager;
use system\versions\MCVersions;
use system\utils\DeviceData;
use system\task\{MainHud, MakeAsyncTasks, ServerSyncTask};
use system\scoreboards\Scoreboards;
use system\bossbar\EnderBoss;
use system\BungeeCord\commands\{ListCommand, PingCommand};

/** EndGames Ranks */
use EndGamesRanks\instances\User;


/**
 * Class Main
 * @package system
 */
class Main extends PluginBase implements Listener
{
    /** @var $instance */
    private static $instance;

    /** @var null $originalAdaptor */
    private $originalAdaptor = null;

    /** @var string $PREFIX */
    public const PREFIX = "§cEndGames §7» §r";

    /** @var BungeeCord */
    public static $BungeeCord;

    /**
     * Returns an instance of the plugin
     * @return mixed
     */
    public static function getInstance() : Main
    {
        return self::$instance;
    }

    public function getPrefix(){
        return self::PREFIX;
    }

    public function onLoad() : void
    {
        /** @var  instance */
        self::$instance = $this;
    }

    public function onEnable() : void
    {
        foreach (['BungeeCord.yml'] as $resources) {
            $this->saveResource($resources);
        }
        
        self::$BungeeCord = new Config($this->getDataFolder() . "BungeeCord.yml", Config::YAML);

        if ((bool)self::$BungeeCord->getNested("overwrite-commands.list") === true) {
            $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("list"));
            $this->getServer()->getCommandMap()->register($this->getName(), new ListCommand(self::$BungeeCord->getNested("command-config.list.description"), self::$BungeeCord->getNested("command-config.list.usage"), self::$BungeeCord->getNested("command-config.list.noperm-message"), self::$BungeeCord->getNested("command-config.list.permission")));
            $this->getLogger()->info("Successfully overwritten PocketMines LIST Command!");
        }
        if ((bool)self::$BungeeCord->getNested("overwrite-commands.ping") === true) {
            $this->getServer()->getCommandMap()->register($this->getName(), new PingCommand(self::$BungeeCord->getNested("command-config.ping.description"), self::$BungeeCord->getNested("command-config.ping.usage"), self::$BungeeCord->getNested("command-config.ping.noperm-message"), self::$BungeeCord->getNested("command-config.ping.permission")));
            $this->getLogger()->info("Successfully overwritten PocketMines PING Command!");
        }

        /** @var  $logger */
        $logger = $this->getLogger();

        $this->registerEvents();
        $this->registerTask();
        $this->registerEntity();
        $this->registerCMD();

        /** @var  originalAdaptor */
        $this->originalAdaptor = SkinAdapterSingleton::get();
        SkinAdapterSingleton::set(new ParsonaSkinAdapter);

        if (!in_array(ProtocolInfo::CURRENT_PROTOCOL, [407, 408])) {
            $logger->error("Tu protocolo no es 407 - 408");
            $this->getServer()->shutdown();
        } else {
            $logger->info("§cEndGames §fNetwork §6System enabled");
        }

        foreach ($this->getServer()->getNetwork()->getInterfaces() as $interface) {
            if($interface instanceof RakLibInterface) {
                $interface->setPacketLimit(PHP_INT_MAX);
                $logger->notice("Disabled packet limit");
                break;
            }
        }

        $lobby = $this->getServer()->getLevelByName("world");
        $lobby->setTime(0);
        $lobby->stopTime();
        ServerManager::init($this, ["lobby", "sw", "practice"]);
    }

    public function onDisable() : void
    {
        foreach($this->getServer()->getOnlinePlayers() as $player){
            $player->transfer("localhost", 19132);
        }

        if($this->originalAdaptor !== null){
            SkinAdapterSingleton::set($this->originalAdaptor);
        }
    }

    /** @param Register events */
    private function registerEvents() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new MCVersions($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new mainevents($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EnderBoss($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BungeeListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EmoteWork($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new NavigatorManager($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new antispam($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new entitymanager($this), $this);
    }

    /** @param Register Task */
    private function registerTask() : void {
        $this->getScheduler()->scheduleRepeatingTask(new MainHud($this), 20);
        $this->getScheduler()->scheduleRepeatingTask(new MakeAsyncTasks($this), 100);

    }

    private function registerEntity() : void {
        Entity::registerEntity(PracticeEntity::class, true);
        Entity::registerEntity(SkyWarsEntity::class, true);
        Entity::registerEntity(BedWarsEntity::class, true);
    }

    /**
     * @param DataPacketReceiveEvent $event
     */
    public function onPacketReceive(DataPacketReceiveEvent $event) {
        $packet = $event->getPacket();
        if($packet instanceof LoginPacket) {
            DeviceData::saveDevice($packet->username, $packet->clientData["DeviceOS"]);
        }
    }

    /**
     * @param Player $player
     * @param int $opcion
     */
    public function createScoreboard(Player $player, int $opcion) {
        $api = new Scoreboards();
        switch($opcion) {
            case 0:
                $api->new($player, $player->getName(), "§l§cEndGames §fNetwork");
                $api->setLine($player, 6, TE::RED. "§7────────────────");
                $api->setLine($player, 5, TE::RESET. " §cNick: §7".$player->getName());
                $api->setLine($player, 4, TE::GRAY. "§4");
                $rank = "";
                $user = new User($player);
                if($user->getUserMainGroup()->getId() !== null){
                    $rank = $user->getUserMainGroup()->getName();
                    } else {
                    $rank = "Get it!";
                }
                $api->setLine($player, 3, TE::RESET. " §cRank: §7".$rank);
                $api->setLine($player, 2, TE::YELLOW. "§8§1§8");
                $api->setLine($player, 1, TE::RESET. " §7play.endgames.cf");
                $api->setLine($player, 0, TE::RESET. "§7────────────────");
                $api->getObjectiveName($player);
                break;
        }
    }

    public function registerCMD() : void {
        $this->getServer()->getCommandMap()->register("/setentity", new setnpccommands($this));
    }

    public static function applyNames($instance, $name) :void
    {
        $level3 = Server::getInstance()->getDefaultLevel();
        if(!$level3) return;
        foreach ($level3->getEntities() as $g) {
            switch ($instance){
                case "practice":
                    if ($g instanceof PracticeEntity) {
                        $g->setNameTag($name);
                        $g->setNameTagAlwaysVisible(true);
                        $g->setImmobile(true);
                        $g->setScale(1.2);
                    }
                    break;
                case "skywars":
                    if ($g instanceof SkyWarsEntity){
                        $g->setNameTag($name);
                        $g->setNameTagAlwaysVisible(true);
                        $g->setImmobile(true);
                        $g->setScale(1.2);
                    }
                    break;
                case "bedwars":
                    if ($g instanceof BedWarsEntity){
                        $g->setNameTag($name);
                        $g->setNameTagAlwaysVisible(true);
                        $g->setImmobile(true);
                        $g->setScale(1.2);
                    }
                    break;
            }
        }
    }
}
