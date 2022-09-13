<?php

declare(strict_types=1);

namespace victoriovm\ItemCommand\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use Vecnavium\FormsUI\CustomForm;
use victoriovm\ItemCommand\Main;

class CreateItemCommand extends Command implements PluginOwned {
	use PluginOwnedTrait;

	private Main $plugin;

	public function __construct() {
		$this->plugin = Main::getInstance();
		$this->setPermission("createitem.command");
		parent::__construct("createitem", "Create a custom item with command");
	}

	public function getOwningPlugin(): Plugin {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void {
		if (!$this->testPermission($sender)) {
			return;
		}
		if (!($sender instanceof Player)) {
			return;
		}

		$form = new CustomForm(
			function(Player $player, $data): void {
				if ($data === null) {
					return;
				}
				$item = $player->getInventory()->getItemInHand();
				$command = $data[1];
				$customName = $data[2] == '' ? null : $data[2];

				if ($command == '') {
					return;
				}
				$player->sendMessage("§aItem created successfully.");
				$player->getInventory()->setItemInHand($this->plugin->makeItem($item, $command, $customName));
			}
		);
		$form->setTitle("Item Command");
		$form->addLabel("Enter a command to add to the item in your hand, you can also add a name to the item.\n \nAttention: The command will be executed in the console.");
		$form->addInput("Command:", "addbalance {PLAYER} 1000");
		$form->addInput("Custom Name: (optional)", "+1000 Coins");
		$sender->sendForm($form);
	}
}