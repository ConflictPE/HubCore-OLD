<?php

/**
 * HubCore – HubSelector.php
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

use core\gui\item\GUIItem;
use core\CorePlayer;
use core\Utils;
use hubcore\HubCorePlayer;
use pocketmine\item\Item;

class HubSelector extends GUIItem {

	public function __construct() {
		parent::__construct(Item::get(Item::NETHER_STAR, 0, 1), null);
		$this->setCustomName(Utils::translateColors("&l&dHub Selector&r"));
	}

	public function getCooldown() : int {
		return 0;
	}

	public function onClick(CorePlayer $player) {
		$player->addWindow($player->getGuiContainer(HubCorePlayer::HUB_SELECTION_CONTAINER));
	}

}