<?php

/**
 * HubCore – TNTLauncher.php
 *
 * Copyright (C) 2017 Jack Noordhuis
 *
 * This is private software, you cannot redistribute and/or modify it in any way
 * unless given explicit permission to do so. If you have not been given explicit
 * permission to view or modify this software you should take the appropriate actions
 * to remove this software from your device immediately.
 *
 * @author JackNoordhuis
 *
 * Created on 02/04/2017 at 2:56 PM
 *
 */

namespace hubcore\gui\item;

use core\CorePlayer;
use core\gui\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\entity\ThrowableTNT;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

class TNTLauncher extends GUIItem {

	public function __construct(ChestGUI $parent = null) {
		parent::__construct(Item::get(Item::TNT, 0, 1), $parent);
		$this->setCustomName(Utils::translateColors("&l&cThrowable TNT"));
	}

	public function getCooldown() : int {
		return 160;
	}

	public function onClick(CorePlayer $player) {
		$e = Entity::createEntity("ThrowableTNT", $player->getLevel(), new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $player->x),
				new DoubleTag("", $player->y),
				new DoubleTag("", $player->z),
			]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
				new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
				new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", $player->yaw),
				new FloatTag("", $player->pitch),
			]),
		]));
		if($e instanceof ThrowableTNT) {
			$e->spawnToAll();
			$e->setMotion($e->getMotion()->multiply(1.1));
		} else {
			$e->close();
		}
	}

}