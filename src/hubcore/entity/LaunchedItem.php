<?php

/**
 * HubCore – LaunchedItem.php
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

namespace hubcore\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\Projectile;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\Player;

class LaunchedItem extends Projectile {

	const NETWORK_ID = 64;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.03;
	protected $drag = 0.01;

	/** @var Item */
	protected $item;

	public function __construct(Level $level, CompoundTag $nbt, Item $item = null, Entity $shootingEntity = null){
		parent::__construct($level, $nbt, $shootingEntity);
		if($item instanceof Item) {
			$this->item = clone $item;
		} else {
			$this->item = Item::get(0);
		}
	}

	public function getItem() {
		return $this->item;
	}

	public function setItem(Item $item) {
		$this->item = $item;
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if($this->age > 1200 or $this->isCollided and $this->isAlive()){
			$this->kill();
			$hasUpdate = true;
		}

		$this->timings->stopTiming();

		return $hasUpdate;
	}

	public function spawnTo(Player $player){
		$pk = new AddItemEntityPacket();
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->item = $this->item;
		$player->dataPacket($pk);

		$this->sendData($player);

		parent::spawnTo($player);
	}

}