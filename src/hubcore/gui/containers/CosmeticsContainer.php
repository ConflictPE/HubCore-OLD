<?php

/**
 * HubCore – CosmeticsContainer.php
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
 * Created on 11/5/17 at 12:50 PM
 *
 */

namespace hubcore\gui\containers;

use core\CorePlayer;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\item\cosmetics\GadgetsSelector;
use hubcore\gui\item\cosmetics\ParticleSelector;
use hubcore\gui\item\cosmetics\PetsSelector;
use pocketmine\inventory\BaseInventory;
use pocketmine\inventory\ChestInventory;
use pocketmine\Player;

class CosmeticsContainer extends ChestGUI {

	public function __construct(CorePlayer $owner) {
		parent::__construct($owner);
		$this->setCustomName(Utils::translateColors("&l&aCosmetics Menu"));
		$this->loadSelections();
	}

	public function loadSelections() {
		$this->clearAll();
		$this->setItem(11, new GadgetsSelector($this));
		$this->setItem(13, new ParticleSelector($this));
		$this->setItem(15, new PetsSelector($this));
	}

	public function onClose(Player $who) {
		BaseInventory::onClose($who);
	}

	public function onSelect($slot, GUIItem $item, CorePlayer $player) {
		if($item instanceof GUIItem) {
			if($item->onClick($player)) {
				//$pk = new ContainerClosePacket();
				//$pk->windowid = $player->getWindowId($this);
				//$player->dataPacket($pk);
			}
		}
	}

}