<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $setting =$this->createPropSetting();

        $manager->persist($setting);

        $manager->flush();

    }
    public function createPropSetting():Setting
    {
        $setting=new Setting();

        $setting->setEmail("healthy-food@gmail.com");
        $setting->setPhone(" 07 66 72 56 59 ");
    
        return $setting;
    }
}
