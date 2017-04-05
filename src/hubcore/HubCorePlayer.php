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
use core\gui\ChestGUI;
use hubcore\entity\LaunchedItem;
use hubcore\gui\containers\CosmeticGUIContainer;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\network\SourceInterface;
use pocketmine\utils\TextFormat;

class HubCorePlayer extends CorePlayer {

	/** GUI Container types */
	const GUI_TYPE_COSMETICS = "cosmetics";

	public function initEntity() {
		parent::initEntity();

		$this->addGuiContainer(new CosmeticGUIContainer($this), self::GUI_TYPE_COSMETICS, true);
	}

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function onInteract(PlayerInteractEvent $event) {
		$item = $event->getItem();
		if($this->getState() === CorePlayer::STATE_LOBBY) {
			if($item->getId() === Item::COMPASS) {
				$this->sendMessage(TextFormat::GOLD . "- " . TextFormat::GREEN . "Coming soon...");
			} elseif($item->getId() === Item::CLOCK) {
				$this->setPlayersVisible(!$this->hasPlayersVisible());
				$this->sendTranslatedMessage("TOGGLE_PLAYERS", [], true);
			} elseif($item->getId() === Item::BED) {
				$this->kill();
				$this->sendTranslatedMessage("HUB_COMMAND", [], true);
			} else {
				parent::onInteract($event);
			}
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