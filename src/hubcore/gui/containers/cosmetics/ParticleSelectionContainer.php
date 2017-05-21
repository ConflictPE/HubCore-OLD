<?php

/**
 * HubCore – ParticleSelectionContainer.php
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
 * Created on 11/5/17 at 12:48 PM
 *
 */

namespace hubcore\gui\containers\cosmetics;

use core\ChatUtil;
use core\CorePlayer;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\item\cosmetics\particles\ParticleSelectionItem;
use core\particle\ParticleTypes;
use pocketmine\item\Item;
use pocketmine\Player;

class ParticleSelectionContainer extends ChestGUI {

	/** @var int|null */
	protected $selectedSlot = null;

	public function __construct(CorePlayer $owner) {
		parent::__construct($owner);
		$this->setCustomName(Utils::translateColors("&l&aParticle Selection"));
	}

	public function loadSelections(CorePlayer $player) {
		$this->clearAll();
		$items = [
			new ParticleSelectionItem($this, Item::get(Item::BUCKET, 10, 1), Utils::translateColors("&l&6Lava Particle Effect&r"), ParticleTypes::PARTICLE_TYPE_LAVA),
			new ParticleSelectionItem($this, Item::get(Item::END_PORTAL_FRAME, 0, 1), Utils::translateColors("&l&dPortal Particle Effect&r"), ParticleTypes::PARTICLE_TYPE_PORTAL),
			new ParticleSelectionItem($this, Item::get(Item::DYE, 14, 1), ChatUtil::rainbow("Rainbow Particle Effect"), ParticleTypes::PARTICLE_TYPE_RAINBOW),
			new ParticleSelectionItem($this, Item::get(Item::REDSTONE, 10, 1), Utils::translateColors("&l&3Redstone Particle Effect&r"), ParticleTypes::PARTICLE_TYPE_REDSTONE),
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