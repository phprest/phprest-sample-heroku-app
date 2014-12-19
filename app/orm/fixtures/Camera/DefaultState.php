<?php namespace App\Orm\Fixtures\Camera;

use Api\Camera\Entity\Camera;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class DefaultState implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        if ($manager->find('Api\Camera\Entity\Camera', Camera::STATE_OFF) or
            $manager->find('Api\Camera\Entity\Camera', Camera::STATE_ON)) {
            return;
        }

        $camera = new Camera(Camera::STATE_OFF);

        $manager->persist($camera);
        $manager->flush();

        echo __CLASS__, "\n";
    }
}
