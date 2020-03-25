<?php


namespace Hakam\AuthenticationBundle\Tests;


use PHPUnit\Framework\TestCase;
use Hakam\AuthenticationBundle\Adapters\TokenAdapter;
use Hakam\AuthenticationBundle\Services\JWTTokenAuthenticatorService;
class JWTTokenAuthenticatorServiceTest extends TestCase
{
    /**
     * @var TokenAdapter $tokenAdapter
     */
    private $tokenAdapter;
    private $jwtTokenAuthenticatorService;

    public function testCanGenerateUserToken()
    {
        $userData = ['apiToken' => 'userToken'];
        $this->tokenAdapter = $this->createMock(TokenAdapter::class);
        $this->tokenAdapter->expects($this->once())
            ->method('getAuthProperty')
            ->willReturn('apiToken');
        $this->tokenAdapter->expects($this->once())
            ->method('encodeToken')
            ->with($this->callback(function ($arguments) {
                return $arguments['apiToken'] == 'userToken';
            }))
            ->willReturn(serialize($userData));
        $this->jwtTokenAuthenticatorService = new JWTTokenAuthenticatorService($this->tokenAdapter);
        return $this->jwtTokenAuthenticatorService->generateUserToken('userToken');
    }

    /**
     * @depends testCanGenerateUserToken
     * @param string $userToken
     */
    public function testCanGetUserDataFromToken(string $userToken)
    {
        $this->tokenAdapter = $this->createMock(TokenAdapter::class);
        $this->tokenAdapter->expects($this->once())
            ->method('decodeToken')
            ->with($userToken)
            ->willReturn(['userData' =>[0=> unserialize($userToken)]]);
        $this->jwtTokenAuthenticatorService = new JWTTokenAuthenticatorService($this->tokenAdapter);
        $userData = $this->jwtTokenAuthenticatorService->getUserDataFromToken($userToken);
        $this->assertEquals('userToken', $userData['apiToken']);
    }
}
