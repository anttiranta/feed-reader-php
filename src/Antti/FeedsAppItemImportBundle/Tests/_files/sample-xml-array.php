<?php

$sampleXmlArray = array(
    '@version' => 2,
    'channel' => array(
        'title' => 'Sample Feed',
        'description' => 'Test description',
        'item' => array(
            0 => array(
                'title' => 'Recommended Desktop Feed Reader Software',
                'description' => 'Short description 1',
                'link' => 'http://www.feedforall.com/feedforall-partners.htm',
                'pubDate' => 'Tue, 26 Oct 2004 14:03:25 -0500'
            ),
            1 => array(
                'title' => 'RSS Solutions for Restaurants',
                'description' => 'Short description 2',
                'link' => 'http://www.feedforall.com/restaurant.htm',
                'category' => array(
                    '#' => 'Some category',
                    '@domain' => 'www.dmoz.com'
                ),
                'comments' => 'http://www.feedforall.com/forum',
                'pubDate' => 'Tue, 19 Oct 2004 11:09:11 -0400'
            )
        )
    )
);
