<?php


namespace Hakam\AuthenticationBundle\Tests;


use PHPUnit\Framework\TestCase;
use Hakam\AuthenticationBundle\Adapters\TokenAdapter;
use Hakam\AuthenticationBundle\Services\JWTTokenAuthenticatorService;
/**
 * @category Authentication
 * @author   Ramy Hakam <pencilsoft1@gmail.com>
 * @link     https://github.com/RamyHakam/symfony-authentication-bundle
 */
class JWTTokenAuthenticatorServiceTest extends TestCase
{
    /**
     * @var TokenAdapter $tokenAdapter
     */
    private $tokenAdapter;
    private $jwtTokenAuthenticatorService;

    public function testCanGenerateUserToken()
    {
        $apiKey = 'apiKey';
        $this->tokenAdapter = $this->createMock(TokenAdapter::class);
        $this->tokenAdapter->expects($this->once())
            ->method('encodeToken')
            ->with($this->callback(function ($argument) {
                return $argument == 'apiKey';
            }))
            ->willReturn(serialize($apiKey));
        $this->jwtTokenAuthenticatorService = new JWTTokenAuthenticatorService($this->tokenAdapter);
        return $this->jwtTokenAuthenticatorService->generateUserToken('apiKey');
    }

    /**
     * @depends testCanGenerateUserToken
     * @param string $userApiToken
     */
    public function testCanGetUserDataFromToken(string $userApiToken)
    {
        $this->tokenAdapter = $this->createMock(TokenAdapter::class);
        $this->tokenAdapter->expects($this->once())
            ->method('decodeToken')
            ->with($userApiToken)
            ->willReturn(['apiKey' => unserialize($userApiToken)]);
        $this->jwtTokenAuthenticatorService = new JWTTokenAuthenticatorService($this->tokenAdapter);
        $userData = $this->jwtTokenAuthenticatorService->getUserDataFromToken($userApiToken);
        $this->assertEquals('apiKey', $userData);
    }
}
