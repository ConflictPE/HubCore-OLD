<?php

/**
 * HubCore – ServerSelectionContainer
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
 * Created on 9/5/17 at 10:50 AM
 *
 */

namespace hubcore\gui\containers;

use core\CorePlayer;
use core\gui\container\ChestGUI;
use core\gui\item\GUIItem;
use core\Utils;
use hubcore\gui\item\serverselection\ClassicPrisonServerSelector;
use hubcore\gui\item\serverselection\ClassicPvPServerSelector;
use hubcore\gui\item\serverselection\DuelsServerSelector;
use pocketmine\network\protocol\ContainerClosePacket;
use pocketmine\Player;

class ServerSelectionContainer extends ChestGUI {

	public function __construct(CorePlayer $owner) {
		parent::__construct($owner);
		$this->setCustomName(Utils::translateColors("&l&aServer Selector"));
	}

	public function loadSelections(CorePlayer $player) {
		$this->clearAll();
		//$nodes = [
		//	"classic_prison" => "CPSN",
		//	//"factions" => "FAC",
		//	"hub" => "HUB",
		//	"duels" => "DUEL",
		//	"classicpvp" => "CPVP",
		//	//"kitpvp" => "KPVP"
		//];
		$networkManager = $player->getCore()->getNetworkManager();
		$this->setItem(11, new ClassicPrisonServerSelector($this, 1));
		$this->setItem(13, new DuelsServerSelector($this, 1));
		$this->setItem(15, new ClassicPvPServerSelector($this, 1));
		//foreach($nodes as $n) {
		//
		//}
	}

	public function onOpen(Player $who) {
		$this->loadSelections($who);
		parent::onOpen($who);
	}

	public function onSelect($slot, GUIItem $item, CorePlayer $player) {
		if($item instanceof GUIItem) {
			if($item->onClick($player)) {
				$pk = new ContainerClosePacket();
				$pk->windowid = $player->getWindowId($this);
				$player->dataPacket($pk);
			}
		}
	}

}