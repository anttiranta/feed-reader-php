<?php
namespace App\Antti\FeedsAppItemBundle\DataFixtures;

use App\Antti\FeedsAppItemBundle\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ItemFixture extends Fixture {

    public function load(ObjectManager $manager) 
    {
        $item = new Item();
        $item->setTitle("RSS Solutions for Restaurants")
            ->setLink("http://www.google.com")
            ->setPubDate(new \DateTime());
        
        $manager->persist($item);
        $manager->flush();
    }
}