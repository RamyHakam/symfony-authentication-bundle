<?php


namespace Hakam\AuthenticationBundle\Tests\FunctionalTest;


use Hakam\AuthenticationBundle\HakamAuthenticationBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class HakamAuthenticationBundleKernel extends Kernel
{
    private $hakamAuthenticationConfig;

    public function __construct( array $config = [])
    {
        $this->hakamAuthenticationConfig = $config;
        parent::__construct('test', true);
    }

    public function registerBundles()
    {
        return [
            new HakamAuthenticationBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container)
        {
           $container->loadFromExtension('hakam_authentication',$this->hakamAuthenticationConfig);
        });
    }

    public function getProjectDir()
    {
        return __DIR__;
    }
}