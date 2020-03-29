<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Overblog\GraphQLBundle\OverblogGraphQLBundle::class => ['all' => true],
    App\Antti\FeedsAppCoreBundle\AnttiFeedsAppCoreBundle::class => ['all' => true],
    App\Antti\FeedsAppItemBundle\AnttiFeedsAppItemBundle::class => ['all' => true],
    //App\Antti\FeedsAppItemImportBundle\AnttiFeedsAppItemImportBundle::class => ['all' => true],
    DAMA\DoctrineTestBundle\DAMADoctrineTestBundle::class => ['test' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    Nelmio\CorsBundle\NelmioCorsBundle::class => ['all' => true],
];
