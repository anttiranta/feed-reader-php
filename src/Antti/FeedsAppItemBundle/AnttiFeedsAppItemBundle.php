<?php
namespace App\Antti\FeedsAppItemBundle;

use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;
use App\Antti\FeedsAppItemBundle\Repository\CategoryRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnttiFeedsAppItemBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerForAutoconfiguration(ItemRepository::class)
            ->addTag('doctrine.repository_service');
        $container->registerForAutoconfiguration(CategoryRepository::class)
            ->addTag('doctrine.repository_service');
    }
}