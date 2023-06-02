<?php

declare(strict_types=1);

namespace victoriovm\ItemCommand;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use function str_replace;

class EventListener implements Listener {
	private ConsoleCommandSender $consoleCommandSender;

	public function __construct(private Main $plugin) {
		$server = $this->plugin->getServer();
		$this->consoleCommandSender = new ConsoleCommandSender($server, $server->getLanguage());
	}

	public function onPlayerInteract(PlayerInteractEvent $event): void {
		$item = $event->getItem();
		$player = $event->getPlayer();

		$command = $this->plugin->getItemCommand($item);

		if ($command !== null) {
			$event->cancel();

			$command = str_replace("{PLAYER}", '"' . $player->getName() . '"', $command);
			$this->plugin->getServer()->dispatchCommand($this->consoleCommandSender, $command);

			$player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
		}
	}

	public function onPlayerItemUse(PlayerItemUseEvent $event): void {
		$item = $event->getItem();

		if ($this->plugin->getItemCommand($item) !== null) {
			$event->cancel();
		}
	}
}