<?php

/**
 * HubCore – TogglePlayersOn.php
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
 * Created on 05/04/2017 at 3:36 PM
 *
 */

namespace hubcore\gui\item\playertoggle;

use core\CorePlayer;
use core\gui\container\ContainerGUI;
use core\gui\item\GUIItem;
use core\Utils;
use pocketmine\item\Dye;
use pocketmine\item\Item;

class TogglePlayersOn extends GUIItem {

	public function __construct(ContainerGUI $parent = null) {
		parent::__construct(Item::get(Item::DYE, Dye::LIME, 1), $parent);
		$this->setCustomName(Utils::translateColors("&l&6Toggle players &a(on)&r"));
	}

	public function getCooldown() : int {
		return 40;
	}

	public function onClick(CorePlayer $player) {
		$player->setPlayersVisible(true);
		$player->getInventory()->setItem(7, new TogglePlayersOff());
	}

}