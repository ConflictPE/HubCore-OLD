<?php

/**
 * HubCore – ThrowableTNT.php
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

use pocketmine\entity\PrimedTNT;

class ThrowableTNT extends PrimedTNT {

	public function explode(){
		foreach($this->getViewers() as $p) {
			if($p->distance($this) <= 6) {
				$p->knockBack($this, 0, $this->x - $p->x, $this->z - $p->z, 1.4);
				$p->setLastDamagedTime();
			}
		}
	}

}