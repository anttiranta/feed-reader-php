<?php
namespace App\Antti\FeedsAppItemBundle\DataFixtures;

use App\Antti\FeedsAppItemBundle\Entity\Item;
use App\Antti\FeedsAppItemBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ItemWithCategoryFixture extends Fixture 
{
    public function load(ObjectManager $manager) 
    {
        $item = new Item();
        $item->setTitle("Something")
            ->setLink("http://www.somethingelse.com")
            ->setPubDate(new \DateTime());
        
        $category = new Category();
        $category->setName("A really unique category name")
            ->setDomain("http://www.somecategory.com");
        
        $item->setCategory($category);
        
        $manager->persist($item);
        $manager->flush();
    }
}