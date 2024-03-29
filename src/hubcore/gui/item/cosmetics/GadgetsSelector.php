<?php

/**
 * HubCore – GadgetsSelector.php
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

namespace hubcore\gui\item\cosmetics;

use core\CorePlayer;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\containers\CosmeticsContainer;
use hubcore\HubCorePlayer;
use pocketmine\item\Item;

class GadgetsSelector extends GUIItem {

	public function __construct(CosmeticsContainer $parent = null) {
		parent::__construct(Item::get(Item::BOW, 0, 1), $parent);
		$this->setCustomName(Utils::translateColors("&l&eGadgets&r"));
	}

	public function onClick(CorePlayer $player) {
		$player->removeWindow($this->getParent());
		$player->addWindow($player->getGuiContainer(HubCorePlayer::GADGETS_CONTAINER));
	}

}