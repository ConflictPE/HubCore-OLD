<?php

/**
 * HubCore – GadgetsSelectionContainerContainer.php
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

namespace hubcore\gui\containers\cosmetics;

use core\CorePlayer;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\item\cosmetics\gadgets\PotatoGun;
use hubcore\gui\item\cosmetics\gadgets\TNTLauncher;
use pocketmine\Player;

class GadgetsSelectionContainer extends ChestGUI {

	/** @var int|null */
	protected $selectedSlot = null;

	public function __construct(CorePlayer $owner) {
		parent::__construct($owner);
		$this->setCustomName(Utils::translateColors("&l&aGadget Selection"));
	}

	public function loadSelections(CorePlayer $player) {
		$this->clearAll();
		$items = [
			new TNTLauncher($this),
			new PotatoGun($this),
		];

		/** @var GUIItem $item */
		foreach($items as $index => $item) {
			if($this->selectedSlot === $index) {
				$item->giveEnchantmentEffect();
			}
			$this->setItem($index, $item);
		}
	}

	public function onOpen(Player $who) {
		$this->loadSelections($who);
		parent::onOpen($who);
	}

	public function onSelect($slot, GUIItem $item, CorePlayer $player) {
		$player->getInventory()->setItem(5, clone $item);
		$this->selectedSlot = $slot;
		$player->removeWindow($this);
	}

}