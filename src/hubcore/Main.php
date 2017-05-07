<?php

/**
 * HubCore – Main.php
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

use core\Utils;
use hubcore\entity\LaunchedItem;
use hubcore\entity\LaunchedPotato;
use hubcore\entity\ThrowableTNT;
use hubcore\gui\item\CosmeticsSelector;
use hubcore\gui\item\HubSelector;
use hubcore\gui\item\playertoggle\TogglePlayersOff;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginException;
use pocketmine\utils\Config;

class Main extends PluginBase {

	/** @var \core\Main */
	private $components;

	/** @var HubCoreListener */
	private $listener;

	/** @var Config */
	private $settings;

	/** @var Item[] */
	protected $lobbyItems = [];

	/** @var array */
	public static $languages = [
		"en" => "english.json"
	];

	const MESSAGES_FILE_PATH = "lang" . DIRECTORY_SEPARATOR . "messages" . DIRECTORY_SEPARATOR;

	public function onEnable() {
		$components = $this->getServer()->getPluginManager()->getPlugin("Components");
		if(!$components instanceof \core\Main) throw new PluginException("Components plugin isn't loaded!");
		$this->components = $components;
		if(!is_dir($this->getDataFolder() . "data")) @mkdir($this->getDataFolder() . "data");
		if(!is_dir($this->getDataFolder() . "data" . DIRECTORY_SEPARATOR . "skins")) @mkdir($this->getDataFolder() . "data" . DIRECTORY_SEPARATOR . "skins");
		Entity::registerEntity(ThrowableTNT::class);
		Entity::registerEntity(LaunchedItem::class);
		$this->loadConfigs();
		$this->setLobbyItems();
		$this->setListener();
		$this->getServer()->getNetwork()->setName($components->getLanguageManager()->translate("SERVER_NAME", "en"));
	}

	public function loadConfigs() {
		$this->saveResource("Settings.yml");
		$this->settings = new Config($this->getDataFolder() . "Settings.yml",  Config::YAML);
		$path = $this->getDataFolder() . self::MESSAGES_FILE_PATH;
		if(!is_dir($path)) @mkdir($path);
		foreach(self::$languages as $lang => $filename) {
			$file = $path . $filename;
			$this->saveResource(self::MESSAGES_FILE_PATH . $filename);
			if(!is_file($file)) {
				$this->getLogger()->notice("Couldn't find language file for '{$lang}'!\nPath: {$file}");
			} else {
				$this->components->getLanguageManager()->registerLanguage($lang, (new Config($file, Config::JSON))->getAll());
			}
		}
	}

	/**
	 * @return \core\Main
	 */
	public function getCore() {
		return $this->components;
	}

	/**
	 * @return Config
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * @return HubCoreListener
	 */
	public function getListener() {
		return $this->listener;
	}

	/**
	 * Give a player the lobby items
	 *
	 * @param Player $player
	 */
	public function giveLobbyItems(Player $player) {
		self::giveItems($player, $this->lobbyItems, true);
	}

	/**
	 * Set the lobby items
	 */
	public function setLobbyItems() {
		$this->lobbyItems = [
			Item::get(Item::COMPASS),
			Item::get(Item::AIR),
			Item::get(Item::AIR),
			Item::get(Item::AIR),
			new CosmeticsSelector(),
			Item::get(Item::AIR),
			Item::get(Item::AIR),
			new TogglePlayersOff(),
			new HubSelector()
		];
		$this->lobbyItems[0]->setCustomName(Utils::translateColors("&l&dServer Selector&r"));
	}

	/**
	 * Set the listener
	 */
	public function setListener() {
		$this->listener = new HubCoreListener($this);
	}

	/**
	 * Give a player an array of items and order them correctly in their hot bar
	 *
	 * @param Player $player
	 * @param Item[] $items
	 * @param bool $shouldCloneItems
	 */
	public static function giveItems(Player $player, array $items, $shouldCloneItems = false) {
		for($i = 0, $hotbarIndex = 0, $invIndex = 0, $inv = $player->getInventory(), $itemCount = count($items); $i < $itemCount; $i++, $invIndex++) {
			$inv->setItem($invIndex, ($shouldCloneItems ? clone $items[$i] : $items[$i]));
			if($hotbarIndex <= 9) {
				$inv->setHotbarSlotIndex($hotbarIndex, $invIndex);
				$hotbarIndex++;
			}
			continue;
		}
		$inv->sendContents($player);
	}

}