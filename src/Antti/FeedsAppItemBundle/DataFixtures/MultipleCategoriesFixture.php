<?php
namespace App\Antti\FeedsAppItemBundle\DataFixtures;

use App\Antti\FeedsAppItemBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MultipleCategoriesFixture extends Fixture {

    public function load(ObjectManager $manager) 
    {
        $category = new Category();
        $category->setName("First category")
            ->setDomain("http://www.first.com");
        
        $manager->persist($category);
        
        $category2 = new Category();
        $category2->setName("Something else")
            ->setDomain("http://www.somethingelse.com");
        
        $manager->persist($category2);
        
        $category3 = new Category();
        $category3->setName("Third category")
            ->setDomain("http://www.thirdcategory.com");
        
        $manager->persist($category3);
        
        $manager->flush();
    }
}