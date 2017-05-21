<?php

/**
 * HubCore – PetSelectionContainer.php
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
 * Created on 11/5/17 at 12:58 PM
 *
 */

namespace hubcore\gui\containers\cosmetics;

use core\CorePlayer;
use core\entity\pets\PetTypes;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\item\cosmetics\pets\PetSelectionItem;
use pocketmine\item\Item;
use pocketmine\Player;

class PetSelectionContainer extends ChestGUI {

	/** @var int|null */
	protected $selectedSlot = null;

	public function __construct(CorePlayer $owner) {
		parent::__construct($owner);
		$this->setCustomName(Utils::translateColors("&l&Pet Selection"));
	}

	public function loadSelections(CorePlayer $player) {
		$this->clearAll();
		$items = [
			new PetSelectionItem($this, Item::get(Item::SPAWN_EGG, 11, 1), Utils::translateColors("&l&3Cow Pet&r"), PetTypes::PET_TYPE_BABY_COW),
			new PetSelectionItem($this, Item::get(Item::SPAWN_EGG, 12, 1), Utils::translateColors("&l&dPig Pet&r"), PetTypes::PET_TYPE_BABY_PIG),
			new PetSelectionItem($this, Item::get(Item::SPAWN_EGG, 10, 1), Utils::translateColors("&l&eChicken Pet&r"), PetTypes::PET_TYPE_CHICKEN),
			new PetSelectionItem($this, Item::get(Item::SPAWN_EGG, 22, 1), Utils::translateColors("&l&4Ocelot Pet&r"), PetTypes::PET_TYPE_OCELOT),
			new PetSelectionItem($this, Item::get(Item::SPAWN_EGG, 18, 1), Utils::translateColors("&l&7Rabbit Pet&r"), PetTypes::PET_TYPE_RABBIT),
			new PetSelectionItem($this, Item::get(Item::SPAWN_EGG, 14, 1), Utils::translateColors("&l&fWolf Pet&r"), PetTypes::PET_TYPE_WOLF)
		];

		/** @var GUIItem $item */
		foreach($items as $index => $item) {
			if($this->selectedSlot === $index) {
				$item->giveEnchantmentEffect();
				$item->setCustomName($item->getCustomName() . Utils::translateColors("\n\n&l&aClick to disable!"));
			} else {
				$item->setCustomName($item->getCustomName() . Utils::translateColors("\n\n&l&aClick to activate!"));
			}
			$this->setItem($index, $item);
		}
	}

	public function onOpen(Player $who) {
		$this->loadSelections($who);
		parent::onOpen($who);
	}

	public function onSelect($slot, GUIItem $item, CorePlayer $player) {
		$this->selectedSlot = $slot;
		$this->loadSelections($player);
		if($item->onClick($player)) {
			$player->removeWindow($this);
		}
	}

	public function removeSelectedSlot() {
		$this->selectedSlot = null;
	}

}