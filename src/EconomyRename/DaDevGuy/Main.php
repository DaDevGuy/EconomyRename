<?php
declare(strict_types=1);

namespace EconomyRename\DaDevGuy;

use davidglitch04\libEco\libEco;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
    public function onEnable(): void {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();

        //Config Version

        if($this->getConfig()->get("config-ver") !== 2) {
            $this->getLogger()->notice("§l§cWARNING: §r§cEconomyRename's config is NOT up to date. Please delete the config.yml and restart the server or the plugin may not work properly.");
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === "rename") {
            if (!$sender instanceof Player) {
                $sender->sendMessage("Please Use This Command In-Game!");
               return true;
            }
            if (!$sender->hasPermission("economyrename.use")) {
                $sender->sendMessage($this->getConfig()->get("no-permission"));
               return true;
            }
            if (!isset($args[0])){
                $sender->sendMessage($this->getConfig()->get("usage"));
               return true;
            }
                $price = $this->getConfig()->get("rename-price");
                $bal = libEco::myMoney($sender);
                if($bal >= $price){
                    libEco::reduceMoney($sender, $price);
                    $name = $args[0];
                    $item = $sender->getInventory()->getItemInHand();
                    $item->setCustomName($name);
                    $sender->getInventory()->setItemInHand($item);
                    $message = str_replace("{name}", $name, $this->getConfig()->get("rename-sucess"));
                    $sender->sendMessage($message);
                } else {
                    $name = $sender->getName();
                    $message = str_replace("{name}", $name, $this->getConfig()->get("no-money"));
                    $sender->sendMessage($message);
                }
              break;
        }
        return false;
    }
}
