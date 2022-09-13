<?php

declare(strict_types=1);

namespace victoriovm\ItemCommand;

use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use victoriovm\ItemCommand\command\CreateItemCommand;

use function is_null;

class Main extends PluginBase {
	use SingletonTrait;

	public const TAG_ITEM_COMMAND = "item.command.tag";

	public function onLoad(): void {
		$this->setInstance($this);
	}

	public function onEnable(): void {
		$this->getServer()->getCommandMap()->register("itemcommand:", new CreateItemCommand());
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}

	public function makeItem(Item $item, string $command, ?string $customName = null): Item {
		if (!is_null($customName)) {
			$item->setCustomName($customName);
		}
		$tags = $item->getNamedTag();
		$tags->setString(self::TAG_ITEM_COMMAND, $command);
		$item->setNamedTag($tags);

		return $item;
	}

	public function getItemCommand(Item $item): ?string {
		if ($item->getNamedTag()->getTag(self::TAG_ITEM_COMMAND) != null) {
			return $item->getNamedTag()->getString(self::TAG_ITEM_COMMAND);
		}
		return null;
	}
}