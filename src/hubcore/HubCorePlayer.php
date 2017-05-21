<?php

/**
 * HubCore – HubCorePlayer.php
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
 * Created on 01/04/2017 at 10:56 PM
 *
 */

namespace hubcore;

use core\CorePlayer;
use hubcore\entity\LaunchedItem;
use hubcore\gui\containers\cosmetics\GadgetsSelectionContainer;
use hubcore\gui\containers\cosmetics\ParticleSelectionContainer;
use hubcore\gui\containers\cosmetics\PetSelectionContainer;
use hubcore\gui\containers\CosmeticsContainer;
use hubcore\gui\containers\HubSelectionContainer;
use hubcore\gui\containers\ServerSelectionContainer;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;

class HubCorePlayer extends CorePlayer {

	/** Container types identifiers */
	const COSMETICS_CONTAINER = "cosmetics_selection";
	const GADGETS_CONTAINER = "cosmetics_gadgets_selection";
	const PARTICLE_CONTAINER = "cosmetics_particle_selection";
	const PETS_CONTAINER = "cosmetics_pets_selection";
	const HUB_SELECTION_CONTAINER = "hub_selection";
	const SERVER_SELECTION_CONTAINER = "server_selection";

	public function initEntity() {
		parent::initEntity();

		$this->addGuiContainer(new CosmeticsContainer($this), self::COSMETICS_CONTAINER, true);
		$this->addGuiContainer(new GadgetsSelectionContainer($this), self::GADGETS_CONTAINER, true);
		$this->addGuiContainer(new ParticleSelectionContainer($this), self::PARTICLE_CONTAINER, true);
		$this->addGuiContainer(new PetSelectionContainer($this), self::PETS_CONTAINER, true);
		$this->addGuiContainer(new HubSelectionContainer($this), self::HUB_SELECTION_CONTAINER, true);
		$this->addGuiContainer(new ServerSelectionContainer($this), self::SERVER_SELECTION_CONTAINER, true);
	}

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function onInteract(PlayerInteractEvent $event) {
		$item = $event->getItem();
		if($this->getState() === CorePlayer::STATE_LOBBY) {
				parent::onInteract($event);
		}
	}

	/**
	 * @param float $damage
	 * @param EntityDamageEvent $source
	 *
	 * @return bool
	 */
	public function attack($damage, EntityDamageEvent $source) {
		$source->setCancelled(true);
		if($source instanceof EntityDamageByChildEntityEvent) {
			$child = $source->getChild();
			if($child instanceof LaunchedItem and $child->getItem()->getId() === Item::POTATO) {
				$e = $source->getDamager();
				$this->knockBack($e, 0, $this->x - $e->x, $this->z - $e->z, 0.6);
				$this->setLastDamagedTime();
			}
		}
		return false;
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function onBreak(BlockBreakEvent $event) {
		$event->setCancelled(true);
	}

	/**
	 * @param BlockPlaceEvent $event
	 */
	public function onPlace(BlockPlaceEvent $event) {
		$event->setCancelled(true);
	}

	/**
	 * @param PlayerDropItemEvent $event
	 */
	public function onDrop(PlayerDropItemEvent $event) {
		$event->setCancelled(true);
	}

	public function kill($forReal = false) {
		if($forReal) {
			parent::kill();
		} else {
			$this->setMaxHealth(20);
			$this->setHealth($this->getMaxHealth());
			$this->setFood($this->getMaxFood());
			$this->teleport($this->getLevel()->getSpawnLocation()->add(0.5, 0, 0.5));
		}
	}

}