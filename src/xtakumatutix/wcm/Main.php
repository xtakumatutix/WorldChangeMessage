<?php

namespace xtakumatutix\wcm;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\utils\Config;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class Main extends PluginBase implements Listener{

    public function onEnable()
    {
        $this->getlogger()->info("読み込み完了");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->Config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, array(
            'メッセージ' => '§f[-§3{origin}§f-]§d➜§f[-§b{target}§f-]',
            '音' => 'beacon.activate'
        ));
    }

    public function change(EntityLevelChangeEvent $event)
    {
        $entity = $event->getEntity();
        $origin = $event->getOrigin();
        $target = $event->getTarget();
        $origin = $origin->getName();
        $target = $target->getName();
        $message = $this->Config->get("メッセージ");
        $message = str_replace("{origin}", (string)$origin, $message);
        $message = str_replace("{target}", (string)$target, $message);
        if($entity instanceof Player){
            $entity->sendPopup("{$message}");
            $pk = new PlaySoundPacket();
            $pk->soundName = $this->Config->get("音");
            $pk->x = $entity->x;
            $pk->y = $entity->y;
            $pk->z = $entity->z;
            $pk->volume = 1;
            $pk->pitch = 1;
            $entity->dataPacket($pk);
        }
    }
}