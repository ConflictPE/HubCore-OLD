<?php

/**
 * HubCore – HubSelector.php
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


namespace hubcore\gui\item\hubselection;

use core\ChatUtil;
use core\CorePlayer;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Main;
use core\Utils;
use pocketmine\item\Item;
use pocketmine\network\protocol\TransferPacket;

class HubSelector extends GUIItem {

	/** @var int */
	private $hubId;

	public function __construct(ChestGUI $parent = null, $hubId = 0) {
		parent::__construct(Item::get(Item::QUARTZ_BLOCK, 0, $hubId), $parent);
		$this->hubId = $hubId;
		$this->updateName();
	}

	public function updateName() {
		$server = $this->getNetworkServer();
		$this->setCustomName(ChatUtil::centerPrecise(Utils::translateColors("&l&aHub #{$server->getId()} (&e{$server->getOnlinePlayers()}&a/&e{$server->getMaxPlayers()}&a)&r\n\n&l&eClick to connect!"), null));
	}

	public function getCooldown() : int {
		return 0;
	}

	public function getNetworkServer() {
		return Main::getInstance()->getNetworkManager()->getNodes()["Hub"]->getServers()[$this->hubId];
	}

	public function onClick(CorePlayer $player) {
		$server = $this->getNetworkServer();
		$pk = new TransferPacket();
		$pk->address = $server->getHost();
		$pk->port = $server->getPort();
		$player->directDataPacket($pk);
	}

}