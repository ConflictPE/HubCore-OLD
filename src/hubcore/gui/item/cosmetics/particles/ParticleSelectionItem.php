<?php

/**
 * HubCore – ParticleSelectionItem.php
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
 * Created on 14/5/17 at 6:38 PM
 *
 */

namespace hubcore\gui\item\cosmetics\particles;

use core\CorePlayer;
use core\gui\item\GUIItem;
use hubcore\gui\containers\cosmetics\ParticleSelectionContainer;
use core\particle\ParticleTypes;
use pocketmine\item\Item;

class ParticleSelectionItem extends GUIItem {

	/** @var string */
	private $type = ParticleTypes::PARTICLE_TYPE_LAVA;

	public function __construct(ParticleSelectionContainer $parent = null, Item $display, string $name, string $type) {
		parent::__construct($display, $parent);
		$this->type = $type;
		$this->setCustomName($name);
	}

	public function onClick(CorePlayer $player) {
		if($player->hasParticle() and $player->getSelectedParticleType() === $this->type) {
			$player->deactivateParticleEffect();
			/** @var ParticleSelectionContainer $parent */
			$parent = $this->getParent();
			$parent->removeSelectedSlot();
			$parent->loadSelections($player);
		} else {
			if($player->hasParticle()) {
				$player->deactivateParticleEffect();
			}
			$player->setSelectedParticleType($this->type);
			$player->activateParticleEffect();
		}
		return false;
	}

}