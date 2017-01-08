<?php

namespace RTG\SuperVIP;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Cancellable;
use pocketmine\utils\TextFormat as TF;

class Loader extends PluginBase implements Listener {
	
	public function onEnable() {
		
		$this->list = array();
		
		$this->getLogger()->warning("Running SuperVIP now!");
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onKick(PlayerKickEvent $e) {
		$p = $e->getPlayer();
			
			if($e->getReason() === "disconnectionScreen.serverFull") {
				if(isset($this->list[strtolower($p->getName())])) {
					$e->setCancelled(false);
				}
				else if($p->hasPermission("serverfull.bypass")) {
					$e->setCancelled(false);
				}
			}
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		switch(strtolower($cmd->getName())) {
			
			case "vip":
				if($sender->isOp()) {
					if(isset($args[0])) {
						switch(strtolower($args[0])) {
							
							case "add":
								if(isset($args[1])) {
									
									$n = $args[1];
									
									if(!(isset($this->list[strtolower($n)]))) {
										$this->list[strtolower($n)] = strtolower($n);
										$sender->sendMessage("You have added $n to the list!");
									}
									else {
										$sender->sendMessage("$n exists!");
									}
								
								}
								else {
									$sender->sendMessage("Usage: /vip add [name]");
								}
								return true;
							break;
							
							case "list":
							
								if(count($this->list) === 0) {
									$sender->sendMessage("0 players");
								}
								else {
									$sender->sendMessage("List of VIPS");
									foreach($this->list as $e) {
										$sender->sendMessage(TF::GREEN . "- $e");
									}
								}
								
								return true;
							break;
							
							case "rm":
								if(isset($args[1])) {
									
									$n = $args[1];
									
									if(isset($this->list[strtolower($n)])) {
										unset($this->list[strtolower($n)]);
										$sender->sendMessage("You have removed $n from the list!");
									}
									else {
										$sender->sendMessage("$n doesn't exist!");
									}
								}
								else {
									$sender->sendMessage("Usage: /vip rm [name]");
								}
								return true;
							break;
							
						}
					}
					else {
						$sender->sendMessage("Usage: /vip < add | list | rm >");
					}
				}
				else {
					$sender->sendMessage("You have no permission to use this command!");
				}
				return true;
			break;
			
		}
	}
	
	public function onDisable() {
	}
	
}