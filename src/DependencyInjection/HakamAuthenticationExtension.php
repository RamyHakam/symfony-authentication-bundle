<?php


namespace Hakam\AuthenticationBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HakamAuthenticationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);

        $configs = $this->processConfiguration($configuration,$configs);

        $definition = $container->getDefinition('token-adapter');
        $definition->setArgument(1,$configs['public_path_key']);
        $definition->setArgument(2,$configs['private_path_key']);
        $definition->setArgument(3,$configs['auth_property']);
        }
}