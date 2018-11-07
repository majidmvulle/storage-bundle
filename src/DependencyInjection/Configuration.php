<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\UtilityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('majidmvulle_utility')->addDefaultsIfNotSet();

        $rootNode
            ->children()
                ->arrayNode('mailer')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('from_email')->defaultValue('')->end()
                        ->scalarNode('from_sender_name')->defaultValue('')->end()
                    ->end()
                ->end()//mailer
                ->arrayNode('twilio')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('sid')->defaultValue('')->end()
                        ->scalarNode('token')->defaultValue('')->end()
                        ->scalarNode('from_number')->defaultValue('')->end()
                    ->end()
                ->end()//twilio
            ->end();

        return $treeBuilder;
    }
}
