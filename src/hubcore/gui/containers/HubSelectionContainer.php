<?php

/**
 * HubCore – HubSelectionContainer.php
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
 * Created on 7/5/2017 at 9:21 PM
 *
 */

namespace hubcore\gui\containers;

use core\CorePlayer;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\item\hubselection\HubSelector;
use hubcore\gui\item\hubselection\SelfHubSelector;
use hubcore\gui\item\hubselection\UnavailableHubSelector;
use pocketmine\item\Item;
use pocketmine\network\protocol\ContainerClosePacket;
use pocketmine\Player;

class HubSelectionContainer extends ChestGUI {

	public function __construct(CorePlayer $player) {
		parent::__construct($player);
		$this->setCustomName(Utils::translateColors("&l&aHub Selection"));
	}

	public function loadSelections(CorePlayer $player) {
		$this->clearAll();
		$servers = $player->getCore()->getNetworkManager()->getNodes()["Hub"]->getServers();
		$server = $player->getCore()->getNetworkManager()->getServer();
		$count = $this->getSize();
		for($i = 1; $i < $count; $i++) {
			$slot = $i - 1;
			if(isset($servers[$i])) {
				if($servers[$i]->isAvailable()) {
					$this->setItem($slot, new HubSelector($this, $i));
				} else {
					$this->setItem($slot, new UnavailableHubSelector($this, $i));
				}
			} elseif($server->getId() === $i) {
				$this->setItem($slot, new SelfHubSelector($this));
			} else {
				$this->setItem($slot, Item::get(Item::AIR));
			}
		}
	}

	public function onOpen(Player $who) {
		$this->loadSelections($who);
		parent::onOpen($who);
	}

	public function onSelect($slot, GUIItem $item, CorePlayer $player) {
		if($item instanceof HubSelector) {
			$item->onClick($player);
		} else {
			if($item instanceof SelfHubSelector) {
				$player->sendMessage("You're already on that server!");
			} elseif($item instanceof UnavailableHubSelector) {
				$player->sendMessage("That server is currently unavailable!");
			}
			$pk = new ContainerClosePacket();
			$pk->windowid = $player->getWindowId($this);
			$player->dataPacket($pk);
		}
	}

}