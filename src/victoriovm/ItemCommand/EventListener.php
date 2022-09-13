<?php

declare(strict_types=1);

namespace victoriovm\ItemCommand;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\Server;
use function str_replace;

class EventListener implements Listener {
	public function onPlayerInteract(PlayerInteractEvent $event): void {
		$item = $event->getItem();
		$command = Main::getInstance()->getItemCommand($item);

		if ($command === null) {
			return;
		}

		$event->cancel();
		$player = $event->getPlayer();
		$command = str_replace("{PLAYER}", '"' . $player->getName() . '"', $command);
		Server::getInstance()->dispatchCommand(
			new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()),
			$command
		);
		$player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
	}

	public function onPlayerItemUse(PlayerItemUseEvent $event): void {
		$item = $event->getItem();

		if (Main::getInstance()->getItemCommand($item) === null) {
			return;
		}
		$event->cancel();
	}
}