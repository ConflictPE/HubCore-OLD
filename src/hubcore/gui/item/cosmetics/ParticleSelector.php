<?php

/**
 * HubCore – ParticleSelector.php
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
 * Created on 11/5/17 at 12:54 PM
 *
 */

namespace hubcore\gui\item\cosmetics;

use core\CorePlayer;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\containers\CosmeticsContainer;
use hubcore\HubCorePlayer;
use pocketmine\item\Item;
use pocketmine\network\protocol\ContainerClosePacket;

class ParticleSelector extends GUIItem {

	public function __construct(CosmeticsContainer $parent = null) {
		parent::__construct(Item::get(Item::REDSTONE, 0, 1), $parent);
		$this->setCustomName(Utils::translateColors("&l&eParticles&r"));
	}

	public function onClick(CorePlayer $player) {
		$player->removeWindow($this->getParent());
		$player->addWindow($player->getGuiContainer(HubCorePlayer::PARTICLE_CONTAINER));
	}


}