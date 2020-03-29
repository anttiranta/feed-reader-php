<?php
namespace App\Antti\FeedsAppItemBundle\DataFixtures;

use App\Antti\FeedsAppItemBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends Fixture {

    public function load(ObjectManager $manager) 
    {
        $category = new Category();
        $category->setName("Computers/Software/Internet/Site Management/Content Management")
            ->setDomain("http://www.dmoz.com");
        
        $manager->persist($category);
        $manager->flush();
    }
}