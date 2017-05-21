<?php

/**
 * HubCore – PetSelectionItem.php
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
 * Created on 11/5/17 at 5:46 PM
 *
 */

namespace hubcore\gui\item\cosmetics\pets;

use core\CorePlayer;
use core\entity\pets\PetTypes;
use core\gui\item\GUIItem;
use hubcore\gui\containers\cosmetics\PetSelectionContainer;
use pocketmine\item\Item;

class PetSelectionItem extends GUIItem {

	/** @var string */
	private $type = PetTypes::PET_TYPE_CHICKEN;

	public function __construct(PetSelectionContainer $parent = null, Item $display, string $name, string $type) {
		parent::__construct($display, $parent);
		$this->type = $type;
		$this->setCustomName($name);
	}

	public function getCooldown() : int {
		return 0;
	}

	public function onClick(CorePlayer $player) {
		if($player->hasPet() and $player->getLastUsedPetType() === $this->type) {
			$player->deactivatePet();
			/** @var PetSelectionContainer $parent */
			$parent = $this->getParent();
			$parent->removeSelectedSlot();
			$parent->loadSelections($player);
		} else {
			$player->setLastUsedPetType($this->type);
			if($player->hasPet()) {
				$player->deactivatePet();
			}
			$player->activatePet();
		}
		return false;
	}

}