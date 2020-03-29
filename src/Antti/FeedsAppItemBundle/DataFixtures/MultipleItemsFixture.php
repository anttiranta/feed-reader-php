<?php
namespace App\Antti\FeedsAppItemBundle\DataFixtures;

use App\Antti\FeedsAppItemBundle\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MultipleItemsFixture extends Fixture {

    public function load(ObjectManager $manager) 
    {
        $item = new Item();
        $item->setTitle("RSS Resources")
            ->setDescription('Be sure to take a look at some of our favorite RSS Resources<br> <a href="http://www.rss-specifications.com">RSS Specifications</a><br> <a href="http://www.blog-connection.com">Blog Connection</a><br> <br>')
            ->setLink("http://www.feedforall.com")
            ->setPubDate(new \DateTime('Tue, 26 Oct 2004 14:01:01 -0500', new \DateTimeZone('Europe/London')));
        
        $manager->persist($item);
        
        $item = new Item();
        $item->setTitle("Recommended Desktop Feed Reader Software")
            ->setDescription('<b>FeedDemon</b> enables you to quickly read and gather information from hundreds of web sites - without having to visit them. Don\'t waste any more time checking your favorite web sites for updates. Instead, use FeedDemon and make them come to you. <br> More <a href="http://store.esellerate.net/a.asp?c=1_SKU5139890208_AFL403073819">FeedDemon Information</a>')
            ->setLink("http://www.feedforall.com/feedforall-partners.htm")
            ->setPubDate(new \DateTime('Tue, 26 Oct 2004 14:03:25 -0500', new \DateTimeZone('Europe/London')));
        
        $manager->persist($item);
        
        $item = new Item();
        $item->setTitle("Recommended Web Based Feed Reader Software")
            ->setDescription('<b>FeedScout</b> enables you to view RSS/ATOM/RDF feeds from different sites directly in Internet Explorer. You can even set your Home Page to show favorite feeds. Feed Scout is a plug-in for Internet Explorer, so you won\'t have to learn anything except for how to press 2 new buttons on Internet Explorer toolbar. <br> More <a href="http://www.bytescout.com/feedscout.html">Information on FeedScout</a><br> <br> <br> <b>SurfPack</b> can feature search tools, horoscopes, current weather conditions, LiveJournal diaries, humor, web modules and other dynamically updated content. <br> More <a href="http://www.surfpack.com/">Information on SurfPack</a><br>')
            ->setLink("http://www.feedforall.com/feedforall-partners.htm")
            ->setPubDate(new \DateTime('Tue, 26 Oct 2004 14:06:44 -0500', new \DateTimeZone('Europe/London')));
        
        $manager->persist($item);
        
        $manager->flush();
    }
}