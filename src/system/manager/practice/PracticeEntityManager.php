<?php
declare(strict_types=1);

namespace system\manager\practice;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\Player;

final class PracticeEntityManager
{

    public function setPracticeNPC(Player $player)
    {
        $nbt = Entity::createBaseNBT(new Vector3((float)$player->getX(), (float)$player->getY(), (float)$player->getZ()));
        $nbt->setTag(clone $player->namedtag->getCompoundTag("Skin"));
        $human = new PracticeEntity($player->getLevel(), $nbt);
        $human->setNameTag("");
        $human->setNameTagVisible(true);
        $human->setNameTagAlwaysVisible(true);
        $human->yaw = $player->getYaw();
        $human->pitch = $player->getPitch();
        $human->spawnToAll();
    }

}