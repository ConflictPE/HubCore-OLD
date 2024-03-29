<?php

/**
 * HubCore – CosmeticsSelector.php
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
 * Created on 11/5/17 at 6:25 PM
 *
 */

namespace hubcore\gui\item;

use core\CorePlayer;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\HubCorePlayer;
use pocketmine\item\Item;

class CosmeticsSelector extends GUIItem {

	public function __construct($parent = null) {
		parent::__construct(Item::get(Item::CHEST, 0, 1), $parent);
		$this->setCustomName(Utils::translateColors("&l&aCosmetics"));
	}

	public function getCooldown() : int {
		return 0;
	}

	public function onClick(CorePlayer $player) {
		$player->addWindow($player->getGuiContainer(HubCorePlayer::COSMETICS_CONTAINER));
	}

}