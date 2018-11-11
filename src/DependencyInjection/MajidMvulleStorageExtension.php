<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MajidMvulleStorageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $this->configureFilesystemAdapter($container, $config);
    }

    public function getAlias()
    {
        return 'majidmvulle_storage';
    }

    private function configureFilesystemAdapter(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('majidmvulle.storage.base_url', $config['baseUrl']);

        // add the default configuration for the local filesystem
        if ($container->hasDefinition('majidmvulle.storage.filesystem.local') && isset($config['filesystem']['local'])) {
            $container->getDefinition('majidmvulle.storage.filesystem.local')->addArgument($config['filesystem']['local']['directory']);
            $container->setParameter('majidmvulle.storage.filesystem.local.directory', $config['filesystem']['local']['directory']);
        } else {
            $container->removeDefinition('majidmvulle.storage.filesystem.local');
        }

        // add the default configuration for the S3 filesystem
        if ($container->hasDefinition('majidmvulle.storage.filesystem.s3') && isset($config['filesystem']['s3'])) {
            $container->getDefinition('majidmvulle.storage.filesystem.s3')
                ->replaceArgument(0, new Reference('majidmvulle.storage.service.s3'))
                ->replaceArgument(1, $config['filesystem']['s3']['bucket'])
                ->replaceArgument(2, ['region' => $config['filesystem']['s3']['region'], 'directory' => $config['filesystem']['s3']['directory'], 'ACL' => $config['filesystem']['s3']['acl']]);

            $container->setParameter('majidmvulle.storage.filesystem.s3.bucket', $config['filesystem']['s3']['bucket']);

            $container->getDefinition('majidmvulle.storage.metadata.amazon')
                ->addArgument(['acl' => $config['filesystem']['s3']['acl'], 'encryption' => $config['filesystem']['s3']['encryption'], 'meta' => $config['filesystem']['s3']['meta'], 'cache_control' => $config['filesystem']['s3']['cache_control']]);

            if (3 === $config['filesystem']['s3']['sdk_version']) {
                $arguments = ['region' => $config['filesystem']['s3']['region'], 'version' => $config['filesystem']['s3']['version']];

                if (isset($config['filesystem']['s3']['secretKey'], $config['filesystem']['s3']['accessKey'])) {
                    $arguments['credentials'] = ['secret' => $config['filesystem']['s3']['secretKey'], 'key' => $config['filesystem']['s3']['accessKey']];
                }

                $container->getDefinition('majidmvulle.storage.service.s3')->replaceArgument(0, $arguments);
            } else {
                $container->getDefinition('majidmvulle.storage.service.s3')->replaceArgument(0, ['secret' => $config['filesystem']['s3']['secretKey'], 'key' => $config['filesystem']['s3']['accessKey']]);
            }
        } else {
            $container->removeDefinition('majidmvulle.storage.filesystem.s3');
            $container->removeDefinition('majidmvulle.storage.filesystem.s3');
        }
    }
}
