<?php

/**
 * HubCore – DuelsServerSelector.php
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
 * Created on 9/5/17 at 5:13 PM
 *
 */

namespace hubcore\gui\item\serverselection;

use core\ChatUtil;
use core\CorePlayer;
use core\gui\item\GUIItem;
use core\Main;
use core\Utils;
use hubcore\gui\containers\ServerSelectionContainer;
use pocketmine\item\Item;
use pocketmine\network\protocol\TransferPacket;

class DuelsServerSelector extends GUIItem {

	/** @var int */
	private $serverId;

	public function __construct(ServerSelectionContainer $parent = null, int $serverId) {
		parent::__construct(Item::get(Item::DIAMOND_SWORD, 0, 1), $parent);
		$this->serverId = $serverId;
		$this->updateName();
	}

	public function getCooldown() : int {
		return 0;
	}

	public function updateName() {
		$server = $this->getNetworkServer();
		if($server->isOnline()) {
			if($server->isAvailable()) {
				$this->setCustomName(ChatUtil::centerPrecise(Utils::translateColors("&l&bDuels &a(&e{$server->getOnlinePlayers()}&a/&e{$server->getMaxPlayers()}&a)&r\n\n&l&aClick to connect!"), null));
			} else {
				$this->setCustomName(ChatUtil::centerPrecise(Utils::translateColors("&l&bDuels &6(&e{$server->getOnlinePlayers()}&6/&e{$server->getMaxPlayers()}&6)&r\n\n&l&cUnavailable"), null));
			}
		} else {
			$this->setCustomName(ChatUtil::centerPrecise(Utils::translateColors("&l&bDuels &6(&coffline&6)&r\n\n&l&cUnavailable"), null));
		}
	}

	public function getNetworkServer() {
		return Main::getInstance()->getNetworkManager()->getNodes()["DUEL"]->getServers()[$this->serverId];
	}

	public function onClick(CorePlayer $player) {
		$server = $this->getNetworkServer();
		if($server->isOnline()) {
			if($server->isAvailable()) {
				$pk = new TransferPacket();
				$pk->address = $server->getHost();
				$pk->port = $server->getPort();
				$player->directDataPacket($pk);
			} else {
				$player->sendMessage("That server is currently unavailable!");
				return true;
			}
		} else {
			$player->sendMessage("That server is currently offline!");
			return true;
		}
		return false;
	}

}