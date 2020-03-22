<?php


namespace Hakam\AuthenticationBundle\Services;


use Hakam\AuthenticationBundle\Adapters\TokenAdapter;
/**
 * @category Authentication
 * @author   Ramy Hakam <pencilsoft1@gmail.com>
 * @link     https://github.com/ramyhakam/JWTSymfonyAuthenticator
 */
class JWTTokenAuthenticatorService
{
    /**
     * @var TokenAdapter
     */
    private $tokenAdapter;

    /**
     * JWTTokenAuthenticatorService constructor.
     * @param TokenAdapter $tokenAdapter
     */
    public function __construct( TokenAdapter $tokenAdapter)
    {
        $this->tokenAdapter = $tokenAdapter;
    }

    /**
     * Generate new user Token based on Symfony UserInterface
     * @param string $identifierSecretValue
     * @return string
     */
    public function generateUserToken(string $identifierSecretValue = TokenAdapter::TEMP_TOKEN): string
    {
        return $this->tokenAdapter->encodeToken([$this->tokenAdapter->getAuthProperty() => $identifierSecretValue]);
    }

    /**
     * Decode user token to return payload user Data
     * @param string $authorizationToken
     * @return array|null
     */
    public function getUserDataFromToken(string $authorizationToken): ?array
    {
     return  (array)$this->tokenAdapter->decodeToken($authorizationToken)['userData'][0]?? [];
    }

    /**
     * Decode user token to return the payload
     * @param string $authorizationToken
     * @return array|null
     */
    public function decodeUserToken(string $authorizationToken): ?array
    {
        return $this->tokenAdapter->decodeToken($authorizationToken)?? null;
    }
}