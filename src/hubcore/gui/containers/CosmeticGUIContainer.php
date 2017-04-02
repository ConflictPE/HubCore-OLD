<?php

/**
 * HubCore – CosmeticGUIContainer.php
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

namespace hubcore\gui\containers;

use core\CorePlayer;
use core\gui\ChestGUI;
use core\gui\item\GUIItem;
use hubcore\gui\item\PotatoGun;
use hubcore\gui\item\TNTLauncher;

class CosmeticGUIContainer extends ChestGUI {

	public function __construct(CorePlayer $player) {
		parent::__construct($player);
		$this->addItem(new TNTLauncher($this));
		$this->addItem(new PotatoGun($this));
	}

	public function onSelect($slot, GUIItem $item, CorePlayer $player) {
		$player->getInventory()->setItem(5, clone $item);
		$this->close($player);
	}

}