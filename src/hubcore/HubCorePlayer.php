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
use hubcore\entity\LaunchedPotato;
use hubcore\entity\ThrowableTNT;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\utils\TextFormat;

class HubCorePlayer extends CorePlayer {

	/** @var int */
	private $lastTntThrowTime = 0;

	/**
	 * @var int
	 */
	private $lastPotatoLaunchTime = 0;

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function onInteract(PlayerInteractEvent $event) {
		$item = $event->getItem();
		if($this->getState() === CorePlayer::STATE_LOBBY) {
			$event->setCancelled(true);
			if($item->getId() === Item::COMPASS) {
				$this->sendMessage(TextFormat::GOLD . "- " . TextFormat::GREEN . "Coming soon...");
			} elseif($item->getId() === Item::CLOCK) {
				$this->setPlayersVisible(!$this->hasPlayersVisible());
				$this->sendTranslatedMessage("TOGGLE_PLAYERS", [], true);
			} elseif($item->getId() === Item::TNT) {
				if(($timeLeft = ($time = microtime(true)) - $this->lastTntThrowTime) >= 5) {
					$this->lastTntThrowTime = microtime(true);
					$e = Entity::createEntity("ThrowableTNT", $this->getLevel(), new CompoundTag("", [
						"Pos" => new ListTag("Pos", [
							new DoubleTag("", $this->x),
							new DoubleTag("", $this->y),
							new DoubleTag("", $this->z)
						]),
						"Motion" => new ListTag("Motion", [
							new DoubleTag("", -sin($this->yaw / 180 * M_PI) * cos($this->pitch / 180 * M_PI)),
							new DoubleTag("", -sin($this->pitch / 180 * M_PI)),
							new DoubleTag("", cos($this->yaw / 180 * M_PI) * cos($this->pitch / 180 * M_PI))
						]),
						"Rotation" => new ListTag("Rotation", [
							new FloatTag("", $this->yaw),
							new FloatTag("", $this->pitch)
						]),
					]));
					if($e instanceof ThrowableTNT) {
						$e->spawnToAll();
						$e->setMotion($e->getMotion()->multiply(1.1));
					} else {
						$e->close();
					}
				} else {
					$this->sendTip(TextFormat::YELLOW . "Cooldown " . round(5 - $timeLeft, 1) . "s");
				}
			} elseif($item->getId() === Item::POTATO) {
				if(($timeLeft = ($time = microtime(true)) - $this->lastPotatoLaunchTime) >= 3) {
					$this->lastPotatoLaunchTime = microtime(true);
					$e = Entity::createEntity("LaunchedPotato", $this->getLevel(), new CompoundTag("", [
						"Pos" => new ListTag("Pos", [
							new DoubleTag("", $this->x),
							new DoubleTag("", $this->y),
							new DoubleTag("", $this->z)
						]),
						"Motion" => new ListTag("Motion", [
							new DoubleTag("", -sin($this->yaw / 180 * M_PI) * cos($this->pitch / 180 * M_PI)),
							new DoubleTag("", -sin($this->pitch / 180 * M_PI)),
							new DoubleTag("", cos($this->yaw / 180 * M_PI) * cos($this->pitch / 180 * M_PI))
						]),
						"Rotation" => new ListTag("Rotation", [
							new FloatTag("", $this->yaw),
							new FloatTag("", $this->pitch)
						])
					]), $this);
					if($e instanceof LaunchedPotato) {
						$e->spawnToAll();
						$e->setMotion($e->getMotion()->multiply(1.4));
					} else {
						$e->close();
					}
				} else {
					$this->sendTip(TextFormat::YELLOW . "Cooldown " . round(3 - $timeLeft, 1) . "s");
				}
			} elseif($item->getId() === Item::BED) {
				$this->kill();
				$this->sendTranslatedMessage("HUB_COMMAND", [], true);
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
			if($source->getChild() instanceof LaunchedPotato) {
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

	public function kill($forReal = false) {
		if(!$forReal) {
			$this->setMaxHealth(20);
			$this->setHealth($this->getMaxHealth());
			$this->setFood($this->getMaxFood());
			$this->teleport($this->getLevel()->getSpawnLocation()->add(0.5, 0, 0.5));
		} else {
			parent::kill();
		}
	}

}