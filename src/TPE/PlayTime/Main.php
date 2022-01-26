<?php

declare(strict_types=1);

namespace TPE\PlayTime;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if(!$sender instanceof Player) {
            $sender->sendMessage("You can not run this command via console!");
            return true;
        }
        if(!$sender->hasPermission("playtime.command")) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command!");
            return true;
        }
        if(count($args) > 0) {
            throw new InvalidCommandSyntaxException();
        }
        $time = ((int) floor(microtime(true) * 1000)) - $sender->getFirstPlayed() ?? microtime();
        $seconds = floor($time % 60);
        $minutes = null;
        $hours = null;
        $days = null;
        if($time >= 60){
            $minutes = floor(($time % 3600) / 60);
            if($time >= 3600){
                $hours = floor(($time % (3600 * 24)) / 3600);
                if($time >= 3600 * 24){
                    $days = floor($time / (3600 * 24));
                }
            }
        }
        $uptime = ($minutes !== null ?
                ($hours !== null ?
                    ($days !== null ?
                        "$days days "
                        : "") . "$hours hours "
                    : "") . "$minutes minutes "
                : "") . "$seconds seconds";
        $sender->sendMessage(TextFormat::GREEN."Playtime: ".$uptime);
        return false;
    }

}
