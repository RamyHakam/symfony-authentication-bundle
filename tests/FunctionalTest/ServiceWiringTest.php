<?php


namespace Hakam\AuthenticationBundle\Tests\FunctionalTest;


use Hakam\AuthenticationBundle\Adapters\TokenAdapter;
use Hakam\AuthenticationBundle\Security\HakamJWTAuthenticator;
use Hakam\AuthenticationBundle\Services\JWTTokenAuthenticatorService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPUnit\Framework\TestCase;
/**
 * @category Authentication
 * @author   Ramy Hakam <pencilsoft1@gmail.com>
 * @link     https://github.com/RamyHakam/symfony-authentication-bundle
 */
class ServiceWiringTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;
    protected function setUp(): void
    {
        $kernel = new HakamAuthenticationBundleKernel();
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    public function testAuthenticationServiceWiring(): void
    {
        /** @var JWTTokenAuthenticatorService $authenticationService */
        $authenticationService = $this->container->get('authenticator-service');

        $this->assertInstanceOf(JWTTokenAuthenticatorService::class,$authenticationService);
    }

    public function testJWTAuthenticatorServiceWiring(): void
    {
        /** @var HakamJWTAuthenticator $jwtAuthenticator */
        $jwtAuthenticator = $this->container->get('token-authenticator');

        $this->assertInstanceOf(HakamJWTAuthenticator::class,$jwtAuthenticator);
    }

    public function testTokenAdapterServiceWiring(): void
    {
        /** @var TokenAdapter $tokenAdapter */
        $tokenAdapter = $this->container->get('token-adapter');

        $this->assertInstanceOf(TokenAdapter::class,$tokenAdapter);
    }
}