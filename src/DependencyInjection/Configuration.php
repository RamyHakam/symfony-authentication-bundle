<?php


namespace Hakam\AuthenticationBundle\DependencyInjection;



use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('hakam_authentication');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
               ->variableNode('public_path_key')->defaultValue('%kernel.project_dir%/config/jwt/public.pem')->end()
               ->variableNode('private_path_key')->defaultValue('%kernel.project_dir%/config/jwt/private.pem')->end()
               ->variableNode('auth_property')->defaultValue('apiToken') ->end()
            ->end();
        return $treeBuilder;
    }

}