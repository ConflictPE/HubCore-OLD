<?php

/**
 * HubCore – ServerSelector.php
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
 * Created on 9/5/17 at 4:48 PM
 *
 */

namespace hubcore\gui\item;

use core\CorePlayer;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\HubCorePlayer;
use pocketmine\item\Item;

class ServerSelector extends GUIItem {

	public function __construct($parent = null) {
		parent::__construct(Item::get(Item::COMPASS, 0, 1), $parent);
		$this->setCustomName(Utils::translateColors("&l&dServer Selector"));
	}

	public function getCooldown() : int {
		return 0;
	}

	public function onClick(CorePlayer $player) {
		$player->addWindow($player->getGuiContainer(HubCorePlayer::SERVER_SELECTION_CONTAINER));
	}

}