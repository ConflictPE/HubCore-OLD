<?php

/**
 * HubCore – HubCoreListener.php
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
 * Created on 29/01/2017 at 4:46 PM
 *
 */

namespace hubcore;

use core\gui\ChestGUI;
use core\gui\item\GUIItem;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\inventory\PlayerInventory;
use pocketmine\network\protocol\ContainerSetSlotPacket;

class HubCoreListener implements Listener {

	/** @var Main */
	private $plugin;

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * @param PlayerCreationEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onPlayerCreation(PlayerCreationEvent $event) {
		$event->setPlayerClass(HubCorePlayer::class);
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$this->plugin->giveLobbyItems($player);
	}

	/**
	 * Handle data packet receive
	 *
	 * @param DataPacketReceiveEvent $event
	 */
	public function onDataPacketReceive(DataPacketReceiveEvent $event) {
		$pk = $event->getPacket();
		if($pk instanceof ContainerSetSlotPacket) {
			/** @var HubCorePlayer $player */
			$player = $event->getPlayer();
			$inv = $player->getWindowById($pk->windowid);
			if($inv instanceof PlayerInventory and !$inv->contains($pk->item)) {
				$event->setCancelled(true);
				$inv->sendContents($player);
			}
		}
	}

}