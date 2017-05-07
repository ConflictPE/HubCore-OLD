<?php

/**
 * HubCore – SelfHubSelector.php
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

namespace hubcore\gui\item\hubselection;

use core\ChatUtil;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Main;
use core\Utils;
use pocketmine\item\Item;

class SelfHubSelector extends GUIItem {

	public function __construct(ChestGUI $parent = null) {
		parent::__construct(Item::get(Item::STAINED_CLAY, 14, $server = Main::getInstance()->getNetworkManager()->getServer()->getId()), $parent);
		$this->updateName();
	}

	public function updateName() {
		$server = Main::getInstance()->getNetworkManager()->getServer();
		$this->setCustomName(ChatUtil::centerPrecise(Utils::translateColors("&l&6Hub #{$server->getId()} (&e{$server->getOnlinePlayers()}&6/&e{$server->getMaxPlayers()}&6)&r\n\n&l&cAlready Connected!"), null));
	}

	public function getCooldown() : int {
		return 0;
	}

}