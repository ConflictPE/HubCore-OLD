<?php

/**
 * HubCore – SpawnWarp.php
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
 * Created on 7/5/2017 at 9:21 PM
 *
 */

namespace hubcore\gui\item;

use core\CorePlayer;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use pocketmine\item\Item;

class SpawnWarp extends GUIItem {

	public function __construct(ChestGUI $parent = null) {
		parent::__construct(Item::get(Item::BED, 0, 1), $parent);
		$this->setCustomName(Utils::translateColors("&a&lWarp to spawn&r"));
	}

	public function getCooldown() : int {
		return 0;
	}

	public function onClick(CorePlayer $player) {
		$player->kill();
		$player->sendTranslatedMessage("HUB_COMMAND", [], true);
	}

}