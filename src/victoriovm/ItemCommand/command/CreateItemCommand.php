<?php

declare(strict_types=1);

namespace victoriovm\ItemCommand\command;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use victoriovm\ItemCommand\Main;

class CreateItemCommand extends Command implements PluginOwned {
	use PluginOwnedTrait;

	public function __construct(private Main $plugin) {
		$this->setPermission("itemcommand.command");
		parent::__construct("createitem", "Create a custom item with command");
	}

	/** @return Main */
	public function getOwningPlugin(): Plugin {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void {
		if (!$this->testPermission($sender) || !($sender instanceof Player)) {
			return;
		}
		$sender->sendForm(new CustomForm("Item Command", [
			new Label("text", "Enter a command to add to the item in your hand, you can also add a name to the item.\n \nAttention: The command will be executed in the console."),
			new Input("command", "Command:", "addbalance {PLAYER} 1000"),
			new Input("customName", "Custom Name: (optional)", "+1000 Coins")
		], function(Player $player, CustomFormResponse $data): void {
			$command = $data->getString("command");
			$customName = $data->getString("customName");
			$customName = $customName == '' ? null : $customName;

			if ($command !== '') {
				$player->sendMessage("§aItem created successfully.");
				$player->getInventory()->setItemInHand($this->plugin->makeItem(
					$player->getInventory()->getItemInHand(),
					$command,
					$customName
				));
			}
		}));
	}
}