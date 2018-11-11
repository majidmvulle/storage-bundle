<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        $rootNode = $treeBuilder->root('majidmvulle_storage')->addDefaultsIfNotSet();

        $this->addFilesystemSection($rootNode);

        return $treeBuilder;
    }

    private function addFilesystemSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('baseUrl')->isRequired()->end()
                ->arrayNode('filesystem')
                    ->children()
                        ->arrayNode('local')
                            ->children()
                                ->scalarNode('directory')->defaultValue('%kernel.root_dir%/../public/uploads/storage')->end()
                            ->end()
                        ->end()
                        ->arrayNode('s3')
                            ->children()
                                ->scalarNode('directory')->defaultValue('')->end()
                                ->scalarNode('deferDirectory')->defaultValue('%kernel.root_dir%/../public/uploads/storage')->end()
                                ->booleanNode('deferUpload')->defaultValue(false)->end()
                                ->scalarNode('bucket')->isRequired()->end()
                                ->scalarNode('accessKey')->isRequired()->end()
                                ->scalarNode('secretKey')->isRequired()->end()
                                ->scalarNode('cache_control')->defaultValue('')->end()
                                ->scalarNode('acl')
                                    ->defaultValue('public')
                                    ->validate()
                                    ->ifNotInArray(['private', 'public', 'open', 'auth_read', 'owner_read', 'owner_full_control'])
                                        ->thenInvalid('Invalid acl permission - "%s"')
                                    ->end()
                                ->end()
                                ->scalarNode('encryption')
                                    ->defaultValue('')
                                    ->validate()
                                    ->ifNotInArray(['aes256'])
                                        ->thenInvalid('Invalid encryption type - "%s"')
                                    ->end()
                                ->end()
                                ->scalarNode('region')->defaultValue('s3.amazonaws.com')->end()
                                ->scalarNode('version')->defaultValue('latest')->end()
                                ->enumNode('sdk_version')->values([2, 3])->defaultValue(2)->end()
                                ->arrayNode('meta')
                                    ->useAttributeAsKey('name')
                                    ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
